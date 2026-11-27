<?php
declare(strict_types=1);
final class Categoria
{
    public function __construct(private PDO $db) {}
    public function all(): array { return $this->db->query('SELECT id, nombre FROM categorias ORDER BY nombre, id')->fetchAll(); }
    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM categorias WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return (bool) $stmt->fetchColumn();
    }
}
