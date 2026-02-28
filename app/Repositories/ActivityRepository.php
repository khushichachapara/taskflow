<?php

namespace TaskFlow\Repositories;

use TaskFlow\Core\Database;
use TaskFlow\Core\ApcuCacheService;
use PDO;

class ActivityRepository
{

    private PDO $db;
    private ApcuCacheService $cache;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->cache = new ApcuCacheService();
    }



    //----------------create activity log
    public function create(array $data)
    {
        $stmt = $this->db->prepare("
        INSERT INTO activity_log (task_id, event_type, event_message)
        VALUES (?, ?, ?)
    ");

        $result = $stmt->execute([
            $data['task_id'],
            $data['event_type'],
            $data['event_message']
        ]);
        $this->cache->delete("activity_logs_" . $data['task_id']);
        return $result;
    }



    //--------------------get log
    public function getLogByTaskId($taskId)
    {
        
        $cacheKey = "activity_logs_" . $taskId;

        $logs = $this->cache->get($cacheKey);
    
        if ($logs !== false) {
            return $logs;
        }

        $stmt = $this->db->prepare("
        SELECT id, task_id, event_type, event_message, created_at
        FROM activity_log
        WHERE task_id = ?
        ORDER BY created_at 
    ");
        $stmt->execute([$taskId]);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->cache->set($cacheKey, $logs, 600);
        return $logs;
    }
}
