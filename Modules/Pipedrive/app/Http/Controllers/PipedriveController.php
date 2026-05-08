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

    public function update(Request $request, int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {
            return back()->with('error', 'Account not found');
        }

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

            // ✅ UPDATE RECORD
            $account->update([
                'account_name' => $validated['account_name'],
                'api_key'      => $validated['api_key'],
                'base_url'     => rtrim($validated['base_url'], '/'),
            ]);

            DB::commit();

            // 🔥 ACTIVITY LOG (SUCCESS)
            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'update',
                'status' => 'success',
                'message' => 'Pipedrive account updated successfully',
                'meta' => [
                    'account_name' => $account->account_name,
                    'base_url' => $account->base_url
                ]
            ]);

            return redirect()->back()
                ->with('success', 'Pipedrive account updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            // 🔥 ACTIVITY LOG (FAILED)
            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'update',
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function connect(int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {
            return back()->with('error', 'Account not found');
        }

        if ($account->is_verified) {
            return back()->with('info', 'Already connected');
        }

        // 👉 here you should call real API later
        $account->update([
            'is_verified' => 1
        ]);

        return back()->with('success', 'Pipedrive account connected successfully!');
    }


    public function syncStages(int $id)
    {
        $account = PipedriveAccount::find($id);

        if (!$account) {
            return back()->with('error', 'Account not found');
        }

        try {
            DB::beginTransaction();

            // 👉 YOUR PIPELINES DATA
            $pipelines = [
                [
                    "id" => 1,
                    "name" => "(Nicht nutzen) Sales Pipeline",
                    "order_nr" => 3,
                    "is_deleted" => false,
                    "is_deal_probability_enabled" => true,
                    "add_time" => "2018-01-09T10:04:30Z",
                    "update_time" => "2019-12-22T21:40:06Z"
                ],
                [
                    "id" => 3,
                    "name" => "Sales Allg. Pipeline",
                    "order_nr" => 0,
                    "is_deleted" => false,
                    "is_deal_probability_enabled" => true,
                    "add_time" => "2018-01-10T10:27:55Z",
                    "update_time" => "2025-10-16T14:00:20Z"
                ],
                [
                    "id" => 5,
                    "name" => "Kochevents Sales Pipeline",
                    "order_nr" => 1,
                    "is_deleted" => false,
                    "is_deal_probability_enabled" => true,
                    "add_time" => "2018-05-29T04:47:29Z",
                    "update_time" => "2019-12-22T21:40:06Z"
                ]
            ];
            foreach ($pipelines as $pipeline) {
                PipedrivePipeline::updateOrCreate(
                    [
                        'pipedrive_account_id' => $account->id,
                        'pipeline_id' => $pipeline['id']
                    ],
                    [
                        'name' => $pipeline['name']
                    ]
                );
            }

            // 👉 YOUR STAGES DATA
            $stages = [
                [
                    "id" => 1,
                    "name" => "Neuer Lead",
                    "pipeline_id" => 1
                ],
                [
                    "id" => 2,
                    "name" => "Kontakt hergestellt",
                    "pipeline_id" => 1
                ],
                [
                    "id" => 3,
                    "name" => "Meeting vereinbart",
                    "pipeline_id" => 1
                ],
                [
                    "id" => 4,
                    "name" => "Bedarf definiert",
                    "pipeline_id" => 1
                ],
                [
                    "id" => 5,
                    "name" => "Angebot abgegeben",
                    "pipeline_id" => 1
                ],
                [
                    "id" => 6,
                    "name" => "Verhandlungen gestartet",
                    "pipeline_id" => 1
                ],
                [
                    "id" => 7,
                    "name" => "Bedarf festgestellt",
                    "pipeline_id" => 3
                ],
                [
                    "id" => 8,
                    "name" => "Kontakte und Infos erfasst",
                    "pipeline_id" => 3
                ],
                [
                    "id" => 9,
                    "name" => "Recherche und Loesung entwickelt",
                    "pipeline_id" => 3
                ],
                [
                    "id" => 10,
                    "name" => "Infopaket erstellt",
                    "pipeline_id" => 3
                ],
                [
                    "id" => 11,
                    "name" => "Infopaket versendet",
                    "pipeline_id" => 3
                ]
            ];

            foreach ($stages as $stage) {
                PipedriveStage::updateOrCreate(
                    [
                        'pipedrive_account_id' => $account->id,
                        'stage_id' => $stage['id']
                    ],
                    [
                        'pipeline_id' => $stage['pipeline_id'],
                        'name' => $stage['name']
                    ]
                );
            }

            $account->update([
                'sync_stages' => 1
            ]);

            DB::commit();

            // 🔥 LOG
            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'sync_stages',
                'status' => 'success',
                'message' => count($stages) . ' stages synced (dummy)'
            ]);

            // return back()->with('success', 'Dummy stages & pipelines synced successfully');
            return response()->json(['message' => 'Dummy stages & pipelines synced successfully']);
        } catch (\Exception $e) {

            DB::rollBack();

            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'sync_stages',
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);

            // return back()->with('error', 'Failed to sync');
            return response()->json(['error' => 'Failed to sync stages'], 500);
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
        | Example Real API Response
        |--------------------------------------------------------------------------
        */

            $fields = [

                [
                    "field_name" => "AR.-Datum",
                    "field_code" => "851db45c30a1a559bb3dc81b5bf31c13d344af84",
                    "field_type" => "date",
                    "options" => null,
                    "subfields" => null,
                    "is_custom_field" => true,
                ],

                [
                    "field_name" => "Rechnung AR Wert",
                    "field_code" => "4f4a189d97153697ec8aa5d8a8d4ccdf60d8b40c",
                    "field_type" => "monetary",
                    "options" => null,
                    "subfields" => json_encode([
                        [
                            "field_code" => "value",
                            "field_name" => "Money Value",
                            "field_type" => "double"
                        ],
                        [
                            "field_code" => "currency",
                            "field_name" => "Currency",
                            "field_type" => "varchar"
                        ]
                    ]),
                    "is_custom_field" => true,
                ],

                [
                    "field_name" => "Chatbot-Konvertierungs-URL",
                    "field_code" => "b527c7c3d24d3b0de2c83a41eb3aff115c65c64f",
                    "field_type" => "text",
                    "options" => null,
                    "subfields" => null,
                    "is_custom_field" => true,
                ],

                [
                    "field_name" => "Recherche Fremdleistung*",
                    "field_code" => "6fe22d2326a0b57270a5dc45065cad615d326697",
                    "field_type" => "enum",
                    "options" => json_encode([
                        [
                            "id" => 34,
                            "label" => "0 - keine Recherche"
                        ],
                        [
                            "id" => 35,
                            "label" => "1 - Recherche einfach"
                        ],
                        [
                            "id" => 36,
                            "label" => "2 - Recherche komplex"
                        ]
                    ]),
                    "subfields" => null,
                    "is_custom_field" => true,
                ],

            ];

            /*
        |--------------------------------------------------------------------------
        | Save Fields
        |--------------------------------------------------------------------------
        */

            foreach ($fields as $field) {

                PipedriveField::updateOrCreate(

                    [
                        'pipedrive_account_id' => $account->id,
                        'field_key' => $field['field_code'],
                    ],

                    [
                        'name' => $field['field_name'],

                        'field_type' => $field['field_type'],

                        'options' => $field['options'],

                        'subfields' => $field['subfields'],

                        'is_custom_field' => $field['is_custom_field'],
                    ]
                );
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
                'message' => 'Fields synced successfully'
            ]);
        } catch (\Exception $e) {

            $this->activityLog([
                'module' => 'pipedrive',
                'record_id' => $account->id,
                'action' => 'sync_fields',
                'status' => 'failed',
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to sync fields'
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
}
