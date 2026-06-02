<?php

namespace Modules\Pipedrive\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Pipedrive\Models\PipedriveAccount;
use Modules\Pipedrive\Models\PipedriveField;
use Modules\Pipedrive\Models\PipedrivePipeline;
use Modules\Pipedrive\Models\PipedriveStage;
use App\Traits\ActivityLogTrait;
use Modules\Pipedrive\Http\Requests\UpdatePipedriveRequest;
use Modules\Pipedrive\Services\PipedriveService;

class PipedriveController extends Controller
{
    use ActivityLogTrait;
    public function index()
    {
        $accounts = PipedriveAccount::orderBy('created_at', 'desc')->get();

        return view('pipedrive::index', compact('accounts'));
    }

    public function store(Request $request)
    {
        try {
            // ✅ VALIDATION
            $validated = $request->validate([
                'account_name' => 'required|string|max:255',
                'api_key'      => 'required|string|min:10',
                'base_url'     => 'required|url',
            ], [
                'account_name.required' => 'Account name is required',
                'api_key.required'      => 'API key is required',
                'base_url.required'     => 'Base URL is required',
                'base_url.url'          => 'Enter a valid URL (https://example.pipedrive.com)',
            ]);

            DB::beginTransaction();

            // ✅ CREATE RECORD
            $account = PipedriveAccount::create([
                'u_id'         => (string) Str::uuid(),
                'account_name' => $validated['account_name'],
                'api_key'      => $validated['api_key'],
                'base_url'     => rtrim($validated['base_url'], '/'),
            ]);

            DB::commit();

            // 🔥 ACTIVITY LOG (SUCCESS)
            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'create',
                'status' => 'success',
                'message' => 'Pipedrive account created successfully',
                'meta' => [
                    'account_name' => $account->account_name,
                    'base_url' => $account->base_url
                ]
            ]);

            return redirect()->back()
                ->with('success', 'Pipedrive account added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            // 🔥 ACTIVITY LOG (FAILED)
            $this->activityLog([
                'module' => 'pipedrive',
                'action' => 'create',
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function update(
        UpdatePipedriveRequest $request,
        int $id
    ) {
        $account = PipedriveAccount::find($id);

        if (!$account) {

            return back()->with(
                'error',
                'Account not found'
            );
        }

        try {

            DB::beginTransaction();

            /*
        |--------------------------------------------------------------------------
        | Validated Data
        |--------------------------------------------------------------------------
        */

            $validated = $request->validated();

            /*
        |--------------------------------------------------------------------------
        | Keep Old API Key
        |--------------------------------------------------------------------------
        */

            if (empty($validated['api_key'])) {

                unset($validated['api_key']);
            }

            /*
        |--------------------------------------------------------------------------
        | Clean Base URL
        |--------------------------------------------------------------------------
        */

            $validated['base_url'] = rtrim(
                $validated['base_url'],
                '/'
            );

            /*
        |--------------------------------------------------------------------------
        | Update Account
        |--------------------------------------------------------------------------
        */

            $account->update($validated);

            DB::commit();

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            $this->activityLog([

                'module' => 'pipedrive',

                'record_id' => $account->id,

                'action' => 'update',

                'status' => 'success',

                'message' => 'Pipedrive account updated successfully',

                'meta' => [

                    'account_name' => $account->account_name,

                    'base_url' => $account->base_url,

                ]

            ]);

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Pipedrive account updated successfully!'
                );
        } catch (\Exception $e) {

            DB::rollBack();

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            $this->activityLog([

                'module' => 'pipedrive',

                'record_id' => $account->id ?? null,

                'action' => 'update',

                'status' => 'failed',

                'message' => $e->getMessage(),

            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Something went wrong. Please try again.'
                );
        }
    }


    public function connect(int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {

            return back()->with(
                'error',
                'Account not found'
            );
        }

        if ($account->is_verified) {

            return back()->with(
                'info',
                'Already connected'
            );
        }

        $pipedriveService = new PipedriveService(
            baseUrl: $account->base_url,
            apiKey: $account->api_key
        );

        $connection = $pipedriveService->testConnection();

        if (!$connection['status']) {

            return back()->with(
                'error',
                $connection['message']
            );
        }
        $account->update([
            'is_verified' => 1,
        ]);

        return back()->with(
            'success',
            'Pipedrive account connected successfully!'
        );
    }


    public function syncStages(int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {

            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ], 404);
        }

        try {

            $pipedriveService = new PipedriveService(
                baseUrl: $account->base_url,
                apiKey: $account->api_key
            );

            $response = $pipedriveService
                ->syncStages($account);

            if (!$response['status']) {

                return response()->json([
                    'status' => false,
                    'message' => $response['message']
                ], 422);
            }

            $account->update([
                'sync_stages' => true
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Stages synced successfully',
                'total_stages' => $response['total_stages']
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function syncFields(int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {

            return response()->json([
                'status' => false,
                'message' => 'Account not found'
            ], 404);
        }

        try {

            /*
        |--------------------------------------------------------------------------
        | Initialize Service
        |--------------------------------------------------------------------------
        */

            $pipedriveService = new PipedriveService(
                baseUrl: $account->base_url,
                apiKey: $account->api_key
            );

            /*
        |--------------------------------------------------------------------------
        | Sync Fields
        |--------------------------------------------------------------------------
        */

            $response = $pipedriveService
                ->syncFields($account);

            if (!$response['status']) {

                return response()->json([
                    'status' => false,
                    'message' => $response['message']
                ], 422);
            }

            /*
        |--------------------------------------------------------------------------
        | Update Sync Status
        |--------------------------------------------------------------------------
        */

            $account->update([
                'sync_fields' => true
            ]);

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'sync_fields',
                'status' => 'success',
                'message' => 'Fields synced successfully'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Fields synced successfully',
                'total_fields' => $response['total_fields']
            ]);
        } catch (\Exception $e) {

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'sync_fields',
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function details(int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        $stages = PipedriveStage::with([
            'pipeline' => function ($q) {
                $q->select('pipeline_id', 'name'); // ✅ include key + required column
            },
        ])
            ->where('pipedrive_account_id', $id)
            ->get(['id', 'name', 'stage_id', 'pipeline_id']);

        $fields = PipedriveField::where('pipedrive_account_id', $id)->get();

        //get activity log
        $activityLog = ActivityLog::with('user:id,name')
            ->where('module', 'pipedrive')
            ->where('record_id', $id)
            ->latest() // or orderBy('performed_at', 'desc')
            ->limit(10)
            ->get();


        return response()->json([
            'account' => $account,
            'stages' => $stages,
            'fields' => $fields,
            'activityLog' => $activityLog
        ]);
    }

    public function pipelines(int $accountId)
    {
        $pipelines = PipedrivePipeline::where('pipedrive_account_id', $accountId)
            ->pluck('name', 'id');

        return response()->json([
            'status' => true,
            'data' => $pipelines
        ]);
    }

    public function destroy(int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {

            return back()->with(
                'error',
                'Account not found'
            );
        }

        try {

            $account->delete();

            return back()->with(
                'success',
                'Pipedrive account deleted successfully!'
            );
        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Something went wrong. Please try again.'
            );
        }
    }
}
