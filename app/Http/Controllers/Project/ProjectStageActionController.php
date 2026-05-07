<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\StageAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectStageActionController extends Controller
{
    /**
     * Store automation
     */
    public function store(Request $request, int $projectId)
    {
        $validated = $request->validate([

            'stage_id'    => 'required',

            'action_type' => 'required',

        ]);

        try {

            /*
        |--------------------------------------------------------------------------
        | Dynamic Config
        |--------------------------------------------------------------------------
        */

            $config = $this->generateConfig(
                $validated['action_type'],
                $request
            );

            /*
        |--------------------------------------------------------------------------
        | Create Automation
        |--------------------------------------------------------------------------
        */

            $stage = StageAction::create([

                'project_id'    => $projectId,

                'stage_id'      => $validated['stage_id'],

                'action_type'   => $validated['action_type'],

                'action_config' => $config,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Load Relations
        |--------------------------------------------------------------------------
        */

            $stage->load('stage');

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            activityLog([

                'module'       => 'projects',

                'action'       => 'created',

                'record_id'    => $projectId,

                'performed_at' => now(),

                'status'       => 'success',

                'message'      => 'Stage Action created successfully.',

            ]);

            /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

            return response()->json([

                'status'  => true,

                'action'  => 'append',

                'target'  => '#satege-mapping',

                'message' => 'Stage Action created successfully',

                'html'    => view('projects.partials.stage-mapping', [

                    'automation' => $stage,

                    'projectId'  => $projectId

                ])->render()

            ]);
        } catch (\Exception $e) {

            Log::error(
                'Automation Store Error: ' . $e->getMessage()
            );

            return response()->json([

                'status'  => false,

                'message' => $e->getMessage() ?? 'Failed to create action'

            ], 500);
        }
    }

    /**
     * Update automation
     */
    public function update(Request $request, int $projectId, int $id)
    {
        $validated = $request->validate([

            'stage_id'    => 'required',

            'action_type' => 'required',

        ]);

        try {

            /*
        |--------------------------------------------------------------------------
        | Find Automation
        |--------------------------------------------------------------------------
        */

            $automation = StageAction::where(
                'project_id',
                $projectId
            )
                ->findOrFail($id);

            /*
        |--------------------------------------------------------------------------
        | Dynamic Config
        |--------------------------------------------------------------------------
        */

            $config = $this->generateConfig(
                $validated['action_type'],
                $request
            );

            /*
        |--------------------------------------------------------------------------
        | Update Automation
        |--------------------------------------------------------------------------
        */

            $automation->update([

                'stage_id'      => $validated['stage_id'],

                'action_type'   => $validated['action_type'],

                'action_config' => $config,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            activityLog([

                'module'       => 'projects',
                'action'       => 'updated',
                'record_id'    => $projectId,

                'performed_at' => now(),

                'status'       => 'success',

                'message'      => 'Stage Action updated successfully.',

            ]);

            /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

            return response()->json([

                'status'  => true,

                'action'  => 'update',

                'target'  => '.satege-mapping',

                'id'      => $automation->id,

                'message' => 'Stage Action updated successfully',

                'html'    => view('projects.partials.stage-mapping', [
                    'projectId' => $projectId,
                    'automation' => $automation,

                ])->render(),

            ]);
        } catch (\Exception $e) {

            Log::error(
                'Automation Update Error: ' . $e->getMessage()
            );

            return response()->json([

                'status'  => false,

                'message' => 'Failed to update Stage Action'

            ], 500);
        }
    }

    /**
     * Generate Action Config
     */
    private function generateConfig(
        string $actionType,
        Request $request
    ): array {

        $config = [];

        switch ($actionType) {

            /*
            |--------------------------------------------------------------------------
            | SMTP
            |--------------------------------------------------------------------------
            */

            case 'smtp':

                $config = [

                    'smtp_id' => $request->smtp_id,

                    'subject' => $request->subject,

                    'message' => $request->message,

                ];

                break;

            /*
            |--------------------------------------------------------------------------
            | WEBHOOK
            |--------------------------------------------------------------------------
            */

            case 'webhook':

                $config = [

                    'url'    => $request->url,

                    'method' => $request->method,

                ];

                break;
        }

        return $config;
    }

    /**
     * Delete automation
     */
    public function destroy(int $projectId, int $id)
    {
        StageAction::where(
            'project_id',
            $projectId
        )
            ->findOrFail($id)
            ->delete();


        activityLog([
            'module' => 'projects',
            'action' => 'created',
            'record_id' => $projectId,
            'performed_at' => now(),
            'status' => 'success',
            'message' => 'Stage Action deleted successfully.',
        ]);


        return response()->json([
            'status' => true,
            'action' => 'delete',
            'target' => '.satege-mapping',
            'id'     => $id,
            'message' => 'Stage Action deleted successfully'
        ]);
    }
}
