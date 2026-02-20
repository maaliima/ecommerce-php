<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function redirect(string $url): void
    {
        $destination = preg_match('#^https?://#i', $url) || str_starts_with($url, '/')
            ? $url
            : url($url);

        header('Location: ' . $destination);
        exit;
    }
}
