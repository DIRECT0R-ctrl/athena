<?php

class Sprint {
    private $id;
    private $project_id;
    private $title;
    private $start_date;
    private $end_date;
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
        $this->project_id = $data['project_id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->start_date = $data['start_date'] ?? null;
        $this->end_date = $data['end_date'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getProjectId() { return $this->project_id; }
    public function getTitle() { return $this->title; }
    public function getStartDate() { return $this->start_date; }
    public function getEndDate() { return $this->end_date; }
    public function getCreatedAt() { return $this->created_at; }
    
    // Setters
    public function setProjectId($project_id) {
        $this->project_id = $project_id;
        return $this;
    }
    
    public function setTitle($title) {
        if (strlen(trim($title)) < 3) {
            throw new Exception("Sprint title must be at least 3 characters");
        }
        $this->title = trim($title);
        return $this;
    }
    
    public function setStartDate($date) {
        if (!$this->isValidDate($date)) {
            throw new Exception("Invalid start date format");
        }
        $this->start_date = $date;
        return $this;
    }
    
    public function setEndDate($date) {
        if (!$this->isValidDate($date)) {
            throw new Exception("Invalid end date format");
        }
        $this->end_date = $date;
        return $this;
    }
    
    private function isValidDate($date) {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    
    public function isDateRangeValid() {
        return strtotime($this->start_date) < strtotime($this->end_date);
    }
}
