<?php
declare(strict_types=1);
final class ValorProducto
{
    public function __construct(private PDO $db) {}
    public function filters(int $categoryId, array $activeFilters = [], ?int $excludeAttribute = null): array
    {
        $sql = 'SELECT a.id AS atributo_id, a.nombre AS atributo, vp.valor, COUNT(DISTINCT p.id) AS conteo
                FROM productos p INNER JOIN valores_productos vp ON vp.producto_id = p.id
                INNER JOIN atributos a ON a.id = vp.atributo_id WHERE p.id_categoria = :categoria_id';
        $params = ['categoria_id' => $categoryId];
        $this->appendFilterExists($sql, $params, $activeFilters, $excludeAttribute);
        $sql .= ' GROUP BY a.id, a.nombre, vp.valor ORDER BY a.nombre, vp.valor';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    private function appendFilterExists(string &$sql, array &$params, array $filters, ?int $excludeAttribute): void
    {
        $index = 0;
        foreach ($filters as $attributeId => $values) {
            if ($excludeAttribute !== null && (int) $attributeId === $excludeAttribute) continue;
            $placeholders = [];
            foreach ($values as $value) { $key = 'f' . $index++; $placeholders[] = ':' . $key; $params[$key] = $value; }
            if ($placeholders !== []) {
                $key = 'a' . $index++;
                $sql .= ' AND EXISTS (SELECT 1 FROM valores_productos vpx WHERE vpx.producto_id = p.id AND vpx.atributo_id = :' . $key . ' AND vpx.valor IN (' . implode(',', $placeholders) . '))';
                $params[$key] = (int) $attributeId;
            }
        }
    }
}
