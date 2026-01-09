<?php

class Task {
    private $id;
    private $sprint_id;
    private $creator_id;
    private $assigned_to;
    private $status_id;
    private $priority_id;
    private $title;
    private $description;
    private $created_at;
    private $updated_at;
    
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
        $this->sprint_id = $data['sprint_id'] ?? null;
        $this->creator_id = $data['creator_id'] ?? null;
        $this->assigned_to = $data['assigned_to'] ?? null;
        $this->status_id = $data['status_id'] ?? 1; // Default: todo
        $this->priority_id = $data['priority_id'] ?? 2; // Default: medium
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getSprintId() { return $this->sprint_id; }
    public function getCreatorId() { return $this->creator_id; }
    public function getAssignedTo() { return $this->assigned_to; }
    public function getStatusId() { return $this->status_id; }
    public function getPriorityId() { return $this->priority_id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }
    
    // Setters
    public function setSprintId($sprint_id) {
        $this->sprint_id = $sprint_id;
        return $this;
    }
    
    public function setCreatorId($creator_id) {
        $this->creator_id = $creator_id;
        return $this;
    }
    
    public function setAssignedTo($user_id) {
        $this->assigned_to = $user_id;
        return $this;
    }
    
    public function setStatusId($status_id) {
        $this->status_id = $status_id;
        return $this;
    }
    
    public function setPriorityId($priority_id) {
        $this->priority_id = $priority_id;
        return $this;
    }
    
    public function setTitle($title) {
        if (strlen(trim($title)) < 3) {
            throw new Exception("Task title must be at least 3 characters");
        }
        $this->title = trim($title);
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = trim($description);
        return $this;
    }
}
