<?php

namespace App\Core;

class Request
{
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);

        $basePath = config('app.base_path', '');
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        return rtrim($uri, '/') ?: '/';
    }

    public function get(string $key, $default = null): ?string
    {
        $value = $_GET[$key] ?? $default;
        return $value !== null ? $this->sanitize($value) : null;
    }

    public function post(string $key, $default = null): ?string
    {
        $value = $_POST[$key] ?? $default;
        return $value !== null ? $this->sanitize($value) : null;
    }

    public function all(): array
    {
        $data = [];
        foreach ($_POST as $key => $value) {
            $data[$key] = $this->sanitize($value);
        }
        return $data;
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function ip(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';
    }

    private function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
}
