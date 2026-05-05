<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\GeoFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectGeoFilterController extends Controller
{
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

             return response()->json([
                'status' => true,
                'action' => 'replace',
                'target' => '#geo-section',
                'message' => 'Geo filter saved successfully',
                'html' => view('projects.partials.geo', [
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
