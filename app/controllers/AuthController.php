<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

ini_set('session.gc_maxlifetime', 7200);
ini_set('session.cookie_lifetime', 7200);
session_start();

header('Content-Type: application/json');

error_log("=== AUTH CONTROLLER ===");
$action = $_GET['action'] ?? $_POST['action'] ?? '';

error_log("Action: " . ($action ?: 'none'));
if($action == 'register') {
    try {
        $full_name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone_number = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        error_log("Register: $email - Name: $full_name");
        
        if(empty($full_name) || empty($email) || empty($phone_number) || empty($password) || $password !== $confirm) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin và mật khẩu khớp nhau']);
            exit;
        }
        
        if(strlen($password) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu ít nhất 6 ký tự']);
            exit;
        }
        
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND is_verified = 1");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email đã được đăng ký']);
            exit;
        }
        
        $stmt = $pdo->prepare("DELETE FROM users WHERE email = ? AND is_verified = 0");
        $stmt->execute([$email]);
        
        $otp = strval(rand(100000, 999999));
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $otp_expiry = date('Y-m-d H:i:s', time() + 900);
        
        $stmt = $pdo->prepare("INSERT INTO users 
            (email, phone_number, password, role, is_verified, otp, otp_expiry, full_name, created_at) 
            VALUES (?, ?, ?, 'customer', 0, ?, ?, ?, NOW())");
        
        $stmt->execute([$email, $phone_number, $password_hash, $otp, $otp_expiry, $full_name]);
        
        $user_id = $pdo->lastInsertId();
        
        $_SESSION['pending_user_id'] = $user_id;
        $_SESSION['pending_email'] = $email;
        $_SESSION['otp_attempts'] = 1;
        $_SESSION['register_time'] = time();
        
        require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
        
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'thithuylinhdinh003@gmail.com';
        $mail->Password = 'woxmcvkbzsevhdjp';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('thithuylinhdinh003@gmail.com', 'JSHOP');
        $mail->addAddress($email, $full_name);
        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP đăng ký JSHOP';
        $mail->Body = "Mã OTP của bạn là: <b>$otp</b>. Mã có hiệu lực trong 15 phút.";
        
        if($mail->send()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Mã OTP đã được gửi đến email của bạn!',
                'email' => $email,
                'requires_otp' => true
            ]);
        } else {
            $pdo->query("DELETE FROM users WHERE user_id = $user_id");
            echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email OTP.']);
        }
        
    } catch(Exception $e) {
        error_log("Register error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

if($action == 'resendOTP') {
    echo json_encode(['status' => 'error', 'message' => 'Chức năng đang cập nhật']);
    exit;
}

if($action == 'verifyOTP') {
    try {
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';
        
        if(empty($email) || empty($otp)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập OTP']);
            exit;
        }
        
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND otp = ? AND is_verified = 0 AND otp_expiry > NOW()");
        $stmt->execute([$email, $otp]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Mã OTP không đúng hoặc đã hết hạn']);
            exit;
        }
        
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, otp = NULL, otp_expiry = NULL WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'customer';
        
        unset($_SESSION['pending_user_id']);
        unset($_SESSION['pending_email']);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Đăng ký thành công!',
            'redirect' => '/Jshop/public/'
        ]);
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

if($action == 'login') {
    try {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        error_log("Login attempt: $email");
        
        if(empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập email và mật khẩu']);
            exit;
        }
        
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_verified = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$user || !password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Email hoặc mật khẩu không đúng']);
            exit;
        }
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $email;
        
        $role_raw = $user['role'] ?? 'customer';
        $_SESSION['user_role'] = strtolower(trim($role_raw));
        
        error_log("✅ Login OK: $email - Role: [" . $_SESSION['user_role'] . "]");
        
        $redirectUrl = '/Jshop/public/';

        if ($_SESSION['user_role'] === 'admin') {
            $redirectUrl = '/Jshop/app/controllers/AdminController.php?action=dashboard';
        } 
        elseif ($_SESSION['user_role'] === 'staff') {
            $redirectUrl = '/Jshop/app/controllers/StaffController.php?action=dashboard';
        }
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Đăng nhập thành công',
            'redirect' => $redirectUrl
        ]);
        
    } catch(Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi đăng nhập hệ thống']);
    }
    exit;
}

if($action == 'cancelRegistration') {
    if(isset($_POST['email'])) {
        unset($_SESSION['pending_email']);
    }
    echo json_encode(['status' => 'success', 'message' => 'Đã hủy']);
    exit;
}

if($action == 'logout') {
    session_unset();
    session_destroy();
    header("Location: /Jshop/public/");
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);
?>