<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Core/Router.php';
require_once __DIR__ . '/app/Core/Response.php';
require_once __DIR__ . '/app/Config/config.php';
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/app/Models/Categoria.php';
require_once __DIR__ . '/app/Models/Producto.php';
require_once __DIR__ . '/app/Models/Atributo.php';
require_once __DIR__ . '/app/Models/ValorProducto.php';
require_once __DIR__ . '/app/Validators/FiltroValidator.php';
require_once __DIR__ . '/app/Services/FiltroService.php';
require_once __DIR__ . '/app/Controllers/ProductosController.php';

Database::boot();

$router = new Router();
$controller = new ProductosController();
require __DIR__ . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
