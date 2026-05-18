<?php

namespace Modules\Project\Repositories;

use Modules\Project\Models\Project;

use Modules\Project\Interfaces\ProjectRepositoryInterface;

class ProjectRepository
implements ProjectRepositoryInterface
{
    public function getAll(
        array $filters = []
    ) {

        $query = Project::query();

        if (!auth()->user()->hasRole('super_admin')) {

            $query->whereHas('users', function ($q) {

                $q->where(
                    'users.id',
                    auth()->id()
                );
            });
        }

        if (!empty($filters['search'])) {

            $search = $filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )

                    ->orWhere(
                        'website_url',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'event_name',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        return $query
            ->with([
                'pipedriveAccount',
                'invoiceAccount'
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function find(int $id): Project
    {
        return Project::findOrFail($id);
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(
        int $id,
        array $data
    ): bool {

        return $this->find($id)
            ->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->find($id)
            ->delete();
    }
}
