<?php

namespace Modules\Project\Http\Controllers;

use Exception;

use App\Traits\ActivityLogTrait;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Modules\Project\Services\GeoFilterService;
use Modules\Project\Interfaces\GeoFilterRepositoryInterface;
use Modules\Project\Http\Requests\GeoFilter\StoreGeoFilterRequest;

class ProjectGeoFilterController extends Controller
{
    use ActivityLogTrait;

    public function __construct(

        protected GeoFilterRepositoryInterface $geoFilterRepository,

        protected GeoFilterService $geoFilterService

    ) {}

    /**
     * Get Geo Filter
     */
    public function show(int $projectId)
    {
        return response()->json([

            'status' => true,

            'data' => $this->geoFilterRepository
                ->findByProject($projectId)

        ]);
    }

    /**
     * Store / Update Geo Filter
     */
    public function store(
        StoreGeoFilterRequest $request,
        int $projectId
    ) {

        $validated = $request->validated();

        try {

            $geo = $this->geoFilterService
                ->save(
                    $projectId,
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

                'html' => view(
                    'project::partials.geo',
                    [
                        'geo' => $geo,
                    ]
                )->render(),

            ]);
        } catch (Exception $e) {

            Log::error(
                'Geo Filter Error: '
                    . $e->getMessage()
            );

            return response()->json([

                'status' => false,

                'message' => 'Failed to save geo filter'

            ], 500);
        }
    }
}
