<?php
// public/ajax_register.php
require_once "../config/constants.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

if (!$name || !$email || !$password || !$confirm) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ!']);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu không khớp!']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Mật khẩu phải từ 6 ký tự!']);
    exit;
}

$db = (new Database())->connect();
$stmt = $db->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email đã được sử dụng!']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $hash]);

echo json_encode(['success' => true, 'message' => 'Đăng ký thành công! Vui lòng đăng nhập.']);