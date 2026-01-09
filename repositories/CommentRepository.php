<?php

class CommentRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $data = $this->db->fetch($sql, [':id' => $id]);
        
        if ($data) {
            return new Comment($data);
        }
        return null;
    }
    
    public function findByTask($task_id) {
        $sql = "SELECT c.*, u.fullname, u.email FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.task_id = :task_id 
                ORDER BY c.created_at DESC";
        $data = $this->db->fetchAll($sql, [':task_id' => $task_id]);
        
        return $data;
    }
    
    public function create(Comment $comment) {
        $sql = "INSERT INTO comments (user_id, task_id, content) 
                VALUES (:user_id, :task_id, :content) 
                RETURNING id, created_at";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':user_id' => $comment->getUserId(),
                ':task_id' => $comment->getTaskId(),
                ':content' => $comment->getContent()
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $commentData = [
                'id' => $result['id'],
                'user_id' => $comment->getUserId(),
                'task_id' => $comment->getTaskId(),
                'content' => $comment->getContent(),
                'created_at' => $result['created_at']
            ];
            
            return new Comment($commentData);
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function countByTask($task_id) {
        $sql = "SELECT COUNT(*) as count FROM comments WHERE task_id = :task_id";
        $result = $this->db->fetch($sql, [':task_id' => $task_id]);
        return $result['count'] ?? 0;
    }
}
