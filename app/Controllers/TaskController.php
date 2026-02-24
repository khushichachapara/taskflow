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



    //--------------------activity log helper function so code not get repeted every time
    private function logActivity($taskId, $type, $message)
    {
        $activityRepo = new ActivityRepository();
        $activityRepo->create([
            'task_id' => $taskId,
            'event_type' => $type,
            'event_message' => $message
        ]);
    }

    //--------------------helper for get user id 
    private function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }




    //-------------------this is for list task page
    public function index()
    {
        $basePath = '/taskflow';
        $user_id = $this->getUserId();

        $perPage = 5;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $currentPage = max($currentPage, 1);

        $offset = ($currentPage - 1) * $perPage;

        $filters = [
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null,
            'sort'   => $_GET['sort'] ?? null
        ];


        $totalTasks = $this->taskRepository->countfilteredTasks($filters, $user_id);

        $totalPages = ceil($totalTasks / $perPage);

        // Get paginated tasks
        $tasks = $this->taskRepository
            ->getFilteredPaginatedTasks($filters, $user_id, $perPage, $offset);


        //echo json_encode($tasks);
        require __DIR__ . '/../../views/tasks/list.php';
    }


    //----------------json api endpoint task list page
    public function apiList()
    {
        header('Content-Type: application/json');

        // THIS getall() method is only for api endpoint use and not for task list page with filter and pagination
        $tasks = $this->taskRepository->getAll($this->getUserId());

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
            'user_id' => $this->getUserId(),
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
        if (!preg_match("/^[a-zA-Z0-9 ]{5,}$/", $title)) {
            $_SESSION['error'] = 'Invalid input type.';
            header("Location: /taskflow/tasks/create");
            exit;
        }

        $description = trim($data['description']);
       
        if ($data['description'] !== '') {

            if (!preg_match('/^(?=.*[a-zA-Z])[a-zA-Z0-9 .!_,\-]{10,}$/',$description)) {
                $errors[] = "Please add a more meaningful description (minimum 10 characters).";
            }
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
        $task = $this->taskRepository->findById($id, $this->getUserId());
        if (!$task) {
            echo 'task not found';
            return;
        }
        $basePath = '/taskflow';
        require __DIR__ . "/../../views/tasks/edit.php";
    }

    public function update($id)
    {
        $user_id = $this->getUserId();

        $oldTask = $this->taskRepository->findById($id, $user_id);
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'status' => $_POST['status'],
            'priority' => $_POST['priority']

        ];

        $this->taskRepository->update($id, $user_id, $data);
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


        $task = $this->taskRepository->findById($id, $this->getUserId());
        if (!$task) {
            echo 'Task not found';
            return;
        }
        $user_id = $this->getUserId();
        $this->taskRepository->softDelete($id, $user_id);
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

        $user_id = $this->getUserId();

        $task = $this->taskRepository->findById($id, $user_id);
        if (!$task) {
            echo 'Task not found';
            return;
        }
        $basePath = '/taskflow';


        $commentRepo = new CommentRepository();
        $activityRepo = new ActivityRepository();


        //fetching cooment and activity log
        $comments = $commentRepo->getCommentByTaskId($id , $user_id);
        $logs = $activityRepo->getLogByTaskId($id);


        require __DIR__ . '/../../views/tasks/view.php';
    }
}
