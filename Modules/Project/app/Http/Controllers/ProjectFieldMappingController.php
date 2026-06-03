<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Project\Models\FieldMapping;
use App\Traits\ActivityLogTrait;
use Illuminate\Validation\ValidationException;

class ProjectFieldMappingController extends Controller
{
    use ActivityLogTrait;
    /**
     * Store mapping
     */
    public function store(Request $request, int $projectId)
    {
        $validated = $request->validate([
            'pipedrive_field_key' => 'required|string',
            'system_field'        => 'required|string',
        ]);

        try {

            $existing = FieldMapping::where('project_id', $projectId)
                ->where(function ($query) use ($validated) {
                    $query->where('system_field', $validated['system_field'])
                        ->orWhere('pipedrive_field_key', $validated['pipedrive_field_key']);
                })
                ->first();

            if ($existing) {

                throw ValidationException::withMessages([
                    'pipedrive_field_key' => [
                        'This System Field or Pipedrive Field is already mapped.'
                    ]
                ]);
            }

            $field = FieldMapping::create([
                'project_id'          => $projectId,
                'system_field'        => $validated['system_field'],
                'pipedrive_field_key' => $validated['pipedrive_field_key'],
            ]);

            $this->activityLog([
                'module'       => 'projects',
                'action'       => 'created',
                'record_id'    => $projectId,
                'performed_at' => now(),
                'status'       => 'success',
                'message'      => 'Field mapping created successfully',
            ]);

            return response()->json([
                'status'  => true,
                'action'  => 'replace',
                'target'  => '#field-mappling-list',
                'id'      => $field->id,
                'message' => 'Field mapping created successfully',
                'html'    => view(
                    'project::partials.field-mapping',
                    [
                        'mappings' => FieldMapping::where('project_id', $projectId)
                            ->latest()
                            ->get(),
                        'projectId' => $projectId,
                    ]
                )->render(),
            ]);
        } catch (\Exception $e) {

            Log::error('Field Mapping Error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
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
