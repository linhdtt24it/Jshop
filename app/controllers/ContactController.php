<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ContactModel.php';
require_once __DIR__ . '/../helpers/EmailHelper.php';

class ContactController extends Controller
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

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
            return;
        }

        header('Content-Type: application/json');

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường bắt buộc!']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ!']);
            return;
        }
        
        $subject = "Yêu cầu liên hệ từ khách hàng: $name";

        // Step 1: Save message to database
        $db_success = $this->contactModel->saveMessage($name, $email, $phone, $subject, $message);

        if (!$db_success) {
            echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ khi lưu tin nhắn. Vui lòng thử lại.']);
            return;
        }

        // Step 2: Send email notification
        $email_config = require __DIR__ . '/../../config/email.php';
        $admin_email = $email_config['username']; // Send to the admin's email configured

        $email_body = "
        Bạn có một tin nhắn liên hệ mới từ khách hàng:
        <br><br>
        <strong>Họ tên:</strong> {$name}<br>
        <strong>Email:</strong> {$email}<br>
        <strong>Điện thoại:</strong> {$phone}<br>
        <br>
        <strong>Nội dung:</strong><br>
        {$message}
        ";
        
        $email_options = [
            'reply_to_email' => $email,
            'reply_to_name' => $name
        ];

        $email_success = EmailHelper::send($admin_email, $subject, $email_body, $email_options);

        if ($email_success) {
            echo json_encode(['success' => true, 'message' => 'Gửi tin nhắn thành công! Chúng tôi sẽ phản hồi sớm nhất có thể.']);
        } else {
            // Message was saved to DB, but email failed. This is still a success for the user.
            // You might want to log this email failure for the admin.
            echo json_encode(['success' => true, 'message' => 'Gửi tin nhắn thành công! Yêu cầu của bạn đã được ghi nhận.']);
        }
    }
}