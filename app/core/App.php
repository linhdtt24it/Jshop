<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/Model.php';

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function run() {
        $url = $this->parseUrl();

        if ($this->handleAuthRoutes($url)) {
            return; 
        }

        // ADMIN
        if (!empty($url[0]) && $url[0] === "admin") {

            require_once __DIR__ . "/../controllers/AdminController.php";
            $this->controller = new AdminController();

            $method = !empty($url[1]) ? $url[1] : "dashboard";

            if (method_exists($this->controller, $method)) {
                unset($url[0], $url[1]);
                $this->params = $url ? array_values($url) : [];
                call_user_func_array([$this->controller, $method], $this->params);
                return;
            }

            $this->notFound();
            return;
        }

        // ===== CONTROLLER BÌNH THƯỜNG =====
        // Ví dụ: /category → CategoryController
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

        // ===== METHOD =====
        // Nếu URL không có method → mặc định “index”
        $method = !empty($url[1]) ? $url[1] : $this->method;

        if (method_exists($this->controller, $method)) {
            $this->method = $method;
            unset($url[1]);
        } else {
            $this->notFound();
            return;
        }

        // ===== PARAMS =====
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // ===== AUTH =====
  // ===== AUTH =====
private function handleAuthRoutes($url) {
    // Nếu muốn logout trực tiếp
    if(!empty($url[0]) && $url[0] === "logout") {
        session_start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: /Jshop/public/");
        exit;
    }

    return false;
}


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
