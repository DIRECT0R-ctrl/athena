<?php

class UserRepository {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $data = $this->db->fetch($sql, [':id' => $id]);
        
        if ($data) {
            return new User($data);
        }
        return null;
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $data = $this->db->fetch($sql, [':email' => $email]);
        
        if ($data) {
            return new User($data);
        }
        return null;
    }
    
    public function findAll($filters = []) {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];
        
        if (!empty($filters['role_id'])) {
            $sql .= " AND role_id = :role_id";
            $params[':role_id'] = $filters['role_id'];
        }
        
        if (!empty($filters['is_active'])) {
            $sql .= " AND is_active = :is_active";
            $params[':is_active'] = $filters['is_active'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (fullname ILIKE :search OR email ILIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $data = $this->db->fetchAll($sql, $params);
        
        $users = [];
        foreach ($data as $userData) {
            $users[] = new User($userData);
        }
        return $users;
    }
    
    public function create(User $user) {
        $sql = "INSERT INTO users (role_id, fullname, email, password, is_active) 
                VALUES (:role_id, :fullname, :email, :password, :is_active) 
                RETURNING id, created_at";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':role_id' => $user->getRoleId(),
                ':fullname' => $user->getFullname(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(),
                ':is_active' => $user->getIsActive() ? 1 : 0
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
           
            $userData = [
                'id' => $result['id'],
                'role_id' => $user->getRoleId(),
                'fullname' => $user->getFullname(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'is_active' => $user->getIsActive(),
                'created_at' => $result['created_at']
            ];
            
            return new User($userData);
            
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'unique') !== false) {
                throw new Exception("Email already exists");
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    } // We can't directly set private properties, so we return new user
    

    public function update(User $user) {
        $sql = "UPDATE users SET 
                role_id = :role_id,
                fullname = :fullname,
                email = :email,
                password = :password,
                is_active = :is_active,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $user->getId(),
                ':role_id' => $user->getRoleId(),
                ':fullname' => $user->getFullname(),
                ':email' => $user->getEmail(),
                ':password' => $user->getPassword(),
                ':is_active' => $user->getIsActive() ? 1 : 0
            ]);
            
            return true;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute(array(':id' => $id));
    }
    
    public function count($filters = []) {
        $sql = "SELECT COUNT(*) FROM users WHERE 1=1";
        $params = [];
        
        if (!empty($filters['role_id'])) {
            $sql .= " AND role_id = :role_id";
            $params[':role_id'] = $filters['role_id'];
        }
        
        if (!empty($filters['is_active'])) {
            $sql .= " AND is_active = :is_active";
            $params[':is_active'] = $filters['is_active'];
        }
        
        $result = $this->db->fetch($sql, $params);
        return (int) $result['count'];
    }
}