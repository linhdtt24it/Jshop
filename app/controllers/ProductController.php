<?php
require_once "app/models/Product.php";

class ProductController {
    public function index() {
        $productModel = new Product();
        $products = $productModel->getAll();

        include "app/views/products/list.php";
    }
}
?>
