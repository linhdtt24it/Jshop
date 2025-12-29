<?php

require_once __DIR__ . '/../core/Controller.php';

class ContactController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => "Liên hệ - JSHOP"
        ];
        $this->view('contact/index', $data);
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được phép']);
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$name || !$email || !$message) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường bắt buộc!']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ!']);
            exit;
        }

        $to = "support@jshop.vn";
        $subject = "Liên hệ từ $name - JSHOP";
        $body = "
        Thông tin liên hệ:
        - Họ tên: $name
        - Email: $email
        - Điện thoại: $phone
        
        Nội dung:
        $message
        ";
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $body, $headers)) {
            echo json_encode(['success' => true, 'message' => 'Gửi thành công! Chúng tôi sẽ liên hệ sớm nhất.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống, vui lòng thử lại sau!']);
        }
        exit;
    }
}