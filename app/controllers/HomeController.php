<?php
// app/controllers/HomeController.php
require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {

    public function index() {

        // Lấy danh mục từ DB
        $categoryModel = $this->model("Category");
        $categories = $categoryModel->getAllCategories();

        // Lấy sản phẩm TRANG SỨC NAM (category_id = 1)
        $productModel = $this->model("Product");
        $tsNam = $productModel->getProductsByCategory(1); // ⬅️ lấy từ DB

        // Truyền xuống view
        $this->view('home/index', [
            'page_title' => 'JSHOP - Trang sức cao cấp',
            'categories'  => $categories,
            'tsNam'       => $tsNam,        // ⬅️ TRUYỀN XUỐNG VIEW
            'is_home'     => true,
            'user'        => $_SESSION['user'] ?? null,
            'cart_count'  => $_SESSION['cart_count'] ?? 0
        ]);
    }
}
