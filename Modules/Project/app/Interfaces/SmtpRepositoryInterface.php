<?php

namespace Modules\Project\Interfaces;

use Modules\Project\Models\Smtp;

interface SmtpRepositoryInterface
{
    public function getAll(int $projectId);

    public function find(
        int $projectId,
        int $id
    ): Smtp;

    public function store(array $data): Smtp;

    public function update(
        int $projectId,
        int $id,
        array $data
    ): bool;

    public function delete(
        int $projectId,
        int $id
    ): bool;

    public function updateDefaultType(
        int $projectId,
        ?int $excludeId = null
    ): void;

    public function updateConnectionStatus(
        int $projectId,
        int $id,
        bool $status
    ): bool;
}
