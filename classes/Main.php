<?php
class Main
{
    // Checking the Request Method
    public static function checkMethod(string $method)
    {
        if ($_SERVER['REQUEST_METHOD'] === $method) {
            return true;
        }
        static::sendJson(405, "Invalid Request Method. HTTP method should be $method");
    }

    public static function sendJson(int $status, string $msg, array $extra = []): void
    {
        http_response_code($status);
        $arr = ['status' => $status];
        if ($msg) $arr['message'] = $msg;
        echo json_encode(array_merge($arr, $extra));
        exit;
    }

    public static function _404()
    {
        static::sendJson(404, 'Not Found!');
    }
}
