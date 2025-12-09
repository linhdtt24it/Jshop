<?php
// app/controllers/AdminController.php
session_start();

// 1. Check quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['status' => 'error', 'message' => 'Hết phiên đăng nhập']); exit;
    }
    header("Location: /Jshop/public/login.php"); exit;
}

require_once __DIR__ . '/../models/User.php';
$userModel = new User();
$action = $_GET['action'] ?? 'dashboard';

// --- PHẦN GIAO DIỆN ---

if ($action == 'dashboard') {
    $user = [
        'name' => $_SESSION['user_name'], 
        'role' => 'Administrator',
        'avatar' => 'https://ui-avatars.com/api/?background=000&color=d4af37&name=' . urlencode($_SESSION['user_name'])
    ];
    $stats = ['revenue' => '1.25 Tỷ', 'orders' => 1240, 'new_customers' => 85, 'inventory' => 3500];
    require_once __DIR__ . '/../views/admin/dashboard.php';
}

elseif ($action == 'staff_list') {
    $staffList = $userModel->getAllStaff();
    require_once __DIR__ . '/../views/admin/staff/index.php';
}

// --- PHẦN XỬ LÝ AJAX (JSON) ---

// 1. Lấy chi tiết nhân viên (để sửa)
elseif ($action == 'get_staff_detail') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    try {
        $db = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
        // [ĐÃ SỬA] Chọn thêm phone_number
        $stmt = $db->prepare("SELECT user_id, full_name, email, role, phone_number FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user ? ['status' => 'success', 'data' => $user] : ['status' => 'error']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// 2. Lưu nhân viên (Thêm hoặc Sửa)
elseif ($action == 'save_staff_ajax') {
    header('Content-Type: application/json');
    
    $id = $_POST['user_id'] ?? '';
    $name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? ''; // [ĐÃ SỬA] Nhận SĐT
    $pass = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'staff';

    if(empty($name) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin']); exit;
    }

    try {
        if ($id) {
            // >>> SỬA (UPDATE) <<<
            $db = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
            if (!empty($pass)) {
                $sql = "UPDATE users SET full_name=?, email=?, phone_number=?, role=?, password=? WHERE user_id=?";
                $stmt = $db->prepare($sql);
                $result = $stmt->execute([$name, $email, $phone, $role, password_hash($pass, PASSWORD_DEFAULT), $id]);
            } else {
                $sql = "UPDATE users SET full_name=?, email=?, phone_number=?, role=? WHERE user_id=?";
                $stmt = $db->prepare($sql);
                $result = $stmt->execute([$name, $email, $phone, $role, $id]);
            }
            $msg = "Cập nhật thành công!";
        } else {
            // >>> THÊM (CREATE) <<<
            if(empty($pass)) {
                echo json_encode(['status' => 'error', 'message' => 'Nhập mật khẩu']); exit;
            }
            // [ĐÃ SỬA] Truyền thêm $phone
            $result = $userModel->createStaff($name, $email, $pass, $role, $phone);
            $msg = "Thêm mới thành công!";
        }

        if ($result) echo json_encode(['status' => 'success', 'message' => $msg]);
        else echo json_encode(['status' => 'error', 'message' => 'Lỗi: Email có thể đã tồn tại']);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
    exit;
}

// 3. Xóa nhân viên
elseif ($action == 'delete_staff_ajax') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    if ($id == $_SESSION['user_id']) {
        echo json_encode(['status' => 'error', 'message' => 'Không thể tự xóa mình']); exit;
    }
    $result = $userModel->deleteUser($id);
    echo json_encode($result ? ['status' => 'success'] : ['status' => 'error']);
    exit;
}
?>