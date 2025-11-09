<?php
// app/models/User.php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->connect();
    }
    
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Database error: " . $e->getMessage());
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
            // Check if email exists
            if ($this->findByEmail($email)) {
                return false;
            }
            
            // Insert new user
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
}
?>