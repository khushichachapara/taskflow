<?php

namespace TaskFlow\Models;

class Task
{
    public ?int $id = null;
    public string $title;
    public ?string $description = null;
    public string $status = 'pending';
    public string $priority = 'medium';
    public int $is_deleted = 0;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->status = $data['status'] ?? 'pending';
        $this->priority = $data['priority'] ?? 'medium';
        $this->is_deleted = $data['is_deleted'] ?? 0;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
}
    