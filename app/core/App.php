<?php
// app/core/App.php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function run() {
        $url = $this->parseUrl();

        // 🔥 THÊM PHẦN NÀY: Xử lý auth routes trước
        if ($this->handleAuthRoutes($url)) {
            return; // Nếu là auth route thì dừng ở đây
        }

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

    // 🔥 THÊM PHƯƠNG THỨC XỬ LÝ AUTH ROUTES
    private function handleAuthRoutes($url) {
        $path = implode('/', $url);
        $method = $_SERVER['REQUEST_METHOD'];

        // Auth routes
        if ($method === 'POST') {
            if ($path === 'auth/login') {
                $this->callAuthController('login');
                return true;
            }
            if ($path === 'auth/register') {
                $this->callAuthController('register');
                return true;
            }
        }

        if ($method === 'GET' && $path === 'auth/logout') {
            $this->callAuthController('logout');
            return true;
        }

        return false;
    }

    // 🔥 THÊM PHƯƠNG THỨC GỌI AUTH CONTROLLER
    private function callAuthController($action) {
        $controllerFile = __DIR__ . "/../controllers/AuthController.php";
        
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo "AuthController not found";
            exit;
        }

        require_once $controllerFile;
        $controller = new AuthController();
        
        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo "Method $action not found in AuthController";
            exit;
        }

        $controller->$action();
        exit;
    }

    private function parseUrl() {
        $url = $_GET['url'] ?? '';
        $url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
        return $url ? explode('/', $url) : [];
    }

    private function notFound() {
        http_response_code(404);
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }
}