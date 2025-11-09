<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../models/Purpose.php';

class MenController extends Controller
{
    public function index()
    {
        $product  = new Product();
        $material = new Material();
        $purpose  = new Purpose();

        $material_id = $_GET['material'] ?? null;
        $purpose_id  = $_GET['purpose'] ?? null;

        $data = [];
        $data['products']  = $product->getMenProducts($material_id, $purpose_id);
        $data['materials'] = $material->getAll();
        $data['purposes']  = $purpose->getAll();
        $data['title']     = "Trang sức Nam - JSHOP";

        $this->view('collection/men', $data);
    }
}
