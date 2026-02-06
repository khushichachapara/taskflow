<?php

namespace TaskFlow\Controllers;

use TaskFlow\Repositories\TaskRepository;

class TaskController
{
    private $taskRepository;
    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }
    public function index()
    {
        $tasks = $this->taskRepository->getAll();
        require __DIR__ . '/../../views/tasks/list.php';
    }
    public function create()
    {
        $basePath = '/taskflow';
        require __DIR__ . "/../../views/tasks/create.php";
    }

    public function store()
    {

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'status' => $_POST['status'] ?? 'pending',
            'priority' => $_POST['priority'] ?? 'medium'

        ];
        if (empty(trim($data['title']))) {
            $_SESSION['error'] = 'Title is Required.';
            header("Location: /taskflow/tasks/create");
            exit;
        }


        $this->taskRepository->create($data);
        header("Location: /taskflow/tasks");
        exit();
    }
    public function apiList()
    {
        header('Content-Type: application/json');

        $tasks = $this->taskRepository->getTasksWithCommentCount();

        echo json_encode($tasks);
        exit;
    }
    public function edit($id)
    {
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            echo 'task not found';
            return;
        }
        $basePath = '/taskflow';
        require __DIR__ . "/../../views/tasks/edit.php";
    }
    public function update($id)
    {
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'status' => $_POST['status'],
            'priority' => $_POST['priority']

        ];

        $this->taskRepository->update($id, $data);

        header("Location: /taskflow/tasks");
        exit;
    }
    public function softdelete($id)
    {
        $this->taskRepository->softDelete($id);

        header("Location: /taskflow/tasks");
        exit;
    }
    public function view($id)
    {
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            echo 'task not found';
            return;
        }
        require __DIR__ . '/../../views/tasks/view.php';
    }
}
