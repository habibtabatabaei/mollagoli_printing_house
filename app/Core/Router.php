<?php
// app/Core/Router.php

class Router {
    protected $routes = [];

    // ثبت مسیرهای GET
    public function get($uri, $controller, $action) {
        $this->routes['GET'][$uri] = ['controller' => $controller, 'action' => $action];
    }

    // ثبت مسیرهای POST (برای فرم‌ها و ماشین‌حساب)
    public function post($uri, $controller, $action) {
        $this->routes['POST'][$uri] = ['controller' => $controller, 'action' => $action];
    }

    // بررسی و هدایت درخواست به کنترلر مناسب
    public function dispatch($uri, $method) {
        // حذف کوئری استرینگ‌ها از آدرس (مثل ?id=1)
        $uri = strtok($uri, '?');

        if (array_key_exists($uri, $this->routes[$method])) {
            $controllerName = $this->routes[$method][$uri]['controller'];
            $actionName = $this->routes[$method][$uri]['action'];

            // بارگذاری فایل کنترلر
            $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controller = new $controllerName();
                $controller->$actionName();
            } else {
                $this->abort(500, "کنترلر $controllerName یافت نشد.");
            }
        } else {
            $this->abort(404, "صفحه مورد نظر یافت نشد.");
        }
    }

    // مدیریت خطاهای استاندارد
    protected function abort($code, $message = '') {
        http_response_code($code);
        echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
        echo "<h1 style='color: #ff6600;'>خطای $code</h1>";
        echo "<p>$message</p>";
        echo "</div>";
        die();
    }
}