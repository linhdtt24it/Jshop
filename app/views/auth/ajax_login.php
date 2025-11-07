<?php
// public/ajax_login.php
require_once "../config/constants.php";

session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ!']);
    exit;
}

$db = (new Database())->connect();
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;

    // TÍNH LẠI SỐ LƯỢNG GIỎ HÀNG
    $stmt = $db->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$user['user_id']]);
    $total = $stmt->fetch()['total'] ?? 0;
    $_SESSION['cart_count'] = $total;

    echo json_encode([
        'success' => true,
        'message' => 'Đăng nhập thành công!',
        'cart_count' => $total
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Email hoặc mật khẩu sai!']);
}