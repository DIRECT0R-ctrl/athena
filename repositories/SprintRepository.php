<?php

class SprintRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM sprints WHERE id = :id";
        $data = $this->db->fetch($sql, [':id' => $id]);
        
        if ($data) {
            return new Sprint($data);
        }
        return null;
    }
    
    public function findByProject($project_id) {
        $sql = "SELECT * FROM sprints WHERE project_id = :project_id ORDER BY start_date DESC";
        $data = $this->db->fetchAll($sql, [':project_id' => $project_id]);
        
        $sprints = [];
        foreach ($data as $sprintData) {
            $sprints[] = new Sprint($sprintData);
        }
        return $sprints;
    }
    
    public function findActive($project_id) {
        $sql = "SELECT * FROM sprints 
                WHERE project_id = :project_id 
                AND start_date <= CURRENT_DATE 
                AND end_date >= CURRENT_DATE 
                ORDER BY start_date DESC";
        $data = $this->db->fetchAll($sql, [':project_id' => $project_id]);
        
        $sprints = [];
        foreach ($data as $sprintData) {
            $sprints[] = new Sprint($sprintData);
        }
        return $sprints;
    }
    
    public function create(Sprint $sprint) {
        $sql = "INSERT INTO sprints (project_id, title, start_date, end_date) 
                VALUES (:project_id, :title, :start_date, :end_date) 
                RETURNING id, created_at";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':project_id' => $sprint->getProjectId(),
                ':title' => $sprint->getTitle(),
                ':start_date' => $sprint->getStartDate(),
                ':end_date' => $sprint->getEndDate()
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $sprintData = [
                'id' => $result['id'],
                'project_id' => $sprint->getProjectId(),
                'title' => $sprint->getTitle(),
                'start_date' => $sprint->getStartDate(),
                'end_date' => $sprint->getEndDate(),
                'created_at' => $result['created_at']
            ];
            
            return new Sprint($sprintData);
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function update(Sprint $sprint) {
        $sql = "UPDATE sprints SET 
                title = :title,
                start_date = :start_date,
                end_date = :end_date
                WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $sprint->getId(),
                ':title' => $sprint->getTitle(),
                ':start_date' => $sprint->getStartDate(),
                ':end_date' => $sprint->getEndDate()
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM sprints WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM sprints WHERE 1=1";
        $params = [];
        
        $result = $this->db->fetch($sql, $params);
        return (int) $result['count'];
    }
}
