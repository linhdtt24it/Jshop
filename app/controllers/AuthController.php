<?php
// C:\Users\LENOVO\Jshop\app\controllers\AuthController.php

ini_set('session.gc_maxlifetime', 7200);
ini_set('session.cookie_lifetime', 7200);

session_start();

// KHÔNG CẦN THEO DÕI OTP ATTEMPTS Ở ĐÂY NỮA
header('Content-Type: application/json');

// ========== REGISTER ==========
if(($_GET['action'] ?? '') == 'register') {
    try {
        $full_name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone_number = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        // Validate
        if(empty($full_name) || empty($email) || empty($password) || $password !== $confirm) {
            echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
            exit;
        }
        
        // Kiểm tra email đã đăng ký chưa
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND is_verified = 1");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email đã được đăng ký']);
            exit;
        }
        
        // Tạo OTP DUY NHẤT
        $otp = strval(rand(100000, 999999));
        
        // LƯU VÀO SESSION - OTP NÀY CHỈ ĐƯỢC GỬI 1 LẦN
        $_SESSION['pending_user'] = [
            'full_name' => $full_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'otp' => $otp, // OTP DUY NHẤT - KHÔNG THAY ĐỔI
            'otp_expiry' => time() + 3600, // 1 giờ
            'created_at' => time(),
            'otp_sent' => 1 // ĐÃ GỬI 1 LẦN
        ];
        
        // CẬP NHẬT SESSION EXPIRY
        $_SESSION['session_expiry'] = time() + 7200;
        
        error_log("Đã gửi OTP lần đầu cho: $email - OTP: $otp");
        
        // GỬI EMAIL OTP LẦN ĐẦU TIÊN
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
        $mail->Subject = 'Mã OTP JSHOP - Lần gửi đầu tiên';
        $mail->Body = "
            <h3>Xin chào $full_name,</h3>
            <p>Mã OTP của bạn là: <strong style='font-size:24px;color:red;'>$otp</strong></p>
            <p><strong>Thời gian hiệu lực: 1 giờ</strong></p>
            <p>Vui lòng không chia sẻ mã OTP với bất kỳ ai.</p>
            <hr>
            <p><small>Đây là lần gửi OTP đầu tiên. Bạn có thể yêu cầu gửi lại tối đa 3 lần.</small></p>
        ";
        
        if($mail->send()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Mã OTP đã được gửi đến email của bạn',
                'email' => $email
            ]);
        } else {
            // XÓA SESSION NẾU GỬI EMAIL THẤT BẠI
            unset($_SESSION['pending_user']);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
            ]);
        }
        
    } catch(Exception $e) {
        error_log("Register error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống']);
    }
    exit;
}

