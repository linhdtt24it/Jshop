<?php
// app/controllers/CollectionController.php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';

class CollectionController extends Controller
{
    public function index()
    {
        $data = [
            'page_title' => "Bộ Sưu Tập - JSHOP"
        ];
        $this->view('collection/index', $data);
    }
    
    public function detail($collection_slug)
    {
        $data = [
            'page_title' => "Chi tiết Bộ Sưu Tập - JSHOP",
            'collection_slug' => $collection_slug
        ];
        $this->view('collection/detail', $data);
    }

    // ✅ THÊM HÀM NÀY
    public function men()
    {
        $productModel = new Product();
        $data['page_title'] = "Trang Nam Giới - JSHOP";
        $data['products'] = $productModel->getByCategory(1); // 1 = Nam giới

        $this->view("collection/men", $data);
    }
}
