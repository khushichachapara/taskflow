<?php

namespace TaskFlow\Repositories;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use TaskFlow\Core\Database;
use TaskFlow\Models\Task;
use TaskFlow\Core\RedisService;
use PDO;

class TaskRepository implements TaskRepositoryInterface
{

    private PDO $db;
    private RedisService $redis;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->redis = new RedisService();
    }


    //-------------------clear cache 
    private function clearUserTaskCache(int $user_id): void
    {
        $this->redis->deleteByPattern("tasks:user:$user_id:*");
    }

    private function clearSingleTaskCache(int $id, int $user_id): void
    {
        $this->redis->delete("task:id:$id:user:$user_id");
    }


    //-----------------this for fetch all column simple and second one with aggregate column count with json return api endpoint 
    public function getAll(int $user_id): array
    {

        $cacheKey = "tasks:user:$user_id:all";

        $cached = $this->redis->get($cacheKey);

        if ($cached !== null) {
            $rows = json_decode($cached, true);
           if (is_array($rows)) {
        return array_map(fn($row) => new Task($row), $rows);
    }
        }

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

        $this->redis->set($cacheKey, json_encode($rows), 600);
        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task($row);
        }
        return $tasks;
    }

    //--------------------To calculate total pages for pagination based on filters and search
    public function countfilteredTasks(array $filters, int $user_id): int
    {
        $sql = "SELECT COUNT(*) as total FROM tasks WHERE is_deleted = 0 AND user_id = ?";
        $params = [$user_id];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND title LIKE ?";
            $params[] = "%" . $filters['search'] . "%";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }


    //---------------------------------To fetch current page data based on filters, search, sort and pagination
    public function getFilteredPaginatedTasks(array $filters, int $user_id, int $limit, int $offset): array
    {

        //if exists in redis
        $cacheKey = "tasks:user:$user_id:" . md5(json_encode($filters) . ":$limit:$offset");

        $cached = $this->redis->get($cacheKey);

        if ($cached !== null) {
            $rows = json_decode($cached, true);
           if (is_array($rows)) {
        return array_map(fn($row) => new Task($row), $rows);
    }
        }


        //if not in redis
        $allowedSortColumns = ['created_at', 'priority'];
        $sortColumn = in_array($filters['sort'] ?? '', $allowedSortColumns)
            ? $filters['sort']
            : 'id';

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
        $sql .= " ORDER BY t.$sortColumn DESC";
        $sql .= " LIMIT ? OFFSET ?";

        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);

        foreach ($params as $index => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($index + 1, $value, $type);
        }

        $stmt->execute();


        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->redis->set($cacheKey, json_encode($rows), 600);

        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task($row);
        }

        return $tasks;
    }







    //---------------this is for view specific task page 
    public function findById(int $id, int $user_id): ?Task
    {

        $cacheKey = "task:id:$id:user:$user_id";

        $cached = $this->redis->get($cacheKey);

        if ($cached) {
            return new Task(json_decode($cached, true));
        }

        $stmt = $this->db->prepare("
            SELECT * FROM tasks
            WHERE id = ? AND user_id = ?
        ");

        $stmt->execute([$id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$row) return null;

        $this->redis->set($cacheKey, json_encode($row), 600);

        return new Task($row);
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
        $taskId = (int) $this->db->lastInsertId();
        $this->clearUserTaskCache($data['user_id']);
        return $taskId;
    }





    //-------------------update or edit quary for task 
    public function update(int $id, int $user_id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET title = ?, description = ?, status = ?, priority = ?
            WHERE id = ? AND user_id = ?
        ");

        $result = $stmt->execute([
            $data['title'],
            $data['description'],
            $data['status'],
            $data['priority'],
            $id,
            $user_id,
        ]);
        $this->clearSingleTaskCache($id, $user_id);
        $this->clearUserTaskCache($user_id);

        return $result;
    }




    //-------------------soft delete task  
    public function softDelete(int $id, int $user_id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET is_deleted = 1 
            WHERE id = ? AND user_id = ?

        ");

        $result = $stmt->execute([$id, $user_id]);
        $this->clearSingleTaskCache($id, $user_id);
        $this->clearUserTaskCache($user_id);
        return $result;
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
