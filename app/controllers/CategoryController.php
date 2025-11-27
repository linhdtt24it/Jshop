<?php

class CategoryController extends Controller
{
    private $categoryModel;
    private $productModel;

    public function __construct()
    {
        $this->categoryModel = $this->model("Category");
        $this->productModel  = $this->model("Product");
    }

    public function index($id = 0)
    {
        if (!$id) {
            echo "Danh mục không tồn tại";
            return;
        }

        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            echo "Danh mục không tồn tại";
            return;
        }

        $products = $this->productModel->getProductsByCategory($id);

        $this->view("products/list", [
            "category" => $category,
            "products" => $products
        ]);
    }
}
