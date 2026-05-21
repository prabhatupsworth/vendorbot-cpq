<?php

namespace Modules\Project\Repositories;

use Modules\Project\Interfaces\SmtpRepositoryInterface;
use Modules\Project\Models\Smtp;

class SmtpRepository implements SmtpRepositoryInterface
{
    public function getAll(int $projectId)
    {
        return Smtp::where(
            'project_id',
            $projectId
        )
            ->latest()
            ->get();
    }

    public function find(
        int $projectId,
        int $id
    ): Smtp {

        return Smtp::where(
            'project_id',
            $projectId
        )
            ->findOrFail($id);
    }

    public function store(array $data): Smtp
    {
        return Smtp::create($data);
    }

    public function update(
        int $projectId,
        int $id,
        array $data
    ): bool {

        return $this->find(
            $projectId,
            $id
        )
            ->update($data);
    }

    public function delete(
        int $projectId,
        int $id
    ): bool {

        return $this->find(
            $projectId,
            $id
        )
            ->delete();
    }

    public function updateDefaultType(
        int $projectId,
        // int $excludeId = null
        ?int $excludeId = null
    ): void {

        $query = Smtp::where(
            'project_id',
            $projectId
        )
            ->where('type', 'default');

        if ($excludeId) {

            $query->where(
                'id',
                '!=',
                $excludeId
            );
        }

        $query->update([
            'type' => 'customer'
        ]);
    }

    public function updateConnectionStatus(
        int $projectId,
        int $id,
        bool $status
    ): bool {

        return $this->find(
            $projectId,
            $id
        )
            ->update([
                'connected' => $status
            ]);
    }
}
