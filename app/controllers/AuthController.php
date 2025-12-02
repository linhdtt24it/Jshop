<?php
// C:\Users\LENOVO\Jshop\app\controllers\AuthController.php
session_start();
header('Content-Type: application/json');

// ========== REGISTER (CHỈ LƯU TẠM) ==========
if(($_GET['action'] ?? '') == 'register') {
    try {
        $full_name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone_number = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        if(empty($full_name) || empty($email) || empty($password) || $password !== $confirm) {
            echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
            exit;
        }
        
        // Kiểm tra email đã verify chưa
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND is_verified = 1");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email đã được đăng ký']);
            exit;
        }
        
        // Tạo OTP
        $otp = rand(100000, 999999);
        
        // LƯU TẠM VÀO SESSION (chưa vào DB)
        $_SESSION['pending_user'] = [
            'full_name' => $full_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'otp' => $otp,
            'otp_expiry' => date('Y-m-d H:i:s', strtotime('+15 minutes'))
        ];
        
        // Gửi email OTP
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
        
        $mail->setFrom('thithuylinhdinh003@gmail.com', 'JSHOP');
        $mail->addAddress($email, $full_name);
        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP JSHOP';
        $mail->Body = "Xin chào $full_name,<br>Mã OTP của bạn là: <b style='font-size:24px;color:red;'>$otp</b><br>Có hiệu lực trong 15 phút";
        
        if($mail->send()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Vui lòng nhập mã OTP đã gửi đến email',
                'email' => $email
            ]);
        } else {
            echo json_encode([
                'status' => 'warning',
                'message' => 'Không gửi được email. OTP: ' . $otp,
                'otp' => $otp,
                'email' => $email
            ]);
        }
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

// ========== VERIFY OTP (LƯU VÀO DB SAU KHI VERIFY) ==========
if(($_GET['action'] ?? '') == 'verifyOTP') {
    try {
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';
        
        if(empty($email) || empty($otp)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập email và OTP']);
            exit;
        }
        
        // Kiểm tra session pending user
        if(!isset($_SESSION['pending_user']) || $_SESSION['pending_user']['email'] !== $email) {
            echo json_encode(['status' => 'error', 'message' => 'Session hết hạn. Vui lòng đăng ký lại']);
            exit;
        }
        
        $pending_user = $_SESSION['pending_user'];
        
        // Kiểm tra OTP
        if($pending_user['otp'] != $otp) {
            echo json_encode(['status' => 'error', 'message' => 'OTP không đúng']);
            exit;
        }
        
        // Kiểm tra OTP hết hạn
        if(strtotime($pending_user['otp_expiry']) < time()) {
            echo json_encode(['status' => 'error', 'message' => 'OTP đã hết hạn']);
            exit;
        }
        
        // LƯU VÀO DATABASE SAU KHI VERIFY
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Xóa user cũ nếu tồn tại (chưa verify)
        $stmt = $pdo->prepare("DELETE FROM users WHERE email = ? AND is_verified = 0");
        $stmt->execute([$email]);
        
        // Lưu user mới với is_verified = 1
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone_number, password, is_verified, role, created_at) 
                              VALUES (?, ?, ?, ?, 1, 'customer', NOW())");
        $stmt->execute([
            $pending_user['full_name'],
            $pending_user['email'],
            $pending_user['phone_number'],
            $pending_user['password']
        ]);
        
        $user_id = $pdo->lastInsertId();
        
        // Đăng nhập tự động
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $pending_user['full_name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'customer';
        
        // Xóa session pending
        unset($_SESSION['pending_user']);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Xác thực thành công! Đang chuyển hướng...',
            'redirect' => '/Jshop/'
        ]);
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

// ========== RESEND OTP ==========
if(($_GET['action'] ?? '') == 'resendOTP') {
    try {
        $email = $_GET['email'] ?? '';
        
        if(empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
            exit;
        }
        
        // Kiểm tra session pending
        if(!isset($_SESSION['pending_user']) || $_SESSION['pending_user']['email'] !== $email) {
            echo json_encode(['status' => 'error', 'message' => 'Session hết hạn']);
            exit;
        }
        
        // Tạo OTP mới
        $otp = rand(100000, 999999);
        $_SESSION['pending_user']['otp'] = $otp;
        $_SESSION['pending_user']['otp_expiry'] = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        
        // Gửi lại email
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
        
        $mail->setFrom('thithuylinhdinh003@gmail.com', 'JSHOP');
        $mail->addAddress($email, $_SESSION['pending_user']['full_name']);
        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP JSHOP (Gửi lại)';
        $mail->Body = "Mã OTP mới của bạn là: <b style='font-size:24px;color:red;'>$otp</b>";
        
        if($mail->send()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Đã gửi lại mã OTP!'
            ]);
        } else {
            echo json_encode([
                'status' => 'warning',
                'message' => 'Không gửi được email. OTP: ' . $otp,
                'otp' => $otp
            ]);
        }
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

// ========== LOGIN ==========
if(($_GET['action'] ?? '') == 'login') {
    try {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_verified = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$user || !password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Sai email hoặc mật khẩu']);
            exit;
        }
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['role'] ?? 'customer';
        
        echo json_encode(['status' => 'success', 'message' => 'Đăng nhập thành công']);
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi đăng nhập: ' . $e->getMessage()]);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);