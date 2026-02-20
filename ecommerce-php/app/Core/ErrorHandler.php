<?php

namespace App\Core;

use ErrorException;
use Throwable;

class ErrorHandler
{
    public static function register(bool $debug = false): void
    {
        set_error_handler(function (int $severity, string $message, string $file, int $line): void {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(function (Throwable $exception) use ($debug): void {
            $status = $exception instanceof HttpException ? max(400, min(599, $exception->getCode() ?: 500)) : 500;
            http_response_code($status);

            $view = $status === 404 ? 'errors.404' : 'errors.500';
            $message = $debug ? $exception->getMessage() : null;

            View::render($view, [
                'message' => $message,
                'status' => $status,
            ]);
        });
    }
}
