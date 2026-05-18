<?php

namespace Modules\Project\Repositories;

use Modules\Project\Models\GeoFilter;
use Modules\Project\Interfaces\GeoFilterRepositoryInterface;

class GeoFilterRepository
implements GeoFilterRepositoryInterface
{
    public function findByProject(
        int $projectId
    ): ?GeoFilter {

        return GeoFilter::where(
            'project_id',
            $projectId
        )->first();
    }

    public function updateOrCreate(
        int $projectId,
        array $data
    ): GeoFilter {

        return GeoFilter::updateOrCreate(
            [
                'project_id' => $projectId
            ],
            $data
        );
    }
}
