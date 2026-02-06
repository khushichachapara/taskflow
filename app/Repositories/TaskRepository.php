<?php

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

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM tasks
            WHERE is_deleted = 0
            ORDER BY id DESC
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tasks = [];
        foreach ($rows as $row) {
            $tasks[] = new Task($row);
        }
        return $tasks;
    }

    public function findById(int $id): ?Task
    {
        $stmt = $this->db->prepare("
            SELECT * FROM tasks
            WHERE id = ? AND is_deleted = 0
        ");

        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Task($row) : null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO tasks (title, description, status, priority)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['status'],
            $data['priority']
        ]);
    }

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

    public function softDelete(int $id): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tasks
            SET is_deleted = 1 
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
    public function restore(int $id): bool
    {
        $stmt = $this->db->prepare("
        UPDATE tasks
        SET is_deleted = 0
        WHERE id = ?
    ");

        return $stmt->execute([$id]);
    }


    public function getTasksWithCommentCount(): array
    {
        $stmt = $this->db->query("
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
        GROUP BY t.id
        ORDER BY t.id DESC
    ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
