<?php
declare(strict_types=1);

final class FiltroService
{
    public function __construct(private Categoria $categorias, private Atributo $atributos, private ValorProducto $valores, private Producto $productos) {}

    public function categorias(): array
    {
        return Categoria::query()->orderBy('nombre')->orderBy('id')->get(['id', 'nombre'])->toArray();
    }

    public function categoryExists(int $id): bool
    {
        return Categoria::query()->whereKey($id)->exists();
    }

    public function filtros(int $categoryId, array $activeFilters = []): array
    {
        $validAttributes = Atributo::idsForCategory($categoryId);
        foreach (array_keys($activeFilters) as $attributeId) {
            if (!in_array((int) $attributeId, $validAttributes, true)) {
                throw new InvalidArgumentException('Uno de los atributos no pertenece a la categoría.');
            }
        }

        $grouped = [];
        foreach ($validAttributes as $attributeId) {
            $rows = ValorProducto::filters($categoryId, $activeFilters, $attributeId);
            if ($rows === []) {
                foreach ($activeFilters[$attributeId] ?? [] as $selected) {
                    $rows[] = ['valor' => $selected, 'conteo' => 0];
                }
            }

            $grouped[$attributeId] = [
                'id' => $attributeId,
                'nombre' => Atributo::nameById($attributeId),
                'valores' => [],
            ];

            foreach ($rows as $row) {
                $grouped[$attributeId]['valores'][] = [
                    'valor' => $row['valor'],
                    'conteo' => (int) $row['conteo'],
                ];
            }
        }

        return array_values($grouped);
    }

    public function productos(?int $categoryId, string $query, array $filters, int $page, int $limit): array
    {
        if ($categoryId !== null) {
            $valid = Atributo::idsForCategory($categoryId);
            foreach (array_keys($filters) as $attributeId) {
                if (!in_array((int) $attributeId, $valid, true)) {
                    throw new InvalidArgumentException('Uno de los atributos no pertenece a la categoría.');
                }
            }
        }

        return Producto::search($categoryId, $query, $filters, $page, $limit);
    }
}
