<?php

class TaskRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $data = $this->db->fetch($sql, [':id' => $id]);
        
        if ($data) {
            return new Task($data);
        }
        return null;
    }
    
    public function findBySprint($sprint_id) {
        $sql = "SELECT * FROM tasks WHERE sprint_id = :sprint_id ORDER BY priority_id DESC, created_at DESC";
        $data = $this->db->fetchAll($sql, [':sprint_id' => $sprint_id]);
        
        $tasks = [];
        foreach ($data as $taskData) {
            $tasks[] = new Task($taskData);
        }
        return $tasks;
    }
    
    public function findByAssignee($user_id) {
        $sql = "SELECT * FROM tasks WHERE assigned_to = :user_id ORDER BY priority_id DESC, created_at DESC";
        $data = $this->db->fetchAll($sql, [':user_id' => $user_id]);
        
        $tasks = [];
        foreach ($data as $taskData) {
            $tasks[] = new Task($taskData);
        }
        return $tasks;
    }
    
    public function findByStatus($status_id, $sprint_id = null) {
        if ($sprint_id) {
            $sql = "SELECT * FROM tasks WHERE status_id = :status_id AND sprint_id = :sprint_id ORDER BY created_at DESC";
            $data = $this->db->fetchAll($sql, [':status_id' => $status_id, ':sprint_id' => $sprint_id]);
        } else {
            $sql = "SELECT * FROM tasks WHERE status_id = :status_id ORDER BY created_at DESC";
            $data = $this->db->fetchAll($sql, [':status_id' => $status_id]);
        }
        
        $tasks = [];
        foreach ($data as $taskData) {
            $tasks[] = new Task($taskData);
        }
        return $tasks;
    }
    
    public function create(Task $task) {
        $sql = "INSERT INTO tasks (sprint_id, creator_id, assigned_to, status_id, priority_id, title, description) 
                VALUES (:sprint_id, :creator_id, :assigned_to, :status_id, :priority_id, :title, :description) 
                RETURNING id, created_at";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':sprint_id' => $task->getSprintId(),
                ':creator_id' => $task->getCreatorId(),
                ':assigned_to' => $task->getAssignedTo(),
                ':status_id' => $task->getStatusId(),
                ':priority_id' => $task->getPriorityId(),
                ':title' => $task->getTitle(),
                ':description' => $task->getDescription()
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $taskData = [
                'id' => $result['id'],
                'sprint_id' => $task->getSprintId(),
                'creator_id' => $task->getCreatorId(),
                'assigned_to' => $task->getAssignedTo(),
                'status_id' => $task->getStatusId(),
                'priority_id' => $task->getPriorityId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'created_at' => $result['created_at']
            ];
            
            return new Task($taskData);
            
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'unique') !== false) {
                throw new Exception("Task title already exists in this sprint");
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function update(Task $task) {
        $sql = "UPDATE tasks SET 
                assigned_to = :assigned_to,
                status_id = :status_id,
                priority_id = :priority_id,
                title = :title,
                description = :description,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $task->getId(),
                ':assigned_to' => $task->getAssignedTo(),
                ':status_id' => $task->getStatusId(),
                ':priority_id' => $task->getPriorityId(),
                ':title' => $task->getTitle(),
                ':description' => $task->getDescription()
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM tasks WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function search($query, $sprint_id = null) {
        if ($sprint_id) {
            $sql = "SELECT * FROM tasks WHERE sprint_id = :sprint_id AND (title ILIKE :query OR description ILIKE :query) ORDER BY created_at DESC";
            $data = $this->db->fetchAll($sql, [':sprint_id' => $sprint_id, ':query' => '%' . $query . '%']);
        } else {
            $sql = "SELECT * FROM tasks WHERE title ILIKE :query OR description ILIKE :query ORDER BY created_at DESC";
            $data = $this->db->fetchAll($sql, [':query' => '%' . $query . '%']);
        }
        
        $tasks = [];
        foreach ($data as $taskData) {
            $tasks[] = new Task($taskData);
        }
        return $tasks;
    }
    
    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM tasks WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status_id'])) {
            $sql .= " AND status_id = :status_id";
            $params[':status_id'] = $filters['status_id'];
        }
        
        $result = $this->db->fetch($sql, $params);
        return (int) $result['count'];
    }
}
