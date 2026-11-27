<?php
declare(strict_types=1);
final class Atributo
{
    public function __construct(private PDO $db) {}
    public function idsForCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare('SELECT DISTINCT vp.atributo_id FROM valores_productos vp INNER JOIN productos p ON p.id = vp.producto_id WHERE p.id_categoria = :categoria_id');
        $stmt->execute(['categoria_id' => $categoryId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }
    public function name(int $id): string
    {
        $stmt = $this->db->prepare('SELECT nombre FROM atributos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return (string) ($stmt->fetchColumn() ?: 'Atributo');
    }
}
