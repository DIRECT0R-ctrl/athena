<?php

class Project {
    private $id;
    private $chef_projet_id;
    private $title;
    private $description;
    private $is_active;
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
        $this->chef_projet_id = $data['chef_projet_id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->is_active = isset($data['is_active']) ? (bool)$data['is_active'] : true;
        $this->created_at = $data['created_at'] ?? null;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getChefProjetId() { return $this->chef_projet_id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function isActive() { return $this->is_active; }
    public function getCreatedAt() { return $this->created_at; }
    
    // Setters
    public function setChefProjetId($chef_projet_id) {
        if (empty($chef_projet_id)) {
            throw new Exception("Chef de projet ID is required");
        }
        $this->chef_projet_id = $chef_projet_id;
        return $this;
    }
    
    public function setTitle($title) {
        if (strlen(trim($title)) < 3) {
            throw new Exception("Project title must be at least 3 characters");
        }
        $this->title = trim($title);
        return $this;
    }
    
    public function setDescription($description) {
        $this->description = trim($description);
        return $this;
    }
    
    public function setIsActive($is_active) {
        $this->is_active = (bool)$is_active;
        return $this;
    }
}
