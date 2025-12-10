
<?php

require_once __DIR__ . '/../core/Controller.php';

class ContactController extends Controller
{
    private $contactModel;

    public function __construct() {
        // [ĐÃ SỬA] Khởi tạo ContactModel
        $this->contactModel = $this->model('ContactModel');
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
        // Xử lý POST (AJAX)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được phép']);
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $subject = "Yêu cầu liên hệ từ khách hàng"; // Giả định subject

        // Validation
        if (!$name || !$email || !$message) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường bắt buộc!']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Email không hợp lệ!']);
            exit;
        }

        // [ĐÃ SỬA] Thay thế logic gửi email bằng cách lưu vào Database
        if ($this->contactModel->saveMessage($name, $email, $phone, $subject, $message)) {
            echo json_encode([
                'success' => true,
                'message' => 'Gửi thành công! Yêu cầu của bạn đã được ghi nhận. Chúng tôi sẽ phản hồi qua email sớm nhất.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi lưu tin nhắn, vui lòng thử lại sau!']);
        }
        exit;
    }
}