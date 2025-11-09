<?php
// app/controllers/AuthController.php

// Đảm bảo load Controller base class
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller {
    public function login() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Only allow POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Method not allowed']);
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validate
        if (empty($email) || empty($password)) {
            $this->json(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin!']);
        }
        
        try {
            $userModel = new User();
            $user = $userModel->login($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user'] = $user;
                
                $cart_count = $userModel->getCartCount($user['user_id']);
                $_SESSION['cart_count'] = $cart_count;
                
                $this->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!',
                    'user_name' => $user['full_name'],
                    'cart_count' => $cart_count
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Email hoặc mật khẩu không đúng!']);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Lỗi hệ thống! Vui lòng thử lại.']);
        }
    }
    
    public function register() {
        // Only allow POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Method not allowed']);
        }
        
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
            $this->json(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
        }
        
        if ($password !== $confirm) {
            $this->json(['success' => false, 'message' => 'Mật khẩu xác nhận không khớp!']);
        }
        
        if (strlen($password) < 6) {
            $this->json(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự!']);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Email không hợp lệ!']);
        }
        
        try {
            $userModel = new User();
            $result = $userModel->register($name, $email, $password);
            
            if ($result) {
                $this->json([
                    'success' => true, 
                    'message' => 'Đăng ký thành công! Vui lòng đăng nhập.'
                ]);
            } else {
                $this->json([
                    'success' => false, 
                    'message' => 'Email đã được sử dụng!'
                ]);
            }
        } catch (Exception $e) {
            $this->json([
                'success' => false, 
                'message' => 'Lỗi hệ thống! Vui lòng thử lại.'
            ]);
        }
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
}
?>