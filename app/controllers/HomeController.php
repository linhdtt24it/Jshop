<?php
// app/controllers/HomeController.php
require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {

    public function index() {

        // Lấy danh mục
        $categoryModel = $this->model("Category");
        $categories = $categoryModel->getAllCategories();
        
        $productModel = $this->model("Product");
        
        // --- TẢI DỮ LIỆU SẢN PHẨM TRANG CHỦ ---
        
        // Dữ liệu theo Category ID (dùng cho TrangSucNam và TrangSucNu)
        $tsNam = $productModel->getProductsByCategory(1);
        $tsNu = $productModel->getProductsByCategory(2); 

        // Dữ liệu theo Material ID (dùng cho Vang, Bac, KimCuong, v.v.)
        $productsVang     = $productModel->getProductsByMaterial(1);
        $productsBac      = $productModel->getProductsByMaterial(2);
        $productsKimCuong = $productModel->getProductsByMaterial(3);
        $productsDaQuy    = $productModel->getProductsByMaterial(4);
        $productsNgoc     = $productModel->getProductsByMaterial(5);
        $productsKhac     = $productModel->getProductsByMaterial(6);

        // Dữ liệu theo dải ID (dùng cho SanPhamYeuThich, SanPhamMoi, DongHo)
        $productsYeuThich = $productModel->getProductsByIdRange(28, 31);
        $productsMoi      = $productModel->getProductsByIdRange(32, 37);
        $productsDongHo   = $productModel->getProductsByIdRange(54, 60);

        // Giả định: Tải News (ví dụ top 3) để thay thế data tĩnh trong TinTuc.php
        $newsModel = $this->model("News");
        $newsList = $newsModel->getLatestNews(3); 

        // -------------------------------------

        $this->view('home/index', [
            'page_title'        => 'JSHOP - Trang sức cao cấp',
            'categories'        => $categories,        // DanhMucGoiY.php
            'is_home'           => true,
            'user'              => $_SESSION['user'] ?? null,
            'cart_count'        => $_SESSION['cart_count'] ?? 0,

            'tsNam'             => $tsNam,
            'tsNu'              => $tsNu,
            'productsVang'      => $productsVang,
            'productsBac'       => $productsBac,
            'productsKimCuong'  => $productsKimCuong,
            'productsDaQuy'     => $productsDaQuy,
            'productsNgoc'      => $productsNgoc,
            'productsKhac'      => $productsKhac,
            'productsYeuThich'  => $productsYeuThich,
            'productsMoi'       => $productsMoi,
            'productsDongHo'    => $productsDongHo,
            'newsList'          => $newsList           // TinTuc.php
        ]);
    }
}