<?php
// app/controllers/AuthController.php

ini_set('session.gc_maxlifetime', 7200);
ini_set('session.cookie_lifetime', 7200);
session_start();

header('Content-Type: application/json');

// ========== DEBUG LOG ==========
error_log("=== AUTH CONTROLLER ===");
error_log("Action: " . ($_GET['action'] ?? 'none'));
error_log("Session: " . json_encode($_SESSION));
error_log("POST: " . json_encode($_POST));

// ========== REGISTER ==========
if(($_GET['action'] ?? '') == 'register') {
    try {
        $full_name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone_number = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        error_log("Register: $email - Name: $full_name - Phone: $phone_number");
        
        // Validate
        if(empty($full_name) || empty($email) || empty($phone_number) || empty($password) || $password !== $confirm) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin và mật khẩu khớp nhau']);
            exit;
        }
        
        if(strlen($password) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu ít nhất 6 ký tự']);
            exit;
        }
        
        // Kết nối database với cấu trúc bảng của bạn
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Kiểm tra email đã đăng ký (đã verify)
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND is_verified = 1");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email đã được đăng ký']);
            exit;
        }
        
        // XÓA USER CHƯA VERIFY CŨ
        $stmt = $pdo->prepare("DELETE FROM users WHERE email = ? AND is_verified = 0");
        $stmt->execute([$email]);
        
        // Tạo OTP 6 số (15 phút)
        $otp = strval(rand(100000, 999999));
        
        // Mã hóa password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Thời gian hết hạn OTP (15 phút)
        $otp_expiry = date('Y-m-d H:i:s', time() + 900); // 15 phút = 900 giây
        
        // Insert vào bảng với đúng cấu trúc
        $stmt = $pdo->prepare("INSERT INTO users 
            (email, phone_number, password, role, is_verified, otp, otp_expiry, full_name, created_at) 
            VALUES (?, ?, ?, 'customer', 0, ?, ?, ?, NOW())");
        
        $stmt->execute([
            $email,
            $phone_number,
            $password_hash,
            $otp,
            $otp_expiry,
            $full_name
        ]);
        
        $user_id = $pdo->lastInsertId();
        
        // Lưu session
        $_SESSION['pending_user_id'] = $user_id;
        $_SESSION['pending_email'] = $email;
        $_SESSION['otp_attempts'] = 1;
        $_SESSION['register_time'] = time();
        
        error_log("✅ User created: ID=$user_id, OTP=$otp, Email=$email, Expiry=$otp_expiry");
        
        
        // GỬI EMAIL OTP
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
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4;'>
                <div style='max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                    <h2 style='color: #333;'>Xin chào $full_name,</h2>
                    <p>Cảm ơn bạn đã đăng ký tài khoản tại JSHOP!</p>
                    
                    <div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center; border: 1px dashed #ddd;'>
                        <p style='margin: 0 0 10px; font-size: 14px; color: #666;'>Mã OTP của bạn:</p>
                        <div style='font-size: 32px; font-weight: bold; color: #dc3545; letter-spacing: 5px;'>$otp</div>
                        <p style='margin: 10px 0 0; font-size: 12px; color: #888;'>(Mã có hiệu lực trong 60 phút)</p>
                    </div>
                    
                    <p style='color: #666;'>Vui lòng không chia sẻ mã này với bất kỳ ai.</p>
                    <p style='font-size: 12px; color: #999; margin-top: 30px;'>Đây là email tự động, vui lòng không trả lời.</p>
                </div>
            </div>
        ";
        
        if($mail->send()) {
            error_log("✅ Email sent to: $email");
            echo json_encode([
                'status' => 'success',
                'message' => 'Mã OTP đã được gửi đến email của bạn!',
                'email' => $email,
                'requires_otp' => true,
                'attempts_left' => 3
            ]);
        } else {
            // Xóa user nếu gửi email thất bại
            $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ? AND is_verified = 0");
            $stmt->execute([$user_id]);
            
            unset($_SESSION['pending_user_id']);
            unset($_SESSION['pending_email']);
            unset($_SESSION['otp_attempts']);
            unset($_SESSION['register_time']);
            
            error_log("❌ Email failed to send: $email");
            echo json_encode([
                'status' => 'error', 
                'message' => 'Không thể gửi email OTP. Vui lòng thử lại.'
            ]);
        }
        
    } catch(Exception $e) {
        error_log("Register error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi đăng ký: ' . $e->getMessage()]);
    }
    exit;
}

