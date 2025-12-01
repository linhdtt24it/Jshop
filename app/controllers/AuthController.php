<?php
// C:\Users\LENOVO\Jshop\app\controllers\AuthController.php
session_start();
header('Content-Type: application/json');

// ========== TEST ==========
if(($_GET['action'] ?? '') == 'test') {
    echo json_encode([
        'status' => 'success',
        'message' => 'AuthController hoạt động',
        'path' => __FILE__
    ]);
    exit;
}

// ========== REGISTER ==========
if(($_GET['action'] ?? '') == 'register') {
    try {
        $full_name = $_POST['name'] ?? ''; // CHÚ Ý: POST name nhưng DB là full_name
        $email = $_POST['email'] ?? '';
        $phone_number = $_POST['phone'] ?? ''; // CHÚ Ý: POST phone nhưng DB là phone_number
        $address = $_POST['address'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        
        // Kiểm tra cơ bản
        if(empty($full_name) || empty($email) || empty($password) || $password !== $confirm) {
            echo json_encode(['status' => 'error', 'message' => 'Thông tin không hợp lệ']);
            exit;
        }
        
        // Kết nối DB
        $pdo = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Kiểm tra email tồn tại
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Email đã tồn tại']);
            exit;
        }
        
        // Tạo OTP
        $otp = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Lưu user - CHÚ Ý TÊN CỘT
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone_number, password, otp, otp_expiry, is_verified, role, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, 0, 'customer', NOW())");
        
        // Không có address trong bảng của mày, bỏ qua hoặc thêm cột
        if(columnExists($pdo, 'users', 'address')) {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone_number, address, password, otp, otp_expiry, is_verified, role, created_at) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, 0, 'customer', NOW())");
            $stmt->execute([$full_name, $email, $phone_number, $address, $hash, $otp, $otp_expiry]);
        } else {
            $stmt->execute([$full_name, $email, $phone_number, $hash, $otp, $otp_expiry]);
        }
        
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
        $mail->Body = "Xin chào $full_name,<br>Mã OTP của bạn là: <b style='font-size:24px;color:red;'>$otp</b>";
        
        if($mail->send()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Đăng ký thành công! Kiểm tra email để nhận OTP.',
                'email' => $email
            ]);
        } else {
            echo json_encode([
                'status' => 'warning',
                'message' => 'Đăng ký thành công nhưng không gửi được email.',
                'otp' => $otp,
                'email' => $email
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
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$user || !password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Sai email hoặc mật khẩu']);
            exit;
        }
        
        // Kiểm tra OTP verified
        if($user['is_verified'] == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng xác thực OTP trước']);
            exit;
        }
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name']; // CHÚ Ý: full_name
        $_SESSION['user_email'] = $email;
        $_SESSION['user_role'] = $user['role'] ?? 'customer';
        
        echo json_encode(['status' => 'success', 'message' => 'Đăng nhập thành công']);
        
    } catch(Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi đăng nhập: ' . $e->getMessage()]);
    }
    exit;
}

// ========== HÀM KIỂM TRA CỘT ==========
function columnExists($pdo, $table, $column) {
    try {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM $table LIKE ?");
        $stmt->execute([$column]);
        return $stmt->rowCount() > 0;
    } catch(Exception $e) {
        return false;
    }
}

// ========== DEFAULT ==========
echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ: ' . ($_GET['action'] ?? 'none')]);