<?php

namespace Modules\Project\Interfaces;

use Modules\Project\Models\GeoFilter;

interface GeoFilterRepositoryInterface
{
    public function findByProject(
        int $projectId
    ): ?GeoFilter;

    public function updateOrCreate(
        int $projectId,
        array $data
    ): GeoFilter;
}