// ========== RESEND OTP ==========
if(($_GET['action'] ?? '') == 'resendOTP') {
    try {
        $email = $_POST['email'] ?? '';
        
        error_log("Resend OTP request for: $email");
        
        // Kiểm tra session
        if(!isset($_SESSION['pending_email']) || $_SESSION['pending_email'] !== $email) {
            error_log("❌ Session mismatch for resend");
            echo json_encode(['status' => 'error', 'message' => 'Phiên làm việc hết hạn']);
            exit;
        }
        
        // Kiểm tra số lần đã gửi
        $otpAttempts = $_SESSION['otp_attempts'] ?? 1;
        
        if($otpAttempts >= 4) {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Bạn đã gửi OTP quá số lần cho phép'
            ]);
            exit;
        }
        
        // Kết nối database
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Lấy thông tin user
        $stmt = $pdo->prepare("SELECT user_id, full_name FROM users WHERE email = ? AND is_verified = 0");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin đăng ký']);
            exit;
        }
        
        // Tạo OTP MỚI
        $newOtp = strval(rand(100000, 999999));
        $newExpiry = date('Y-m-d H:i:s', time() + 900); // 15 phút
        
        // Cập nhật OTP mới
        $stmt = $pdo->prepare("UPDATE users SET 
            otp = ?, 
            otp_expiry = ?
            WHERE user_id = ?");
        $stmt->execute([$newOtp, $newExpiry, $user['user_id']]);
        
        // Cập nhật số lần gửi
        $_SESSION['otp_attempts'] = $otpAttempts + 1;
        
        error_log("✅ Resend OTP ($otpAttempts) for $email: $newOtp (Expiry: $newExpiry)");
        
        // GỬI LẠI EMAIL
        // ... (phần gửi email giữ nguyên)
        
    } catch(Exception $e) {
        error_log("Resend OTP Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi gửi lại OTP: ' . $e->getMessage()]);
    }
    exit;
}

// ========== VERIFY OTP ==========
if(($_GET['action'] ?? '') == 'verifyOTP') {
    try {
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';
        
        error_log("Verify OTP: $email - OTP: $otp");
        
        if(empty($email) || empty($otp)) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập email và OTP']);
            exit;
        }
        
        // Kết nối database
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Kiểm tra OTP và thời gian
        $stmt = $pdo->prepare("
            SELECT * FROM users 
            WHERE email = ? 
            AND otp = ? 
            AND is_verified = 0
            AND otp_expiry > NOW()
        ");
        $stmt->execute([$email, $otp]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$user) {
            // Kiểm tra xem OTP có tồn tại nhưng hết hạn không
            $stmt = $pdo->prepare("
                SELECT otp_expiry FROM users 
                WHERE email = ? AND otp = ? AND is_verified = 0
            ");
            $stmt->execute([$email, $otp]);
            $expired = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($expired) {
                error_log("❌ OTP expired for $email: " . $expired['otp_expiry']);
                echo json_encode(['status' => 'error', 'message' => 'Mã OTP đã hết hạn. Vui lòng gửi lại']);
            } else {
                error_log("❌ OTP not found for $email: $otp");
                echo json_encode(['status' => 'error', 'message' => 'Mã OTP không đúng']);
            }
            exit;
        }
        
        // Xác thực thành công
        $stmt = $pdo->prepare("UPDATE users SET 
            is_verified = 1, 
            otp = NULL, 
            otp_expiry = NULL 
            WHERE user_id = ?");
        $stmt->execute([$user['user_id']]);
        
        // Đăng nhập
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['role'] ?? 'customer';
        
        // Xóa session pending
        unset($_SESSION['pending_user_id']);
        unset($_SESSION['pending_email']);
        unset($_SESSION['otp_attempts']);
        unset($_SESSION['register_time']);
        
        error_log("✅ OTP verified successfully: $email - UserID: " . $user['user_id']);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Đăng ký thành công!',
            'verified' => true,
            'redirect' => '/Jshop/'
        ]);
        
    } catch(Exception $e) {
        error_log("OTP Verification Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi xác thực: ' . $e->getMessage()]);
    }
    exit;
}

// ========== LOGIN ==========
if(($_GET['action'] ?? '') == 'login') {
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
        
        if(!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Email chưa đăng ký hoặc chưa xác thực']);
            exit;
        }
        
        if(!password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Sai mật khẩu']);
            exit;
        }
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['role'] ?? 'customer';
        
        error_log("✅ Login successful: $email");
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Đăng nhập thành công',
            'redirect' => '/Jshop/'
        ]);
        
    } catch(Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi đăng nhập']);
    }
    exit;
}

// ========== CANCEL REGISTRATION ==========
if(($_GET['action'] ?? '') == 'cancelRegistration') {
    try {
        $email = $_POST['email'] ?? '';
        
        if(empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
            exit;
        }
        
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Xóa user chưa verify
        $stmt = $pdo->prepare("DELETE FROM users WHERE email = ? AND is_verified = 0");
        $stmt->execute([$email]);
        $deleted = $stmt->rowCount();
        
        // Xóa session
        if(isset($_SESSION['pending_email']) && $_SESSION['pending_email'] === $email) {
            unset($_SESSION['pending_user_id']);
            unset($_SESSION['pending_email']);
            unset($_SESSION['otp_attempts']);
            unset($_SESSION['register_time']);
        }
        
        error_log("✅ Cancel registration: $email - Deleted: $deleted");
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Đã hủy đăng ký',
            'deleted' => $deleted
        ]);
        
    } catch(Exception $e) {
        error_log("Cancel registration error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hủy đăng ký']);
    }
    exit;
}

// ========== LOGOUT ==========
if(($_GET['action'] ?? '') == 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Đã đăng xuất']);
    exit;
}

// ========== DEFAULT ==========
echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);