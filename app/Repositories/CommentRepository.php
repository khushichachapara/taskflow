<?php

namespace TaskFlow\Repositories;

use TaskFlow\Core\Database;
use TaskFlow\Models\Comment;
use TaskFlow\Core\RedisService;
use PDO;

class CommentRepository
{

    private PDO $db;
    private RedisService $redis;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->redis = new RedisService();
    }

    //-----------------------clear cache of while create comment get comments  
    private function clearTaskCacheAfterComment(int $taskId): void
    {

        $stmt = $this->db->prepare("SELECT user_id FROM tasks WHERE id = ?");
        $stmt->execute([$taskId]);
        $userId = $stmt->fetchColumn();

        if (!$userId) return;

        $this->redis->deleteByPattern("tasks:user:$userId:*");
        $this->redis->delete("task:id:$taskId:user:$userId");

        //clear comments cache for the task
        $this->redis->delete("comments:tasks:$taskId:user:$userId:all");
    }

    //-------------------view or get comments
    public function getCommentByTaskId($taskId , $user_id) 
    {

        $cacheKey = "comments:tasks:$taskId:user:$user_id:all";

        $cached = $this->redis->get($cacheKey);

        if ($cached !== null) {
            $rows = json_decode($cached, true);
            if (is_array($rows)) {
                return array_map(fn($row) => new Comment($row), $rows);
            }
        }

        $stmt = $this->db->prepare("
        SELECT * FROM comments
        WHERE task_id = ?
        ORDER BY created_at 
    ");
        $stmt->execute([$taskId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->redis->set($cacheKey, json_encode($rows), 600);


        $comments = [];
        foreach ($rows as $row) {
            $comments[] = new Comment($row);
        }
        return $comments;
    }



    //----------------------create new comments 
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO comments (task_id, comment)
            VALUES (?, ?)
        ");

        $result = $stmt->execute([
            $data['task_id'],
            $data['comment']
        ]);
        if ($result) {
            $this->clearTaskCacheAfterComment($data['task_id'] );
        }
        return $result;
    }
}
