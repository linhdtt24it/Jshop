<?php

class AdminController
{
    private $productModel;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Product.php';
        $this->productModel = new Product(); // ← SỬA ĐÚNG
    }

    // ============================
    // Dashboard
    // ============================
    public function dashboard()
    {
        $totalProducts = $this->productModel->count(); // ← SỬA
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // ============================
    // Danh sách sản phẩm
    // ============================
    public function products()
    {
        $products = $this->productModel->getAllProducts();

        require_once __DIR__ . '/../views/admin/products.php';
    }

    // ============================
    // Form thêm sản phẩm
    // ============================
    public function addProduct()
    {
        require_once __DIR__ . '/../views/admin/addProduct.php';
    }

    // ============================
    // Lưu sản phẩm (POST)
    // ============================
    public function storeProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /Jshop/public/admin/products");
            exit;
        }

        $name     = $_POST["name"];
        $price    = $_POST["price"];
        $category = $_POST["category"];

        // ------------------------------------
        // Upload ảnh
        // ------------------------------------
        $imageName = null;

        if (!empty($_FILES['image']['name'])) {

            // Đường dẫn tuyệt đối → tránh sai path
            $targetDir = __DIR__ . "/../../public/uploads/products/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Tên file không trùng
            $imageName = time() . "_" . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $imageName;

            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        }

        // ------------------------------------
        // Lưu database
        // ------------------------------------
        $this->productModel->insertProduct($name, $price, $category, $imageName);


        header("Location: /Jshop/public/admin/products");
        exit;
    }
}
