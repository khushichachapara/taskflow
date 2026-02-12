<?php

namespace TaskFlow\Controllers;

use TaskFlow\Repositories\TaskRepository;
use TaskFlow\Repositories\CommentRepository;
use TaskFlow\Repositories\ActivityRepository;

class TaskController
{
    private $taskRepository;
    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }



    //------------activity log helper function so code not get repeted every time
    private function logActivity($taskId, $type, $message)
    {
        $activityRepo = new ActivityRepository();
        $activityRepo->create([
            'task_id' => $taskId,
            'event_type' => $type,
            'event_message' => $message
        ]);
    }




    //-------------this is for list task page
    public function index()
    {
        $basePath = '/taskflow';
        $filters = [
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null,
            'sort'   => $_GET['sort'] ?? null
        ];

        if (!empty($filters['status']) || !empty($filters['search']) || !empty($filters['sort'])) {
            $tasks = $this->taskRepository->getFilteredTasks($filters);
        } else {
            $tasks = $this->taskRepository->getTasksWithCommentCount();
        }

        //echo json_encode($tasks);
        require __DIR__ . '/../../views/tasks/list.php';
    }

    //----------------json api endpoint task list page
    public function apiList()
    {
        header('Content-Type: application/json');

        //====this getAll() method is only for api endpoint json data showing====
        $tasks = $this->taskRepository->getAll();

        echo json_encode($tasks);
        exit;
    }






    //---------------------this is for create task form page
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

        $title = trim($data['title']);
        if (empty($title)) {
            $_SESSION['error'] = 'Title is Required.';
            header("Location: /taskflow/tasks/create");
            exit;
        }
        if (!preg_match("/^[a-zA-Z0-9 _-]+$/", $title)) {
            $_SESSION['error'] = 'Invalid input type.';
            header("Location: /taskflow/tasks/create");
            exit;
        }

        $taskId = $this->taskRepository->create($data);

        $this->logActivity($taskId, 'task_created', 'Task Created ');
        header("Location: /taskflow/tasks");
        exit();
    }






    //-------------------this is for edit tasks form page 
    public function edit($id)
    {
        $basePath = '/taskflow';
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
        $oldTask = $this->taskRepository->findById($id);
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'status' => $_POST['status'],
            'priority' => $_POST['priority']

        ];

        $this->taskRepository->update($id, $data);
        if (
            $oldTask->title !== $data['title'] ||
            $oldTask->description !== $data['description'] ||
            $oldTask->status !== $data['status'] ||
            $oldTask->priority !== $data['priority']
        ) {
            $this->logActivity($id, 'task_updated', 'Task updated');
        }

        header("Location: /taskflow/tasks");
        exit;
    }






    //-------------------just soft delete task
    public function softdelete($id)
    {
        $this->taskRepository->softDelete($id);
        $this->logActivity($id, 'task_deleted', 'Task Deleted');

        if (isset($_GET['from']) && $_GET['from'] === 'view') {
            header("Location: /taskflow/tasks/view?id=" . $id);
        } else {
            header("Location: /taskflow/tasks");
        }
        exit;
    }





    //--------------this is for view specific task page 
    public function view($id)
    {
        $basePath = '/taskflow';
        $task = $this->taskRepository->findById($id);
        if (!$task) {
            echo 'task not found';
            return;
        }

        $commentRepo = new CommentRepository();
        $activityRepo = new ActivityRepository();


        //fetching cooment and activity log
        $comments = $commentRepo->getCommentByTaskId($id);
        $logs = $activityRepo->getLogByTaskId($id);


        require __DIR__ . '/../../views/tasks/view.php';
    }
}
