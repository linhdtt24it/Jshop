<?php
require_once __DIR__ . '/../core/Controller.php';

class CollectionController extends Controller {

    private $collectionModel;
    private $productModel;

    public function __construct() {
        $this->collectionModel = $this->model('Collection');
        $this->productModel = $this->model('Product');
    }

    public function index() {
        $collections = $this->collectionModel->getAllCollections();
        
        $data = [
            'page_title' => 'Tất Cả Bộ Sưu Tập',
            'collections' => $collections
        ];

        $this->view('collection/index', $data);
    }

    public function detail($identifier = null) {
        if (empty($identifier)) {
            header("Location: " . BASE_URL . "collection");
            exit;
        }

        $collection = $this->collectionModel->getCollectionByIdOrSlug($identifier);

        if (!$collection) {
            $this->view('errors/404');
            return;
        }

        $products = $this->productModel->getProductsByCollectionId($collection['collection_id']);

        $data = [
            'page_title' => $collection['name'] ?? 'Chi Tiết Bộ Sưu Tập',
            'collection' => $collection,
            'products' => $products
        ];

        $this->view('collection/detail', $data);
    }

}

?>