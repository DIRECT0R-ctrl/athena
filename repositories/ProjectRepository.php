<?php

class ProjectRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM projects WHERE id = :id";
        $data = $this->db->fetch($sql, [':id' => $id]);
        
        if ($data) {
            return new Project($data);
        }
        return null;
    }
    
    public function findAll($filters = []) {
        $sql = "SELECT * FROM projects WHERE 1=1";
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (title ILIKE :search OR description ILIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (isset($filters['status']) && $filters['status'] !== '') {
            $sql .= " AND is_active = :status";
            $params[':status'] = $filters['status'] === 'active' ? true : false;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $data = $this->db->fetchAll($sql, $params);
        
        $projects = [];
        foreach ($data as $projectData) {
            $projects[] = new Project($projectData);
        }
        return $projects;
    }
    
    public function findByChef($chef_id) {
        $sql = "SELECT * FROM projects WHERE chef_projet_id = :chef_id ORDER BY created_at DESC";
        $data = $this->db->fetchAll($sql, [':chef_id' => $chef_id]);
        
        $projects = [];
        foreach ($data as $projectData) {
            $projects[] = new Project($projectData);
        }
        return $projects;
    }
    
    public function findByMember($user_id) {
        $sql = "SELECT p.* FROM projects p 
                INNER JOIN project_members pm ON p.id = pm.project_id 
                WHERE pm.user_id = :user_id AND p.is_active = true
                ORDER BY p.created_at DESC";
        $data = $this->db->fetchAll($sql, [':user_id' => $user_id]);
        
        $projects = [];
        foreach ($data as $projectData) {
            $projects[] = new Project($projectData);
        }
        return $projects;
    }
    
    public function create(Project $project) {
        $sql = "INSERT INTO projects (chef_projet_id, title, description, is_active) 
                VALUES (:chef_id, :title, :description, :is_active) 
                RETURNING id, created_at";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':chef_id' => $project->getChefProjetId(),
                ':title' => $project->getTitle(),
                ':description' => $project->getDescription(),
                ':is_active' => $project->isActive() ? 1 : 0
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $projectData = [
                'id' => $result['id'],
                'chef_projet_id' => $project->getChefProjetId(),
                'title' => $project->getTitle(),
                'description' => $project->getDescription(),
                'is_active' => $project->isActive(),
                'created_at' => $result['created_at']
            ];
            
            return new Project($projectData);
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function update(Project $project) {
        $sql = "UPDATE projects SET 
                title = :title,
                description = :description,
                is_active = :is_active
                WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $project->getId(),
                ':title' => $project->getTitle(),
                ':description' => $project->getDescription(),
                ':is_active' => $project->isActive() ? 1 : 0
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM projects WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function addMember($project_id, $user_id) {
        $sql = "INSERT INTO project_members (project_id, user_id) 
                VALUES (:project_id, :user_id) 
                ON CONFLICT DO NOTHING";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':project_id' => $project_id,
                ':user_id' => $user_id
            ]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function getMembers($project_id) {
        $sql = "SELECT u.* FROM users u 
                INNER JOIN project_members pm ON u.id = pm.user_id 
                WHERE pm.project_id = :project_id";
        $data = $this->db->fetchAll($sql, [':project_id' => $project_id]);
        
        $members = [];
        foreach ($data as $userData) {
            $members[] = new User($userData);
        }
        return $members;
    }
    
    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM projects WHERE 1=1";
        $params = [];
        
        if (!empty($filters['is_active'])) {
            $sql .= " AND is_active = :is_active";
            $params[':is_active'] = $filters['is_active'];
        }
        
        $result = $this->db->fetch($sql, $params);
        return (int) $result['count'];
    }
}
