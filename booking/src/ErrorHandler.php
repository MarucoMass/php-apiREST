<?php

class ErrorHandler
{
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);

        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
        ]);
    }

    public static function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool {
        
        $error_message = "Error: [$errno] $errstr - $errfile:$errline " . date("Y-m-d H:i:s");
        error_log($error_message . PHP_EOL, 3, "error_log.txt");

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
