<?php

namespace TaskFlow\Repositories;
use TaskFlow\Models\Task;

interface TaskRepositoryInterface
{
    public function getAll(int $user_id): array;

    public function findById(int $id ,int $user_id): ?Task;

    public function create(array $data): int;

    public function update(int $id,int $user_id, array $data  ): bool;

    public function softDelete(int $id , int $user_id): bool;

}
