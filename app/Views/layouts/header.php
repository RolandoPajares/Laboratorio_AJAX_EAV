<?php $baseUrl = $baseUrl ?? ''; ?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Catálogo dinámico de productos con modelo EAV y AJAX">
    <title>EAV STORE | Catálogo dinámico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl . '/assets/css/estilos.css?v=20261127-2', ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl . '/assets/css/productos.css?v=20261127-2', ENT_QUOTES, 'UTF-8') ?>">
</head>

<body>