<?php
$router->get('/', [$controller, 'index']);
$router->get('/api/categorias', [$controller, 'apiGetCategorias']);
$router->get('/api/filtros', [$controller, 'apiGetFiltros']);
$router->get('/api/productos', [$controller, 'apiGetProductos']);
