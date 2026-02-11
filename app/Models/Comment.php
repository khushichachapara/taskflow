<?php

namespace TaskFlow\Models;

class Comment
{
    public $id;
    public $task_id;
    public $comment;
    public $created_at;


    public function __construct(array $data)
    {
        $this->id         = $data['id'] ?? null;
        $this->task_id    = $data['task_id'] ?? null;
        $this->comment    = $data['comment'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }
}
