<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Project\Models\GeoFilter;
use App\Traits\ActivityLogTrait;

class ProjectGeoFilterController extends Controller
{
    use ActivityLogTrait;

    /**
     * 🔹 Get geo filter (one per project)
     */
    public function show(int $projectId)
    {
        $geo = GeoFilter::where('project_id', $projectId)->first();

        return response()->json([
            'status' => true,
            'data' => $geo
        ]);
    }

    /**
     * 🔹 Store / Update (same method)
     */
    public function store(Request $request, int $projectId)
    {


        $validated = $request->validate([
            'latitude_range' => 'required|numeric|min:0|max:1',
            'longitude_range' => 'required|numeric|min:0|max:1',
        ]);

        try {

            $geo = GeoFilter::updateOrCreate(
                ['project_id' => $projectId],
                [
                    ...$validated,
                    'status' => $request->has('status')
                ]
            );
            $this->activityLog([

                'module'       => 'projects',
                'action'       => 'create',
                'record_id'    => $projectId,

                'performed_at' => now(),

                'status'       => 'success',

                'message'      => 'Geo filter saved successfully',

            ]);


            return response()->json([
                'status' => true,
                'action' => 'replace',
                'target' => '#geo-section',
                'message' => 'Geo filter saved successfully',
                'html' => view('project::partials.geo', [
                    'geo' => $geo,
                ])->render(),
            ]);
        } catch (\Exception $e) {

            Log::error('Geo Filter Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to save geo filter'
            ], 500);
        }
    }
}
