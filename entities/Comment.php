<?php

class Comment {
    private $id;
    private $user_id;
    private $task_id;
    private $content;
    private $created_at;
    
    private static $db;
    
    public function __construct($data = []) {
        if (self::$db === null) {
            self::$db = Database::getInstance();
        }
        
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }
    
    private function hydrate($data) {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->task_id = $data['task_id'] ?? null;
        $this->content = $data['content'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getTaskId() { return $this->task_id; }
    public function getContent() { return $this->content; }
    public function getCreatedAt() { return $this->created_at; }
    
    // Setters
    public function setUserId($user_id) {
        $this->user_id = $user_id;
        return $this;
    }
    
    public function setTaskId($task_id) {
        $this->task_id = $task_id;
        return $this;
    }
    
    public function setContent($content) {
        if (strlen(trim($content)) < 1) {
            throw new Exception("Comment cannot be empty");
        }
        $this->content = trim($content);
        return $this;
    }
}
