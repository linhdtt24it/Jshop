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
        $params = [];
        if ($id >= 6 && $id <= 18) {
            $params = ['cat' => $id];
        } else {
            switch ($id) {
                case 1:
                    $params = ['gender' => 'nam'];
                    break;
                case 2:
                    $params = ['gender' => 'nu'];
                    break;
                case 3:
                    $params = ['cat' => 3];
                    break;
                case 4:
                    $params = ['purpose_id' => 2];
                    break;
                case 5:
                    $params = ['cat' => 5];
                    break;
                default:
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
                    return;
            }
        }

        $queryParams = http_build_query(array_merge([
            'q' => '',
            'cat' => 0,
            'gender' => '',
            'material_id' => 0,
            'purpose_id' => 0,
        ], $params));

        $url = BASE_URL . "/product?" . $queryParams;

        header("Location: " . $url);
        exit();
    }
}
