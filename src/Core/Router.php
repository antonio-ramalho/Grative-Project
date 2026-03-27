<?php

class Router {
    private array $routes = [];

    public function get(string $path, string $action): void {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, string $action): void {
        $this->addRoute('POST', $path, $action);
    }

    private function addRoute(string $method, string $path, string $action): void {
        // Converte {id} em padrão regex
        $pattern = preg_replace('/\{[a-z]+\}/', '([^/]+)', $path);
        $this->routes[$method][] = ['pattern' => $pattern, 'action' => $action];
    }
    
    public function dispatch(string $method, string $uri): void {
        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match('#^' . $route['pattern'] . '$#', $uri, $matches)) {
                [$ctrl, $mtd] = explode('@', $route['action']);
                array_shift($matches);
                (new $ctrl())->$mtd(...$matches);
                return;
            }
        }
        http_response_code(404);
        echo '<h1>404 — Página não encontrada</h1>';
    }
}