<?php
declare(strict_types=1);
final class Producto
{
    public function __construct(private PDO $db) {}
    public function search(int $categoryId, string $query, array $filters, int $page, int $limit): array
    {
        $where = 'p.id_categoria = :categoria_id';
        $params = ['categoria_id' => $categoryId];
        if ($query !== '') { $where .= ' AND p.nombre LIKE :busqueda'; $params['busqueda'] = '%' . $this->escapeLike($query) . '%'; }
        $this->appendFilterExists($where, $params, $filters);
        $countStmt = $this->db->prepare('SELECT COUNT(*) FROM productos p WHERE ' . $where);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare('SELECT p.id, p.nombre, c.id AS categoria_id, c.nombre AS categoria
            FROM productos p INNER JOIN categorias c ON c.id = p.id_categoria
            WHERE ' . $where . ' ORDER BY p.nombre, p.id LIMIT :limite OFFSET :desplazamiento');
        foreach ($params as $key => $value) $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        $stmt->bindValue(':limite', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':desplazamiento', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll();
        $this->addAttributes($products);
        return ['productos' => $products, 'total' => $total, 'pagina' => $page, 'limite' => $limit, 'total_paginas' => $total === 0 ? 0 : (int) ceil($total / $limit)];
    }
    private function appendFilterExists(string &$where, array &$params, array $filters): void
    {
        $index = 0;
        foreach ($filters as $attributeId => $values) {
            $placeholders = [];
            foreach ($values as $value) { $key = 'valor' . $index++; $placeholders[] = ':' . $key; $params[$key] = $value; }
            if ($placeholders !== []) { $key = 'atributo' . $index++; $where .= ' AND EXISTS (SELECT 1 FROM valores_productos vpx WHERE vpx.producto_id = p.id AND vpx.atributo_id = :' . $key . ' AND vpx.valor IN (' . implode(',', $placeholders) . '))'; $params[$key] = (int) $attributeId; }
        }
    }
    private function addAttributes(array &$products): void
    {
        if ($products === []) return;
        $ids = array_column($products, 'id');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare('SELECT vp.producto_id, a.nombre AS atributo, vp.valor FROM valores_productos vp INNER JOIN atributos a ON a.id = vp.atributo_id WHERE vp.producto_id IN (' . $placeholders . ') ORDER BY a.nombre, vp.valor');
        $stmt->execute($ids);
        $byProduct = [];
        foreach ($stmt->fetchAll() as $row) $byProduct[$row['producto_id']][$row['atributo']][] = $row['valor'];
        foreach ($products as &$product) $product['atributos'] = $byProduct[$product['id']] ?? [];
    }
    private function escapeLike(string $value): string { return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value); }
}
