<?php

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method][$path])){
            http_response_code(404);
            echo "404 - Page Not Found";
            return;
        }

        [$controllerClass, $methodName] = $this->routes[$method][$path];

        $controller = new $controllerClass;
        $controller->$methodName();
    }
}

?>