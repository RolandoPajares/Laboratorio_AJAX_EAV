<?php
declare(strict_types=1);
final class ProductosController
{
    private ?FiltroService $service = null;
    private function service(): FiltroService
    {
        if ($this->service === null) { $db = Database::connection(); $this->service = new FiltroService($db, new Categoria($db), new Atributo($db), new ValorProducto($db), new Producto($db)); }
        return $this->service;
    }
    public function index(): never { Response::view(__DIR__ . '/../Views/productos/index.php', ['baseUrl' => $this->baseUrl()]); }
    public function apiGetCategorias(): never
    {
        try { Response::json(true, $this->service()->categorias()); }
        catch (Throwable $e) { error_log($e->getMessage()); Response::json(false, null, 'No fue posible cargar las categorías.', 500); }
    }
    public function apiGetFiltros(): never
    {
        try {
            $categoryId = FiltroValidator::category($_GET['categoria_id'] ?? null);
            if (!$this->service()->categoryExists($categoryId)) Response::json(false, null, 'La categoría no existe.', 404);
            Response::json(true, $this->service()->filtros($categoryId, FiltroValidator::filters($_GET['filtros'] ?? null)));
        } catch (InvalidArgumentException $e) { Response::json(false, null, $e->getMessage(), 400); }
        catch (Throwable $e) { error_log($e->getMessage()); Response::json(false, null, 'No fue posible cargar los filtros.', 500); }
    }
    public function apiGetProductos(): never
    {
        try {
            $categoryId = FiltroValidator::category($_GET['categoria_id'] ?? null);
            if (!$this->service()->categoryExists($categoryId)) Response::json(false, null, 'La categoría no existe.', 404);
            [$page, $limit] = FiltroValidator::pagination($_GET['pagina'] ?? 1, $_GET['limite'] ?? 12);
            $filters = FiltroValidator::filters($_GET['filtros'] ?? null);
            Response::json(true, $this->service()->productos($categoryId, FiltroValidator::query($_GET['q'] ?? ''), $filters, $page, $limit));
        } catch (InvalidArgumentException $e) { Response::json(false, null, $e->getMessage(), 400); }
        catch (Throwable $e) { error_log($e->getMessage()); Response::json(false, null, 'No fue posible cargar los productos.', 500); }
    }
    private function baseUrl(): string
    {
        if (defined('BASE_URL')) {
            return rtrim((string) parse_url(BASE_URL, PHP_URL_PATH), '/');
        }
        $directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        return $directory === '/' || $directory === '.' ? '' : rtrim($directory, '/');
    }
}
