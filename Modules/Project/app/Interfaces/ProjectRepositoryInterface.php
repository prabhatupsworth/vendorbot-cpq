<?php

namespace Modules\Project\Interfaces;

use Modules\Project\Models\Project;

interface ProjectRepositoryInterface
{
    public function getAll(array $filters = []);

    public function find(int $id): Project;

    public function create(array $data): Project;

    public function update(
        int $id,
        array $data
    ): bool;

    public function delete(int $id): bool;
}
