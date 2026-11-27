<?php
declare(strict_types=1);

final class Response
{
    public static function json(bool $success, mixed $data = null, string $message = '', int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        $payload = ['success' => $success];
        if ($success) $payload['data'] = $data;
        else $payload['message'] = $message;
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function view(string $view, array $data = []): never
    {
        extract($data, EXTR_SKIP);
        require $view;
        exit;
    }
}
