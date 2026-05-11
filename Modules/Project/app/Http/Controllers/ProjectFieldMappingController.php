<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Project\Models\FieldMapping;
use App\Traits\ActivityLogTrait;

class ProjectFieldMappingController extends Controller
{
    use ActivityLogTrait;
    /**
     * Store mapping
     */
    public function store(
        Request $request,
        int $projectId
    ) {
        $validated = $request->validate([

            'pipedrive_field_key' => 'required|string',

            'system_field' => 'required|string',

        ]);

        try {

            /*
        |--------------------------------------------------------------------------
        | Create or Update Mapping
        |--------------------------------------------------------------------------
        */

            $field = FieldMapping::updateOrCreate(

                [
                    'project_id' => $projectId,

                    'pipedrive_field_key' => $validated['pipedrive_field_key'],
                ],

                [
                    'system_field' => $validated['system_field'],
                ]
            );

            /*
        |--------------------------------------------------------------------------
        | Dynamic Message
        |--------------------------------------------------------------------------
        */

            $message = $field->wasRecentlyCreated

                ? 'Field mapping created successfully'

                : 'Field mapping updated successfully';

            /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

            $this->activityLog([

                'module' => 'projects',

                'action' => $field->wasRecentlyCreated
                    ? 'created'
                    : 'updated',

                'record_id' => $projectId,

                'performed_at' => now(),

                'status' => 'success',

                'message' => $message,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

            return response()->json([

                'status' => true,

                'action' => $field->wasRecentlyCreated
                    ? 'append'
                    : 'update',

                'target' => '.field-mappling-list',

                'id' => $field->id,

                'message' => $message,

                'html' => view(
                    'project::partials.field-mapping',
                    [
                        'projectId' => $projectId,

                        'mapping' => $field,
                    ]
                )->render(),

            ]);
        } catch (\Exception $e) {

            Log::error(
                'Field Mapping Error: '
                    . $e->getMessage()
            );

            return response()->json([

                'status' => false,

                'message' => $e->getMessage()
                    ?? 'Failed to save mapping'

            ], 500);
        }
    }

    /**
     * Delete mapping
     */
    public function destroy(int $projectId, int $id)
    {
        $field = FieldMapping::where('project_id', $projectId)
            ->findOrFail($id)
            ->delete();


        $this->activityLog([

            'module'       => 'projects',
            'action'       => 'updated',
            'record_id'    => $projectId,
            'performed_at' => now(),
            'status'       => 'success',
            'message'      => 'Field mapping deleted successfully',

        ]);


        return response()->json([

            'status'  => true,
            'action' => 'delete',
            'target' => '.field-mappling-list',
            'id'     => $id,
            'message' => 'Field mapping deleted successfully',

        ]);
    }
}
