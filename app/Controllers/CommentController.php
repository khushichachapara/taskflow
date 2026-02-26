<?php

namespace TaskFlow\Controllers;

use TaskFlow\Repositories\CommentRepository;
use TaskFlow\Repositories\TaskRepository;


class CommentController
{
    private $commentRepository;
    private $taskRepository;


    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
        $this->taskRepository = new TaskRepository();
    }



    //---------------------helper for get user id
    private function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }


    //-----------------comment create
    public function store()
    {
        $taskId = $_POST['task_id'] ?? null;
        $commentText = trim($_POST['comment'] ?? '');
        $userId = $this->getUserId();

        if (empty($taskId) || empty($commentText) || !$userId) {
            header("Location: /taskflow/tasks");
            exit;
        }

        $task = $this->taskRepository->findById($taskId, $userId);

        if (!$task || $task->is_deleted) {
            header("Location: /taskflow/tasks/view?id=" . $taskId);
            exit;
        }
        $this->commentRepository->create([
            'task_id' => $taskId,
            'comment' => $commentText
        ]);

        header("Location: /taskflow/tasks/view?id=" . $taskId);
        exit;
    }
}
