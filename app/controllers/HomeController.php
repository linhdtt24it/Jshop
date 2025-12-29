<?php
require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {

    public function index() {

        $categoryModel = $this->model("Category");
        $categories = $categoryModel->getAllCategories();
        
        $productModel = $this->model("Product");
        
        $tsNam = $productModel->getProductsByCategory(1);
        $tsNu = $productModel->getProductsByCategory(2); 

        $productsVang     = $productModel->getProductsByMaterial(1);
        $productsBac      = $productModel->getProductsByMaterial(2);
        $productsKimCuong = $productModel->getProductsByMaterial(3);
        $productsDaQuy    = $productModel->getProductsByMaterial(4);
        $productsNgoc     = $productModel->getProductsByMaterial(5);
        $productsKhac     = $productModel->getProductsByMaterial(6);

        $productsYeuThich = $productModel->getProductsByIdRange(28, 31);
        $productsMoi      = $productModel->getProductsByIdRange(32, 37);
        $productsDongHo   = $productModel->getProductsByIdRange(54, 60);

        $newsModel = $this->model("News");
        $newsList = $newsModel->getLatestNews(3); 

        $this->view('home/index', [
            'page_title'        => 'JSHOP - Trang sức cao cấp',
            'categories'        => $categories,
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
            'newsList'          => $newsList
        ]);
    }
}