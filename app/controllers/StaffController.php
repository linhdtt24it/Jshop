<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once __DIR__ . '/../../config/constants.php'; 
require_once __DIR__ . '/../../config/database.php';

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class StaffController extends Controller
{
    private $contactModel;
    private $userModel;
    private $orderModel;
    private $orderItemModel;
    private $ROOT_URL;
    
    public function __construct() {
        $this->contactModel = $this->model('ContactModel');
        $this->userModel = $this->model('User'); 
        $this->orderModel = $this->model('Order');
        $this->orderItemModel = $this->model('OrderItem');
        
        $this->ROOT_URL = str_replace('public/', '', BASE_URL);
    }

    public function index()
    {
        $allowed_roles = ['staff', 'employee', 'admin'];

        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'] ?? '', $allowed_roles)) {
            header("Location: " . BASE_URL . "auth/index");
            exit;
        }

        $action = $_GET['action'] ?? 'dashboard';
        
        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            $this->dashboard();
        }
    }

    private function dashboard()
    {
        $user_name = $_SESSION['user_name'] ?? 'Nhân viên';
        $user_role_display = ucfirst($_SESSION['user_role'] ?? 'staff');
        
        $user = [
            'full_name' => $user_name, 
            'role'      => $user_role_display, 
            'avatar'    => 'https://ui-avatars.com/api/?background=fce7f3&color=be123c&name=' . urlencode($user_name)
        ];

        $orders_pending_count = $this->orderModel->countOrdersByStatus('pending'); 
        $orders_processing_count = $this->orderModel->countOrdersByStatus('processing'); 
        $orders_completed_count = $this->orderModel->countOrdersByStatus('completed');
        
        $orders_total_pending = $orders_pending_count + $orders_processing_count;

        $all_messages = $this->contactModel->getAllMessages();
        $new_messages_count = count(array_filter($all_messages, fn($m) => $m['status'] === 'new'));

        $recent_orders = $this->orderModel->getPendingOrders();
        $recent_orders = array_slice($recent_orders, 0, 5);

        $data = [
            'user'               => $user, 
            'orders_pending'     => $orders_pending_count,
            'orders_processing'  => $orders_processing_count,
            'completed_orders'   => $orders_completed_count,
            'orders_total_pending' => $orders_total_pending,
            'new_messages_count' => $new_messages_count,
            'recent_orders'      => $recent_orders
        ];

        $this->view('staff/dashboard', $data);
    }

    private function orders_pending()
    {
        $orders = $this->orderModel->getPendingOrders(); 
        
        $data = [
            'page_title' => 'Quản lý Đơn hàng Chờ & Đang xử lý',
            'orders' => $orders,
            'BASE_URL' => BASE_URL
        ];
        
        $this->view('staff/orders/index', $data);
    }
 

    private function order_detail()
    {
        $order_id = (int)($_GET['id'] ?? 0);
        
        if ($order_id <= 0) {
             header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=orders_pending');
             exit;
        }

        $order = $this->orderModel->getOrderById($order_id);
        
        if (!$order) {
             header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=orders_pending');
             exit;
        }

        $items = $this->orderItemModel->getOrderItemsByOrderId($order_id);

        $orders_pending_count = $this->orderModel->countOrdersByStatus('pending'); 
        $orders_processing_count = $this->orderModel->countOrdersByStatus('processing'); 
        $orders_total_pending = $orders_pending_count + $orders_processing_count;
        $all_messages = $this->contactModel->getAllMessages();
        $new_messages_count = count(array_filter($all_messages, fn($m) => $m['status'] === 'new'));

        $data = [
            'page_title' => 'Chi tiết đơn hàng #' . $order_id,
            'order' => $order,
            'items' => $items,
            'BASE_URL' => BASE_URL,
            'orders_total_pending' => $orders_total_pending,
            'new_messages_count' => $new_messages_count
        ];

        $this->view('staff/orders/detail', $data);
    }

    private function update_order_status()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = (int)($_POST['order_id'] ?? 0);
            $status = $_POST['status'] ?? '';
            
            if ($order_id > 0 && !empty($status)) {
                $db = (new Database())->connect();
                $stmt = $db->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
                $stmt->execute([$status, $order_id]);
                $_SESSION['success_message'] = "Cập nhật đơn hàng #$order_id thành công!";
            }
            header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=orders_pending');
            exit;
        }
    }

    private function messages()
    {
        $messages = $this->contactModel->getAllMessages();
        
        $data = ['page_title' => 'Tin nhắn khách hàng', 'messages' => $messages];
        $this->view('staff/messages/index', $data);
    }

    private function message_detail()
    {
        $message_id = (int)($_GET['id'] ?? 0);
        $message_detail = $this->contactModel->getMessageDetail($message_id);
        
        if (!$message_detail) { 
            $this->view('errors/404');
            return;
        }
        
        if ($message_detail['status'] === 'new') {
            $this->contactModel->updateStatus($message_id, 'in_progress');
            $message_detail['status'] = 'in_progress';
        }

        $all_messages = $this->contactModel->getAllMessages();

        $data = [
            'page_title' => 'Chi tiết tin nhắn', 
            'msg' => $message_detail,
            'messages' => $all_messages 
        ];

        $this->view('staff/messages/detail', $data);
    }
    
    private function profile()
    {
        $user_id = $_SESSION['user_id'] ?? 0;
        
        $staff_data = $this->userModel->getUserById($user_id);

        $all_messages = $this->contactModel->getAllMessages();

        $data = [
            'page_title' => 'Hồ sơ Cá nhân Staff',
            'messages' => $all_messages,
            'staff_data' => $staff_data
        ];
        
        $this->view('staff/profile', $data);
    }
    
    private function update_profile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=profile');
            exit;
        }

        $user_id = (int)($_POST['user_id'] ?? 0);
        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');
        $age = (int)($_POST['age'] ?? 0);
        $hometown = trim($_POST['hometown'] ?? '');
        $health_status = trim($_POST['health_status'] ?? '');

        if ($user_id != ($_SESSION['user_id'] ?? 0) || empty($full_name) || empty($email)) {
            $_SESSION['error_message'] = 'Lỗi xác thực hoặc dữ liệu không hợp lệ.';
            header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=profile');
            exit;
        }

        $update_data = [
            'full_name' => $full_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'age' => $age,
            'hometown' => $hometown,
            'health_status' => $health_status,
        ];
        
        try {
            $update_success = $this->userModel->updateProfile($user_id, $update_data);
            
            if ($update_success) {
                $_SESSION['user_name'] = $full_name; 
                $_SESSION['email'] = $email;
                $_SESSION['phone_number'] = $phone_number;
                $_SESSION['success_message'] = 'Cập nhật hồ sơ thành công!';
            } else {
                $_SESSION['error_message'] = 'Không có thông tin nào được cập nhật.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Lỗi cập nhật hồ sơ: ' . $e->getMessage();
        }

        header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=profile');
        exit;
    }

    private function change_password()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=profile');
            exit;
        }

        $user_id = (int)($_POST['user_id'] ?? 0);
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if ($user_id != ($_SESSION['user_id'] ?? 0)) {
            $_SESSION['error_message'] = 'Lỗi xác thực người dùng.';
            header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=profile');
            exit;
        }

        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
            header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=profile');
            exit;
        }

        $user_data = $this->userModel->getUserById($user_id); 
        
        if ($user_data && password_verify($old_password, $user_data['password'])) {
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            
            try {
                $update_success = $this->userModel->updatePassword($user_id, $new_password_hashed); 
                
                if ($update_success) {
                    $_SESSION['success_message'] = 'Đổi mật khẩu thành công! Vui lòng đăng nhập lại lần sau.';
                } else {
                    $_SESSION['error_message'] = 'Không thể cập nhật mật khẩu. Vui lòng thử lại.';
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = 'Lỗi cơ sở dữ liệu khi đổi mật khẩu.';
            }


        } else {
            $_SESSION['error_message'] = 'Mật khẩu cũ không chính xác.';
        }

        header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=profile');
        exit;
    }


    public function reply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=dashboard'); exit;
        }
        
        $message_id = (int)($_POST['message_id'] ?? 0);
        $content = trim($_POST['reply_content'] ?? '');
        $user_id = $_SESSION['user_id'] ?? null;
        
        if (empty($content) || !$user_id || !$message_id) { 
            header('Location: ' . $_SERVER['HTTP_REFERER']); exit; 
        }

        $this->contactModel->addReply($message_id, $user_id, 'staff', $content);

        $customer_info = $this->contactModel->getCustomerInfoByMessageId($message_id);
        
        if ($customer_info) {
            $to_email = $customer_info['email'];
            $to_name = $customer_info['full_name'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'thithuylinhdinh003@gmail.com'; 
                $mail->Password   = 'woxmcvkbzsevhdjp'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('thithuylinhdinh003@gmail.com', 'JSHOP Support Team');
                $mail->addAddress($to_email, $to_name);

                $mail->isHTML(true);
                $mail->Subject = 'Phản hồi yêu cầu hỗ trợ của bạn (#' . $message_id . ')';
                $mail->Body    = '<h2>Xin chào ' . htmlspecialchars($to_name) . ',</h2><p>Đội ngũ hỗ trợ JSHOP đã phản hồi yêu cầu của bạn:</p>'
                                . '<div style="padding: 15px; border-left: 3px solid #000; background: #f8f8f8;">' 
                                . nl2br(htmlspecialchars($content)) . '</div>'
                                . '<p>Vui lòng truy cập lại trang liên hệ JSHOP để gửi thêm câu hỏi nếu cần.</p>';
                
                $mail->send();
                
            } catch (Exception $e) {
                error_log("Mailer Error: {$mail->ErrorInfo}");
            }
        }
        
        $this->contactModel->updateStatus($message_id, 'closed');

        header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=message_detail&id=' . $message_id);
        exit;
    }

    public function close_message() {
        $message_id = (int)($_GET['id'] ?? 0);
        
        if ($message_id) {
             $this->contactModel->updateStatus($message_id, 'closed');
        }
        
        header('Location: ' . $this->ROOT_URL . 'app/controllers/StaffController.php?action=messages');
        exit;
    }
}

(new StaffController())->index();