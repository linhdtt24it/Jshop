<?php
require_once __DIR__ . '/../core/Model.php'; 

class User extends Model { // Kế thừa Model
  
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Giữ lại throw Exception hoặc xử lý lỗi
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
    // --- BỔ SUNG HÀM LẤY DANH SÁCH NHÂN VIÊN ---
    public function getAllStaff() {
        // Lấy admin và staff (hoặc employee nếu bạn chưa sửa DB)
        // Lưu ý: Nếu bạn chưa sửa DB thì đổi chữ 'staff' dưới này thành 'employee' nhé
        $sql = "SELECT * FROM users WHERE role IN ('admin', 'staff') ORDER BY created_at DESC";
        
        // Vì class của bạn kế thừa Model, chắc là biến $this->db có sẵn rồi
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // --- BỔ SUNG HÀM TẠO NHÂN VIÊN (Cho bài sau) ---
   public function createStaff($name, $email, $password, $role, $phone) { // <-- Thêm biến $phone
    if ($this->findByEmail($email)) return false;

    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    
    // Thêm phone_number vào câu SQL
    $sql = "INSERT INTO users (full_name, email, password, role, phone_number, is_verified, created_at) 
            VALUES (?, ?, ?, ?, ?, 1, NOW())";
    
    $stmt = $this->db->prepare($sql);
    // Thêm $phone vào danh sách execute
    return $stmt->execute([$name, $email, $hashPass, $role, $phone]);
}

    public function deleteUser($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }}
} // Kết thúc class


