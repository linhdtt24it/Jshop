<?php
// app/core/App.php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function run() {
        $url = $this->parseUrl();

        // 1. Xác định Controller
        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : $this->controller;
        $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $controllerName();
            unset($url[0]);
        } else {
            $this->notFound();
            return;
        }

        // 2. Xác định Method
        $method = !empty($url[1]) ? $url[1] : $this->method;

        if (method_exists($this->controller, $method)) {
            $this->method = $method;
            unset($url[1]);
        } else {
            $this->notFound();
            return;
        }

        // 3. Lấy tham số
        $this->params = $url ? array_values($url) : [];

        // 4. Gọi method với params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl() {
        $url = $_GET['url'] ?? '';
        $url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
        return $url ? explode('/', $url) : [];
    }

    private function notFound() {
        http_response_code(404);
        // Có thể load view 404 ở đây nếu muốn
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }
}