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

    //this is for list task page
    public function index()
    {
        $basePath ='/taskflow';
        $tasks = $this->taskRepository->getTasksWithCommentCount();
        // echo json_encode($tasks);
        require __DIR__ . '/../../views/tasks/list.php';
    }
    public function apiList()
    {
        header('Content-Type: application/json');

        $tasks = $this->taskRepository->getTasksWithCommentCount();

        echo json_encode($tasks);
        exit;
    }


    //this is for create task form page
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

        $title =trim($data['title']);
        if (empty($title)) {
            $_SESSION['error'] = 'Title is Required.';
            header("Location: /taskflow/tasks/create");
            exit;
        }
        if(!preg_match("/^[a-zA-Z0-9_-]+$/",$title)){
            $_SESSION['error'] = 'Invalid input type.';
            header("Location: /taskflow/tasks/create");
            exit;
        }


        $this->taskRepository->create($data);
        header("Location: /taskflow/tasks");
        exit();
    }



    //this is for edit tasks form page 
    public function edit($id)
    {
        $basePath ='/taskflow';
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



    //just soft delete task
    public function softdelete($id)
    {
        $this->taskRepository->softDelete($id);

        header("Location: /taskflow/tasks");
        exit;
    }


    //this is for view specific task page 
    public function view($id)
    {
        $basePath = '/taskflow';
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            echo 'task not found';
            return;
        }
        require __DIR__ . '/../../views/tasks/view.php';
    }
}
