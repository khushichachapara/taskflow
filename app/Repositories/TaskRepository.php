<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
namespace TaskFlow\Repositories;

use TaskFlow\Core\Database;
use TaskFlow\Models\Task;
use PDO;

class TaskRepository implements TaskRepositoryInterface
{

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    //-----------------this for fetch all column simple and second one with aggregate column count with json return api endpoint 
    public function getAll(int $user_id): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                    t.id,
                    t.title,
                    t.description,
                    t.status,
                    t.priority,
                    t.created_at,
                    is_deleted,
                    t.updated_at,
                    COUNT(c.id) AS comment_count
                FROM tasks t
                LEFT JOIN comments c ON c.task_id = t.id
                WHERE t.is_deleted = 0
                AND t.user_id = ?
                GROUP BY t.id
                ORDER BY t.id DESC
        ");

        $stmt->execute([$user_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task($row);
        }
        return $tasks;
    }

    public function getTasksWithCommentCount(int $user_id): array
    {
        $stmt = $this->db->prepare("
                SELECT 
                    t.id,
                    t.title,
                    t.status,
                    t.priority,
                    t.created_at,
                    COUNT(c.id) AS comment_count
                FROM tasks t
                LEFT JOIN comments c ON c.task_id = t.id
                WHERE t.is_deleted = 0
                AND t.user_id = ?
                GROUP BY t.id
                ORDER BY t.id DESC
            ");
         $stmt->execute([$user_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task($row);
        }

        return $tasks;
    }

    public function getFilteredTasks(array $filters,int $user_id): array
    {
        $sql = "
        SELECT 
            t.id,
            t.title,
            t.status,
            t.priority,
            t.created_at,
            COUNT(c.id) AS comment_count
        FROM tasks t
        LEFT JOIN comments c ON c.task_id = t.id
        WHERE t.is_deleted = 0
         AND t.user_id = ?
    ";

        $params = [$user_id];

        if (!empty($filters['status'])) {
            $sql .= " AND t.status = ?";
            $params[] = $filters['status']; 
        }

        if (!empty($filters['search'])) {
            $sql .= " AND t.title LIKE ?";
            $params[] = "%" . $filters['search'] . "%";
        }

        $sql .= " GROUP BY t.id";

        if (!empty($filters['sort'])) {
            $sql .= " ORDER BY t." . $filters['sort'] . " DESC";
        } else {
            $sql .= " ORDER BY t.id DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task($row);
        }

        return $tasks;
    }






    //---------------this is for view specific task page 
    public function findById(int $id, int $user_id): ?Task
    {
        $stmt = $this->db->prepare("
            SELECT * FROM tasks
            WHERE id = ? AND user_id = ?
        ");

        $stmt->execute([$id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Task($row) : null;
    }






    //-----------------crete task insert quary
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (user_id, title, description, status, priority)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['description'],
            $data['status'],
            $data['priority']
        ]);
        return (int)$this->db->lastInsertId();
    }





    //-------------------update or edit quary for task 
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET title = ?, description = ?, status = ?, priority = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['status'],
            $data['priority'],
            $id
        ]);
    }




    //-------------------soft delete task  
    public function softDelete(int $id , int $user_id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET is_deleted = 1 
            WHERE id = ? AND user_id = ?

        ");

        return $stmt->execute([$id ,$user_id]);
    }


    //optional if we want to restore task
    // public function restore(int $id): bool
    // {
    //     $stmt = $this->db->prepare("
    //     UPDATE tasks
    //     SET is_deleted = 0
    //     WHERE id = ?
    // ");

    //     return $stmt->execute([$id]);
    // }
}
