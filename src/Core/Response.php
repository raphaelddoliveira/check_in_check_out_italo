<?php

namespace App\Core;

class Response
{
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function view(string $template, array $data = []): void
    {
        extract($data);

        ob_start();
        $templatePath = dirname(__DIR__, 2) . '/templates/' . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template nao encontrado: {$template}");
        }

        require $templatePath;
        $content = ob_get_clean();

        require dirname(__DIR__, 2) . '/templates/layout.php';
    }

    public static function viewWithoutLayout(string $template, array $data = []): void
    {
        extract($data);

        $templatePath = dirname(__DIR__, 2) . '/templates/' . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template nao encontrado: {$template}");
        }

        require $templatePath;
    }
}
