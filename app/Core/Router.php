<?php
declare(strict_types=1);

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function dispatch(string $method, string $requestUri): never
    {
        $path = parse_url($requestUri, PHP_URL_PATH) ?: '/';

        if (
            isset($_GET['ruta'])
            && is_string($_GET['ruta'])
            && $_GET['ruta'] !== ''
        ) {
            $path = '/' . trim($_GET['ruta'], '/');
        }

        $projectRoot = realpath(dirname(__DIR__, 2));
        $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

        if ($projectRoot !== false && $documentRoot !== false) {
            $projectRoot = str_replace('\\', '/', $projectRoot);
            $documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');

            if (str_starts_with(
                strtolower($projectRoot),
                strtolower($documentRoot)
            )) {
                $basePath = '/' . trim(
                    substr($projectRoot, strlen($documentRoot)),
                    '/'
                );

                if (
                    $basePath !== '/'
                    && str_starts_with($path, $basePath)
                ) {
                    $path = substr($path, strlen($basePath)) ?: '/';
                }
            }
        }

        $scriptDir = str_replace(
            '\\',
            '/',
            dirname($_SERVER['SCRIPT_NAME'] ?? '/')
        );

        if (
            $scriptDir !== '/'
            && $scriptDir !== '.'
            && str_starts_with($path, $scriptDir)
        ) {
            $path = substr($path, strlen($scriptDir)) ?: '/';
        }

        $path = '/' . trim($path, '/');

        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            $status = isset($this->routes['GET'][$path]) ? 405 : 404;
            Response::json(
                false,
                null,
                'Ruta o método no disponible.',
                $status
            );
        }

        call_user_func($handler);
        exit;
    }
}
