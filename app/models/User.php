<?php
require_once __DIR__ . '/../core/Model.php'; 

class User extends Model {
  
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function getUserById($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Database error fetching user by ID: " . $e->getMessage());
        }
    }
    
    public function login($email, $password) {
        try {
            $user = $this->findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }
    
    public function register($name, $email, $password) {
        try {
            if ($this->findByEmail($email)) {
                return false;
            }
            
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password, created_at) VALUES (?, ?, ?, NOW())");
            
            return $stmt->execute([$name, $email, $hash]);
        } catch (Exception $e) {
            throw new Exception("Registration failed: " . $e->getMessage());
        }
    }
    
    public function getCartCount($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    public function getAllStaff() {
        $sql = "SELECT * FROM users WHERE role IN ('admin', 'staff') ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
   public function createStaff($name, $email, $password, $role, $phone) {
    if ($this->findByEmail($email)) return false;

    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (full_name, email, password, role, phone_number, is_verified, created_at) 
            VALUES (?, ?, ?, ?, ?, 1, NOW())";
    
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$name, $email, $hashPass, $role, $phone]);
}

    public function deleteUser($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function updatePassword($user_id, $new_password_hashed) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            return $stmt->execute([$new_password_hashed, $user_id]);
        } catch (Exception $e) {
            throw new Exception("Database error updating password: " . $e->getMessage());
        }
    }

    public function updateProfile($user_id, $data) {
        $fields = [];
        $params = [];

        $allowed_fields = ['full_name', 'email', 'phone_number', 'age', 'hometown', 'health_status'];

        foreach ($allowed_fields as $field) {
            if (isset($data[$field])) {
                $fields[] = "`{$field}` = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = ?";
        $params[] = $user_id;

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Database error updating profile: " . $e->getMessage());
            throw new Exception("Lỗi cơ sở dữ liệu khi cập nhật hồ sơ.");
        }
    }

}