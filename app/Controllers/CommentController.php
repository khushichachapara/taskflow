<?php

namespace TaskFlow\Controllers;

use TaskFlow\Repositories\CommentRepository;


class CommentController
{
    private $commentRepository;


    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
    }




    //-----------------comment create
    public function store()
    {
        $taskId = $_POST['task_id'] ?? null;
        $commentText = trim($_POST['comment'] ?? '');

        if (empty($taskId) || empty($commentText)) {
            header("Location: /taskflow/tasks");
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
