<?php

namespace Modules\Project\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Modules\Project\Models\Project;

use Modules\Project\Interfaces\ProjectRepositoryInterface;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Create Project
     */
    public function store(
        array $data
    ): Project {

        return $this->projectRepository
            ->create([

                'name' => $data['name'],

                'slug' => Str::slug($data['name']),

                'website_url' =>
                $data['website_url'] ?? null,

                'event_name' =>
                $data['event_name'] ?? null,

                'flow_type' =>
                $data['flow_type'],

                'invoice_enabled' =>
                request()->boolean('invoice_enabled'),

                'pipedrive_account_id' =>
                $data['pipedrive_account_id'] ?? null,

                'pipeline_id' =>
                $data['pipeline_id'] ?? null,

                'invoice_account_id' =>
                $data['invoice_account_id'] ?? null,

                'created_by' => Auth::id(),

            ]);
    }

    /**
     * Update Project
     */
    public function update(
        int $id,
        array $data
    ): bool {

        return $this->projectRepository
            ->update($id, [

                'name' => $data['name'],

                'website_url' =>
                $data['website_url'] ?? null,

                'event_name' =>
                $data['event_name'] ?? null,

                'flow_type' =>
                $data['flow_type'],

                'invoice_enabled' =>
                request()->boolean('invoice_enabled'),

                'pipedrive_account_id' =>
                $data['pipedrive_account_id'] ?? null,

                'pipeline_id' =>
                $data['pipeline_id'] ?? null,

                'invoice_account_id' =>
                $data['invoice_account_id'] ?? null,

            ]);
    }
}
