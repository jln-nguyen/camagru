<?php

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[$method][] = [
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "404 - Page not found";
            return;
        }

        foreach ($this->routes[$method] as $route) {
            $pattern = $this->convertToRegex($route['path']);

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter(
                    $matches,
                    fn($key) => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );

                [$controllerClass, $methodName] = $route['handler'];
                $controller = new $controllerClass();
                $controller->$methodName(...array_values($params));
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page not found";
    }

    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}

?>