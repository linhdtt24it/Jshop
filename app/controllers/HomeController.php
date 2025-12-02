<?php
// app/controllers/HomeController.php
require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {

    public function index() {

        // Lấy danh mục
        $categoryModel = $this->model("Category");
        $categories = $categoryModel->getAllCategories();

        // Lấy sản phẩm
        $productModel = $this->model("Product");
        $tsNam = $productModel->getProductsByCategory(1);

        // ⬇️ LẤY FOOTER TỪ DATABASE
        $groupModel = $this->model("FooterGroup");
        $linkModel  = $this->model("FooterLink");
        $assetModel = $this->model("FooterAsset");

        $footer_groups = $groupModel->getAll();  // danh sách nhóm

        foreach ($footer_groups as &$g) {
            $g['links'] = $linkModel->getByGroup($g['id']); // gán link vào từng nhóm
        }

        $footer_assets = $assetModel->getAll(); // danh sách asset (logo thanh toán...)
        unset($g);
        // Truyền xuống view
        $this->view('home/index', [
            'page_title'    => 'JSHOP - Trang sức cao cấp',
            'categories'    => $categories,
            'tsNam'         => $tsNam,
            'is_home'       => true,
            'user'          => $_SESSION['user'] ?? null,
            'cart_count'    => $_SESSION['cart_count'] ?? 0,

            // ⬇️ TRUYỀN FOOTER XUỐNG VIEW
            'footer_groups' => $footer_groups,
            'footer_assets' => $footer_assets
        ]);
    }
}
