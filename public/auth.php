<?php
// public/auth.php  ← File xử lý AJAX đăng ký, login, OTP...
session_start();
require_once "../app/controllers/AuthController.php";

header('Content-Type: application/json');

$auth = new AuthController();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        $auth->register();
        break;
    case 'verify_otp':
        $auth->verifyOtp();
        break;
    case 'login':
        $auth->login();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'resend_otp':
        $auth->resendOtp();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
}