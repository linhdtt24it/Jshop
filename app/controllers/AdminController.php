<?php
// Jshop/app/controllers/AdminController.php
session_start();

// 1. Check quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['status' => 'error', 'message' => 'Hết phiên đăng nhập']); exit;
    }
    header("Location: /Jshop/public/login.php"); exit;
}

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Collection.php'; 
require_once __DIR__ . '/../models/Product.php';
// Models cho Dropdown
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../models/Purpose.php';
require_once __DIR__ . '/../models/Fengshui.php';

$userModel = new User();
$collectionModel = new Collection();
$productModel = new Product();
// Khởi tạo các models cho form
$categoryModel = new Category();
$materialModel = new Material();
$purposeModel = new Purpose();
$fengshuiModel = new Fengshui();


$action = $_GET['action'] ?? 'dashboard';

// --- PHẦN GIAO DIỆN (VIEW RENDERING) ---

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

// 1. Quản lý Bộ sưu tập (Collections)
elseif ($action == 'collections_list') {
    $collections = $collectionModel->getAllCollections();
    require_once __DIR__ . '/../views/admin/collection/index.php'; 
}

// 2. Quản lý Sản phẩm (Products)
elseif ($action == 'product_list') {
    $products = $productModel->getAllProductsWithDetails();
    require_once __DIR__ . '/../views/admin/product/index.php'; 
}

// 3. Quản lý Kho hàng (Inventory)
elseif ($action == 'inventory_list') {
    $products = $productModel->getAllProductsWithDetails();
    require_once __DIR__ . '/../views/admin/inventory/index.php'; 
}

// 4. Chỉnh sửa chi tiết Sản phẩm (bao gồm Thêm mới)
elseif ($action == 'edit_product' || $action == 'add_product') {
    $product_id = $_GET['id'] ?? null;
    $product = null;

    if ($product_id) {
        $product = $productModel->getProductById($product_id);
        if (!$product) {
            header("Location: " . '/Jshop/app/controllers/AdminController.php?action=product_list');
            exit;
        }
    }
    
    // Lấy tất cả dữ liệu cần thiết cho dropdown
    $data = [
        'product' => $product,
        'categories' => $categoryModel->getAllCategories(),
        'materials' => $materialModel->getAllMaterials(),
        'collections' => $collectionModel->getAllCollections(),
        'purposes' => $purposeModel->getAllPurposes(),
        'fengshui_items' => $fengshuiModel->getAllFengshuiItems()
    ];
    
    require_once __DIR__ . '/../views/admin/product/edit.php'; 
}


// --- PHẦN XỬ LÝ AJAX (JSON ACTIONS) ---

