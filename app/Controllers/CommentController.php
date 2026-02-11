<?php

namespace TaskFlow\Controllers;

use TaskFlow\Repositories\CommentRepository;
use TaskFlow\Repositories\ActivityRepository;

class CommentController
{
    private $commentRepository;
    private $activityRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
        $this->activityRepository = new ActivityRepository();
    }

    public function store()
    {
        $taskId = $_POST['task_id'] ?? null;
        $commentText = trim($_POST['comment'] ?? '');

        if (empty($taskId) || empty($commentText)) {
            header("Location: /taskflow/tasks");
            exit;
        }

        // Insert comment
        $this->commentRepository->create([
            'task_id' => $taskId,
            'comment' => $commentText
        ]);

        $this->activityRepository->create([
            'task_id' => $taskId,
            'event_type' => 'comment_added',
            'event_message' => 'New comment added'
        ]);

        header("Location: /taskflow/tasks/view?id=" . $taskId);
        exit;
    }
}
