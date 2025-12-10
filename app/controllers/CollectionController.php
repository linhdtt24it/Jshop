<?php
// app/controllers/CollectionController.php
require_once __DIR__ . '/../core/Controller.php';

class CollectionController extends Controller
{
    private $collectionModel;

    public function __construct()
    {
        $this->collectionModel = $this->model('Collection');
    }

    public function index()
    {
        // Lấy tất cả bộ sưu tập
        $collections = $this->collectionModel->getAllWithProductCount();

        $data = [
            'page_title' => "Bộ Sưu Tập - JSHOP",
            'collections' => $collections // Truyền dữ liệu sang View
        ];
        $this->view('collection/index', $data);
    }
    
    public function detail($collection_slug)
    {
        // Lấy chi tiết bộ sưu tập theo slug
        $collection = $this->collectionModel->getBySlug($collection_slug);

        if (!$collection) {
            $this->view('errors/404');
            return;
        }

        // Lấy sản phẩm của bộ sưu tập
        $products = $this->collectionModel->getProductsByCollectionId($collection['collection_id']);

        $data = [
            'page_title' => $collection['name'] . " - JSHOP",
            'collection' => $collection,
            'products' => $products
        ];

        // Dùng view detail mới
        $this->view('collection/detail', $data);
    }
}