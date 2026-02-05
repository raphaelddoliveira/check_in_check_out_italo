<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $pattern, string $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, string $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    private function addRoute(string $method, string $pattern, string $handler): void
    {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        $this->routes[] = [
            'method'  => $method,
            'pattern' => $regex,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->callHandler($route['handler'], $request, $params);
                return;
            }
        }

        http_response_code(404);
        echo '404 - Pagina nao encontrada';
    }

    private function callHandler(string $handler, Request $request, array $params): void
    {
        [$controllerClass, $method] = explode('@', $handler);
        $controllerClass = 'App\\Controllers\\' . $controllerClass;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller {$controllerClass} nao encontrado");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            throw new \RuntimeException("Metodo {$method} nao encontrado em {$controllerClass}");
        }

        $controller->$method($request, ...$params);
    }
}
