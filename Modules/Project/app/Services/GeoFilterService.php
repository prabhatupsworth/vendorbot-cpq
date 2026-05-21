<?php

namespace Modules\Project\Services;

use Modules\Project\Models\GeoFilter;

use Modules\Project\Interfaces\GeoFilterRepositoryInterface;

class GeoFilterService
{
    public function __construct(
        protected GeoFilterRepositoryInterface $geoFilterRepository
    ) {}

    /**
     * Save Geo Filter
     */
    public function save(
        int $projectId,
        array $data
    ): GeoFilter {

        return $this->geoFilterRepository
            ->updateOrCreate(
                $projectId,
                $data
            );
    }
}
