<?php
// app/controllers/HomeController.php
require_once __DIR__ . '/../core/Controller.php';

class HomeController extends Controller {
    public function index() {
        $this->view('home/index', [
            'page_title' => 'JSHOP - Trang sức cao cấp',
            'is_home' => true,
            'user' => $_SESSION['user'] ?? null,
            'cart_count' => $_SESSION['cart_count'] ?? 0
        ]);
    }
}