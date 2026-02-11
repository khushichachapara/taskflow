<?php

namespace TaskFlow\Repositories;

use TaskFlow\Core\Database;
use PDO;

class ActivityRepository
{

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    //get activity log
    public function create(array $data)
    {
        $stmt = $this->db->prepare("
        INSERT INTO activity_log (task_id, event_type, event_message)
        VALUES (?, ?, ?)
    ");

        return $stmt->execute([
            $data['task_id'],
            $data['event_type'],
            $data['event_message']
        ]);
    }


    //get log
    public function getLogByTaskId($taskId)
    {
        $stmt = $this->db->prepare("
        SELECT id, task_id, event_type, event_message, created_at
        FROM activity_log
        WHERE task_id = ?
        ORDER BY created_at 
    ");
        $stmt->execute([$taskId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
