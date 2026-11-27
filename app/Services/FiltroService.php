<?php
declare(strict_types=1);
final class FiltroService
{
    public function __construct(private PDO $db, private Categoria $categorias, private Atributo $atributos, private ValorProducto $valores, private Producto $productos) {}
    public function categorias(): array { return $this->categorias->all(); }
    public function categoryExists(int $id): bool { return $this->categorias->exists($id); }
    public function filtros(int $categoryId, array $activeFilters = []): array
    {
        $validAttributes = $this->atributos->idsForCategory($categoryId);
        foreach (array_keys($activeFilters) as $attributeId) if (!in_array((int) $attributeId, $validAttributes, true)) throw new InvalidArgumentException('Uno de los atributos no pertenece a la categoría.');
        $grouped = [];
        foreach ($validAttributes as $id) {
            $rows = $this->valores->filters($categoryId, $activeFilters, $id);
            if ($rows === []) foreach ($activeFilters[$id] ?? [] as $selected) $rows[] = ['atributo_id' => $id, 'atributo' => $this->atributos->name($id), 'valor' => $selected, 'conteo' => 0];
            foreach ($rows as $row) {
                if ((int) $row['atributo_id'] !== (int) $id) continue;
                $id = (int) $row['atributo_id'];
                $grouped[$id] ??= ['id' => $id, 'nombre' => $row['atributo'], 'valores' => []];
                $grouped[$id]['valores'][] = ['valor' => $row['valor'], 'conteo' => (int) $row['conteo']];
            }
        }
        foreach ($activeFilters as $id => $values) {
            $grouped[$id] ??= ['id' => $id, 'nombre' => $this->atributos->name((int) $id), 'valores' => []];
            foreach ($values as $selected) {
                $exists = array_filter($grouped[$id]['valores'], fn (array $item): bool => $item['valor'] === $selected);
                if ($exists === []) $grouped[$id]['valores'][] = ['valor' => $selected, 'conteo' => 0];
            }
        }
        return array_values($grouped);
    }
    public function productos(int $categoryId, string $query, array $filters, int $page, int $limit): array
    {
        $valid = $this->atributos->idsForCategory($categoryId);
        foreach (array_keys($filters) as $attributeId) if (!in_array((int) $attributeId, $valid, true)) throw new InvalidArgumentException('Uno de los atributos no pertenece a la categoría.');
        return $this->productos->search($categoryId, $query, $filters, $page, $limit);
    }
}
