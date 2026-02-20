<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            die('View não encontrada: ' . $view);
        }

        extract($data, EXTR_SKIP);
        include $viewPath;
    }
}
