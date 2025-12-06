<?php
// Include PHPMailer TRƯỚC KHI dùng "use"
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';

// Dùng "use" statement ngay sau include
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Sau đó mới đến phần code khác
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// ====== TEST ======
if ($action == 'test') {
    echo json_encode([
        'status' => 'success',
        'message' => 'File is working!',
        'path' => __FILE__
    ]);
    exit;
}

// ====== SIMPLE REGISTER (không cần database) ======
if ($action == 'register') {
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    
    if (empty($email) || empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu email hoặc tên']);
        exit;
    }
    
    // Tạo OTP
    $otp = rand(100000, 999999);
    
    // Gửi email
    $emailSent = sendOTPEmail($email, $name, $otp);
    
    if ($emailSent) {
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Đã gửi OTP đến email!',
            'email' => $email,
            'debug' => 'OTP: ' . $otp
        ]);
    } else {
        echo json_encode([
            'status' => 'warning',
            'message' => 'Không gửi được email. OTP là: ' . $otp,
            'otp' => $otp,
            'email' => $email
        ]);
    }
    exit;
}

// ====== HÀM GỬI EMAIL ======
function sendOTPEmail($to_email, $name, $otp) {
    try {
        $mail = new PHPMailer(true);
        
        // Gmail của BẠN
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'thithuylinhdinh003@gmail.com';
        $mail->Password = 'woxmcvkbzsevhdjp'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('thithuylinhdinh003@gmail.com', 'JSHOP');
        $mail->addAddress($to_email, $name);
        
        $mail->isHTML(true);
        $mail->Subject = 'JSHOP OTP: ' . $otp;
        $mail->Body = "<h2>Xin chào $name!</h2><p>Mã OTP của bạn: <b style='color:red;font-size:30px;'>$otp</b></p>";
        
        return $mail->send();
        
    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Action không hợp lệ']);