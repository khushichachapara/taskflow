<?php

namespace TaskFlow\Repositories;
use TaskFlow\Models\Task;

interface TaskRepositoryInterface
{
    public function getAll(): array;

    public function findById(int $id): ?Task;

    public function create(array $data): int;

    public function update(int $id, array $data): bool;

    public function softDelete(int $id): bool;

}
