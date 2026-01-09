<?php

class User {
    private $id;
    private $role_id;
    private $fullname;
    private $email;
    private $password;
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
        $this->role_id = $data['role_id'] ?? ROLE_MEMBRE; 
        $this->fullname = $data['fullname'] ?? '';
        $this->email = $data['email'] ?? '';

	// shof wash password already hased from db-
	if (isset($data['password']))  {$this->setPassword($data['password'], true);}

        $this->is_active = isset($data['is_active']) ? (bool)$data['is_active'] : true;
        $this->created_at = $data['created_at'] ?? null;
    }
    
   
    public function getId() { return $this->id; }
    public function getRoleId() { return $this->role_id; }
    public function getFullname() { return $this->fullname; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getIsActive() { return $this->is_active; }
    public function getCreatedAt() { return $this->created_at; }
    public function setFullname($fullname) {
        if (strlen(trim($fullname)) < 2) {
            throw new Exception("Full name must be at least 2 characters");
        }
        $this->fullname = trim($fullname);
        return $this;
    }
    
    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        $this->email = trim($email);
        return $this;
    }
    
    public function setPassword($password) {
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }
    
    public function setRoleId($role_id) {
        $valid_roles = [ROLE_ADMIN, ROLE_CHEF_PROJET, ROLE_MEMBRE];
        if (!in_array($role_id, $valid_roles)) {
            throw new Exception("Invalid role ID");
        }
        $this->role_id = $role_id;
        return $this;
    }
    
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
    
    public function save() {
        if ($this->id) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
    
    private function create() {
        $sql = "INSERT INTO users (role_id, fullname, email, password, is_active) 
                VALUES (:role_id, :fullname, :email, :password, :is_active) 
                RETURNING id, created_at";
        
        try {
            $stmt = self::$db->getConnection()->prepare($sql);
            $stmt->execute([
                ':role_id' => $this->role_id,
                ':fullname' => $this->fullname,
                ':email' => $this->email,
                ':password' => $this->password,
                ':is_active' => $this->is_active ? 1 : 0
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $result['id'];
            $this->created_at = $result['created_at'];
            
            return $this;
            
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'unique') !== false) {
                throw new Exception("Email already exists");
            }
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    private function update() {
        $sql = "UPDATE users SET 
                role_id = :role_id,
                fullname = :fullname,
                email = :email,
                password = :password,
                is_active = :is_active,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        try {
            $stmt = self::$db->getConnection()->prepare($sql);
            $stmt->execute([
                ':id' => $this->id,
                ':role_id' => $this->role_id,
                ':fullname' => $this->fullname,
                ':email' => $this->email,
                ':is_active' => $this->is_active ? 1 : 0
            ]);
            
            return $this;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function delete() {
        if (!$this->id) {
            throw new Exception("Cannot delete unsaved user");
        }
        
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = self::$db->getConnection()->prepare($sql);
        return $stmt->execute([':id' => $this->id]);
    }
    
    public static function find($id) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE id = :id";
        $data = $db->fetch($sql, [':id' => $id]);
        
        if ($data) {
            return new self($data);
        }
        return null;
    }
    
    public static function findByEmail($email) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE email = :email";
        $data = $db->fetch($sql, [':email' => $email]);
        
        if ($data) {
            return new self($data);
        }
        return null;
    }
    
    public static function all() {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $data = $db->fetchAll($sql);
        
        $users = [];
        foreach ($data as $userData) {
            $users[] = new self($userData);
        }
        return $users;
    }
    
    public function isAdmin() {
        return $this->role_id == ROLE_ADMIN;
    }
    
    public function isChefProjet() {
        return $this->role_id == ROLE_CHEF_PROJET;
    }
    
    public function isMembre() {
        return $this->role_id == ROLE_MEMBRE;
    }
    
    public function isActive() {
        return $this->is_active == true;
    }
    

    public function toArray() {
        return [
            'id' => $this->id,
            'role_id' => $this->role_id,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at
        ];
    }
    
    /**
     * haad l method it's obviously for for debugging
     */
    public function __toString() {
        return "User #{$this->id}: {$this->fullname} ({$this->email})";
    }
}
