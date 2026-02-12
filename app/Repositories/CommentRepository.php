<?php

namespace TaskFlow\Repositories;

use TaskFlow\Core\Database;
use TaskFlow\Models\Comment;
use PDO;

class CommentRepository
{

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    //-------------------view or get comments
    public function getCommentByTaskId($taskId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM comments
        WHERE task_id = ?
        ORDER BY created_at 
    ");
        $stmt->execute([$taskId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        return $stmt->execute([
            $data['task_id'],
            $data['comment']
        ]);
    }
}
