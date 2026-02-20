<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $name, callable $handler): void
    {
        $this->routes[$name] = $handler;
    }

    public function dispatch(string $name): void
    {
        if (!isset($this->routes[$name])) {
            throw new HttpException('Rota não encontrada.', 404);
        }

        ($this->routes[$name])();
    }
}
