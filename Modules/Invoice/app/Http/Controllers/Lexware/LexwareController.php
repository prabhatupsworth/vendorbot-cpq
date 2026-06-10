<?php

namespace Modules\Invoice\Http\Controllers\Lexware;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Modules\Invoice\Models\InvoiceAccount;
use App\Traits\ActivityLogTrait;
use Modules\Invoice\Services\LexwareService;

class LexwareController extends Controller
{
    use ActivityLogTrait;
    public function index()
    {
        $settings = InvoiceAccount::where('type', 'lexware')->get();
        return view('invoice::lexware.index', compact('settings'));
    }

    public function edit(int $id)
    {
        $setting = InvoiceAccount::findOrFail($id);
        return response()->json($setting);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lexware,manual,other',
            'account_name' => 'required|string|max:255',
            'api_key' => 'required|string',
            'base_url' => [
                'required',
                'url',
                'regex:/^https:\/\/[a-zA-Z0-9-]+\.lexware\.com\/?$/'
            ],
        ], [
            'base_url.regex' => 'Enter a valid Lexware URL (https://company.lexware.com)',
        ]);
        $invoiceAccount = InvoiceAccount::create([
            'type' => $request->type,
            'account_name' => $request->account_name,
            'api_key' => $request->api_key,
            'base_url' => $request->base_url,
        ]);

        $this->activityLog([
            'module' => 'lexware',
            'action' => 'created',
            'record_id' => $invoiceAccount->id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Account created successfully.',
        ]);

        return redirect()->back()->with('success', 'Account added successfully.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:lexware,manual,other',
            'account_name' => 'required|string|max:255',
            'api_key' => 'nullable|string',

            'base_url' => [
                'required',
                'url',
                function ($attribute, $value, $fail) use ($request) {

                    if ($request->type !== 'lexware') {
                        return;
                    }

                    $host = parse_url($value, PHP_URL_HOST);

                    if (
                        !$host ||
                        !preg_match('/^[a-zA-Z0-9-]+\.lexware\.io$/', $host)
                    ) {
                        $fail(
                            'Enter a valid Lexware URL (https://company.lexware.io)'
                        );
                    }
                },
            ],
        ], [
            'account_name.required' => 'Account name is required',
            'base_url.required' => 'Base URL is required',
            'base_url.url' => 'Enter a valid URL',
        ]);

        $account = InvoiceAccount::findOrFail($id);

        $updateData = [
            'type' => $validated['type'],
            'account_name' => $validated['account_name'],
            'base_url' => $validated['base_url'],
        ];

        // Update API key only if provided
        if (!empty($validated['api_key'])) {
            $updateData['api_key'] = $validated['api_key'];
        }

        $account->update($updateData);

        $this->activityLog([
            'module' => 'lexware',
            'action' => 'updated',
            'record_id' => $id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Account updated successfully.',
        ]);

        return back()->with(
            'success',
            'Account updated successfully.'
        );
    }
    // show details of the invoice account
    public function details(int $id)
    {
        $account = InvoiceAccount::findOrFail($id);
        $activityLog = ActivityLog::with('user:id,name')
            ->where('module', 'lexware')
            ->where('record_id', $id)
            ->latest() // or orderBy('performed_at', 'desc')
            ->limit(10)
            ->get();
        return response()->json(['account' => $account, 'activityLog' => $activityLog]);
    }

    public function testConnection(int $id)
    {
        try {

            $account = InvoiceAccount::findOrFail($id);

            $lexwareService = new LexwareService($account);

            $response = $lexwareService->testConnection();

            if ($response['success']) {

                $account->update([
                    'is_verified' => true,
                ]);

                $this->activityLog([
                    'module'       => 'lexware',
                    'action'       => 'connection_tested',
                    'record_id'    => $id,
                    'performed_at' => now(),
                    'status'       => 'success',
                    'message'      => 'Lexware connection successful.',
                ]);

                return back()->with(
                    'success',
                    $response['message']
                );
            }

            // ❌ Mark account unverified
            $account->update([
                'is_verified' => false,
            ]);

            $this->activityLog([
                'module'       => 'lexware',
                'action'       => 'connection_failed',
                'record_id'    => $id,
                'performed_at' => now(),
                'status'       => 'failed',
                'message'      => $response['message'] ?? 'Connection failed.',
            ]);

            return back()->with(
                'error',
                $response['message'] ?? 'Connection failed.'
            );
        } catch (\Throwable $e) {

            Log::error('Lexware Test Connection Error', [
                'account_id' => $id,
                'message'    => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
            ]);

            $this->activityLog([
                'module'       => 'lexware',
                'action'       => 'connection_exception',
                'record_id'    => $id,
                'performed_at' => now(),
                'status'       => 'failed',
                'message'      => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                config('app.debug')
                    ? $e->getMessage()
                    : 'Something went wrong while testing the connection.'
            );
        }
    }

    public function destroy(int $id)
    {
        $account = InvoiceAccount::findOrFail($id);
        $account->delete();

        $this->activityLog([
            'module' => 'lexware',
            'action' => 'deleted',
            'record_id' => $id,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Account deleted successfully.',
        ]);

        return redirect()->back()->with('success', 'Account deleted successfully.');
    }
}
