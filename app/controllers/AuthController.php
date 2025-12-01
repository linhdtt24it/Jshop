<?php
require_once __DIR__.'/../../config/database.php';

// Include PHPMailer thủ công từ zip
require_once __DIR__.'/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__.'/../libs/PHPMailer/src/SMTP.php';
require_once __DIR__.'/../libs/PHPMailer/src/Exception.php';

class AuthController {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // ====== REGISTER ======
    public function register() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        // Validate cơ bản
        if(!$name || !$email || !$phone || !$address || !$password || !$confirm){
            echo json_encode(['status'=>'error','message'=>'Vui lòng điền đủ thông tin']);
            return;
        }

        if($password !== $confirm){
            echo json_encode(['status'=>'error','message'=>'Mật khẩu không trùng khớp']);
            return;
        }

        // Kiểm tra email đã tồn tại
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0){
            echo json_encode(['status'=>'error','message'=>'Email đã tồn tại']);
            return;
        }

        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Role mặc định là 'customer'
        $role = 'customer';

        // Tạo OTP 6 chữ số
        $otp = rand(100000,999999);

        // Lưu user + OTP
        $stmt = $this->pdo->prepare("INSERT INTO users (name,email,phone,address,password,role,otp,otp_verified) VALUES (?,?,?,?,?,?,?,0)");
        $stmt->execute([$name,$email,$phone,$address,$hash,$role,$otp]);

        // Gửi OTP email
        $this->sendOTPEmail($email,$otp);

        echo json_encode(['status'=>'success','message'=>'Đăng ký thành công! Kiểm tra email để nhận OTP.']);
    }

    // ====== LOGIN ======
    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user || !password_verify($password,$user['password'])){
            echo json_encode(['status'=>'error','message'=>'Email hoặc mật khẩu không đúng']);
            return;
        }

        if($user['otp_verified']==0){
            echo json_encode(['status'=>'error','message'=>'Vui lòng xác nhận email trước khi đăng nhập']);
            return;
        }

        // Lưu session
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        echo json_encode(['status'=>'success','message'=>'Đăng nhập thành công']);
    }

    // ====== SEND OTP EMAIL ======
    private function sendOTPEmail($email,$otp){
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // thay bằng mail server của bạn
            $mail->SMTPAuth = true;
            $mail->Username = 'youremail@gmail.com'; // email gửi OTP
            $mail->Password = 'yourpassword';       // mật khẩu ứng dụng (App Password)
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('youremail@gmail.com','JSHOP');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Xác nhận email JSHOP';
            $mail->Body = "Mã OTP của bạn là: <b>$otp</b>";

            $mail->send();
        } catch (Exception $e){
            error_log("Mailer Error: ".$mail->ErrorInfo);
        }
    }

    // ====== VERIFY OTP ======
    public function verifyOTP() {
        $email = trim($_POST['email'] ?? '');
        $otp = trim($_POST['otp'] ?? '');

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=? AND otp=?");
        $stmt->execute([$email,$otp]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user){
            echo json_encode(['status'=>'error','message'=>'OTP không hợp lệ']);
            return;
        }

        $stmt = $this->pdo->prepare("UPDATE users SET otp_verified=1, otp=NULL WHERE user_id=?");
        $stmt->execute([$user['user_id']]);

        echo json_encode(['status'=>'success','message'=>'Xác nhận email thành công! Bạn có thể đăng nhập.']);
    }
}
