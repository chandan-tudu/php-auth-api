<?php
class ErrorHandler
{
    public static function handelError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): void {

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);
        if (DEBUG_MODE) {
            echo json_encode([
                "status" => 500,
                "code" => $exception->getCode(),
                "message" => $exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine()
            ]);
            return;
        }

        echo json_encode([
            "status" => 500,
            "message" => "Internal Server Error."
        ]);
    }
}
