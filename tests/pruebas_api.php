<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/Config/Database.php';

try {
    $db = Database::connection();
    $checks = [
        'categorías >= 7' => (int) $db->query('SELECT COUNT(*) FROM categorias')->fetchColumn() >= 7,
        'productos >= 7' => (int) $db->query('SELECT COUNT(*) FROM productos')->fetchColumn() >= 7,
        'atributos >= 11' => (int) $db->query('SELECT COUNT(*) FROM atributos')->fetchColumn() >= 11,
        'valores EAV >= 13' => (int) $db->query('SELECT COUNT(*) FROM valores_productos')->fetchColumn() >= 13,
        'Polo Casual conservado' => (bool) $db->query("SELECT 1 FROM productos WHERE id=1 AND nombre='Polo Casual' AND id_categoria=1")->fetchColumn(),
        'Smartphone X1 conservado' => (bool) $db->query("SELECT 1 FROM productos WHERE id=2 AND nombre='Smartphone X1' AND id_categoria=2")->fetchColumn(),
    ];
    foreach ($checks as $name => $passed) echo ($passed ? 'APROBADO' : 'FALLIDO') . ': ' . $name . PHP_EOL;
} catch (Throwable $exception) {
    fwrite(STDERR, 'NO EJECUTADO: ' . $exception->getMessage() . PHP_EOL);
    exit(2);
}