// 1. Lấy chi tiết nhân viên (để sửa)
elseif ($action == 'get_staff_detail') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    try {
        $db = new PDO("mysql:host=localhost;dbname=jshop;charset=utf8mb4", 'root', '');
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
    $phone = $_POST['phone'] ?? '';
    $pass = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'staff';

    if(empty($name) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu thông tin']); exit;
    }

    try {
        if ($id) {
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
            if(empty($pass)) {
                echo json_encode(['status' => 'error', 'message' => 'Nhập mật khẩu']); exit;
            }
            $result = true; 
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
    $result = true; 
    echo json_encode($result ? ['status' => 'success'] : ['status' => 'error']);
    exit;
}

// 4. Lấy chi tiết Bộ sưu tập (để sửa)
elseif ($action == 'get_collection_detail') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    $collection = $collectionModel->getCollectionById($id);
    echo json_encode($collection ? ['status' => 'success', 'data' => $collection] : ['status' => 'error', 'message' => 'Không tìm thấy bộ sưu tập']);
    exit;
}

// 5. Lưu Bộ sưu tập (Thêm hoặc Sửa)
elseif ($action == 'save_collection_ajax') {
    header('Content-Type: application/json');
    
    $id = $_POST['collection_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $image = $_POST['image'] ?? null;
    $description = $_POST['description'] ?? null;

    if(empty($name) || empty($slug)) {
        echo json_encode(['status' => 'error', 'message' => 'Tên và Slug không được trống']); exit;
    }

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $slug), '-'));

    try {
        if ($id) {
            $result = $collectionModel->updateCollection($id, $name, $slug, $description, $image);
            $msg = $result ? "Cập nhật Bộ sưu tập thành công!" : "Không có thay đổi hoặc lỗi khi cập nhật.";
        } else {
            $result = $collectionModel->createCollection($name, $slug, $description, $image);
            $msg = $result ? "Thêm Bộ sưu tập mới thành công!" : "Lỗi khi thêm mới (có thể do Slug đã tồn tại).";
        }

        echo json_encode(['status' => $result ? 'success' : 'error', 'message' => $msg]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit;
}

// 6. Xóa Bộ sưu tập
elseif ($action == 'delete_collection_ajax') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    
    if (!is_numeric($id) || $id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']); exit;
    }

    try {
        $result = $collectionModel->deleteCollection($id); 
        $msg = $result ? 'Đã xóa Bộ sưu tập thành công.' : 'Lỗi khi xóa.';
        echo json_encode(['status' => $result ? 'success' : 'error', 'message' => $msg]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit;
}

// 7. Lấy chi tiết Sản phẩm (dùng cho việc cập nhật kho hàng)
elseif ($action == 'get_product_detail') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    $product = $productModel->getProductById($id); 
    echo json_encode($product ? ['status' => 'success', 'data' => $product] : ['status' => 'error', 'message' => 'Không tìm thấy sản phẩm']);
    exit;
}

// 8. Xóa Sản phẩm
elseif ($action == 'delete_product_ajax') {
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? 0;
    
    if (!is_numeric($id) || $id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID sản phẩm không hợp lệ']); exit;
    }

    try {
        $result = $productModel->deleteProduct($id); 
        $msg = $result ? 'Đã xóa Sản phẩm thành công.' : 'Lỗi khi xóa.';
        echo json_encode(['status' => $result ? 'success' : 'error', 'message' => $msg]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit;
}

// 9. Cập nhật Tồn kho
elseif ($action == 'update_stock_ajax') {
    header('Content-Type: application/json');
    
    $id = $_POST['product_id'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if(empty($id) || !is_numeric($stock) || $stock < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']); exit;
    }

    try {
        $result = $productModel->updateProductStock($id, $stock);
        $msg = $result ? "Cập nhật tồn kho thành công (ID: $id, SL: $stock)!" : "Không có thay đổi hoặc lỗi khi cập nhật.";

        echo json_encode(['status' => $result ? 'success' : 'error', 'message' => $msg]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit;
}

// 10. Lưu Sản phẩm (Thêm hoặc Sửa)
elseif ($action == 'save_product_ajax') {
    header('Content-Type: application/json');
    
    $data = [
        'product_id' => $_POST['product_id'] ?? null,
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? null,
        'category_id' => $_POST['category_id'] ?? null,
        'material_id' => $_POST['material_id'] ?? null,
        'collection_id' => $_POST['collection_id'] ?? null,
        'purpose_id' => $_POST['purpose_id'] ?? null,
        'fengshui_id' => $_POST['fengshui_id'] ?? null,
        'price' => $_POST['price'] ?? 0,
        'stock' => $_POST['stock'] ?? 0,
        'image' => $_POST['image'] ?? null,
        'gender' => $_POST['gender'] ?? 'unisex'
    ];

    if (empty($data['name']) || $data['price'] <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Tên và Giá sản phẩm là bắt buộc.']); exit;
    }

    // Xử lý giá
    $data['price'] = str_replace(['.', ',', ' '], '', $data['price']);
    $data['price'] = is_numeric($data['price']) ? (float)$data['price'] : 0;
    
    try {
        $is_update = !empty($data['product_id']);
        $result = $productModel->saveProduct($data);

        if ($result) {
            $msg = $is_update ? "Cập nhật sản phẩm thành công!" : "Thêm sản phẩm mới thành công!";
            echo json_encode(['status' => 'success', 'message' => $msg]);
        } else {
            throw new Exception("Lỗi CSDL khi lưu sản phẩm.");
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit;
}
?>