// ========== RESEND OTP ==========
if(($_GET['action'] ?? '') == 'resendOTP') {
    try {
        $email = $_POST['email'] ?? $_GET['email'] ?? '';
        
        if(empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
            exit;
        }
        
        // KIỂM TRA SESSION PENDING USER
        if(!isset($_SESSION['pending_user']) || ($_SESSION['pending_user']['email'] ?? '') !== $email) {
            echo json_encode(['status' => 'error', 'message' => 'Phiên làm việc hết hạn. Vui lòng đăng ký lại']);
            exit;
        }
        
        // KIỂM TRA SỐ LẦN ĐÃ GỬI OTP
        $otpSentCount = $_SESSION['pending_user']['otp_sent'] ?? 1;
        
        // GIỚI HẠN 3 LẦN GỬI LẠI
        if ($otpSentCount >= 4) { // 1 lần đầu + 3 lần gửi lại
            echo json_encode([
                'status' => 'error', 
                'message' => 'Bạn đã gửi OTP quá số lần cho phép. Vui lòng đăng ký lại.'
            ]);
            exit;
        }
        
        // SỬ DỤNG OTP HIỆN TẠI - KHÔNG TẠO OTP MỚI
        $currentOtp = $_SESSION['pending_user']['otp'];
        
        // CẬP NHẬT SỐ LẦN ĐÃ GỬI
        $_SESSION['pending_user']['otp_sent'] = $otpSentCount + 1;
        $_SESSION['pending_user']['otp_expiry'] = time() + 3600; // Reset thời gian 1 giờ
        
        error_log("Gửi lại OTP lần $otpSentCount cho: $email - OTP: $currentOtp");
        
        // GỬI LẠI EMAIL VỚI OTP HIỆN TẠI
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
        $mail->Subject = 'Mã OTP JSHOP (Gửi lại lần ' . $otpSentCount . ')';
        $mail->Body = "
            <h3>Gửi lại mã OTP</h3>
            <p>Mã OTP của bạn vẫn là: <strong style='font-size:24px;color:red;'>$currentOtp</strong></p>
            <p><strong>Thời gian hiệu lực: 1 giờ</strong></p>
            <p><small>Lần gửi: $otpSentCount/4</small></p>
            <hr>
            <p><small>Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.</small></p>
        ";
        
        if($mail->send()) {
            $attemptsLeft = 4 - ($otpSentCount + 1);
            echo json_encode([
                'status' => 'success',
                'message' => 'Đã gửi lại mã OTP!',
                'attempts_left' => $attemptsLeft,
                'current_attempt' => $otpSentCount
            ]);
        } else {
            // KHÔI PHỤC SỐ LẦN GỬI NẾU THẤT BẠI
            $_SESSION['pending_user']['otp_sent'] = $otpSentCount;
            echo json_encode([
                'status' => 'error',
                'message' => 'Không thể gửi lại email. Vui lòng thử lại sau.'
            ]);
        }
        
    } catch(Exception $e) {
        error_log("Resend OTP Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi gửi lại OTP']);
    }
    exit;
}

// ========== VERIFY OTP ==========
if(($_GET['action'] ?? '') == 'verifyOTP') {
    try {
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';
        
        if(empty($email) || empty($otp)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập email và OTP']);
            exit;
        }
        
        // Kiểm tra session
        if(!isset($_SESSION['pending_user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Phiên làm việc hết hạn. Vui lòng đăng ký lại']);
            exit;
        }
        
        $pending_user = $_SESSION['pending_user'];
        
        // Kiểm tra email
        if(($pending_user['email'] ?? '') !== $email) {
            echo json_encode(['status' => 'error', 'message' => 'Email không khớp']);
            exit;
        }
        
        // Kiểm tra OTP - SO SÁNH VỚI OTP DUY NHẤT TRONG SESSION
        $sessionOtp = strval($pending_user['otp'] ?? '');
        $formOtp = strval($otp);
        
        if($sessionOtp !== $formOtp) {
            echo json_encode(['status' => 'error', 'message' => 'Mã OTP không đúng']);
            exit;
        }
        
        // Kiểm tra thời gian hết hạn
        $currentTime = time();
        $expiryTime = $pending_user['otp_expiry'] ?? 0;
        
        if($currentTime > $expiryTime) {
            echo json_encode(['status' => 'error', 'message' => 'Mã OTP đã hết hạn. Vui lòng gửi lại']);
            unset($_SESSION['pending_user']);
            exit;
        }
        
        // LƯU VÀO DATABASE
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Xóa user cũ chưa verify
        $stmt = $pdo->prepare("DELETE FROM users WHERE email = ? AND is_verified = 0");
        $stmt->execute([$email]);
        
        // Thêm user mới
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone_number, password, is_verified, role, created_at) 
                              VALUES (?, ?, ?, ?, 1, 'customer', NOW())");
        $stmt->execute([
            $pending_user['full_name'],
            $pending_user['email'],
            $pending_user['phone_number'],
            $pending_user['password']
        ]);
        
        $user_id = $pdo->lastInsertId();
        
        // XÓA SESSION PENDING
        unset($_SESSION['pending_user']);
        
        // Đăng nhập tự động
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $pending_user['full_name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = 'customer';
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Đăng ký thành công!',
            'redirect' => '/Jshop/'
        ]);
        
    } catch(Exception $e) {
        error_log("OTP Verification Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi xác thực']);
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
?>