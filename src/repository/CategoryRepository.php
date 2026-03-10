<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Repository;

use App\ControleFinanceiro\Db\Database;
use PDO;

final class CategoryRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.id, c.nome AS name, c.tipo AS type,
                COUNT(t.id)               AS transactionCount,
                COALESCE(SUM(t.value), 0) AS totalSpent
         FROM categories c
         LEFT JOIN transactions t ON t.category_id = c.id AND t.user_id = :user_id_join
         WHERE c.user_id = :user_id
         GROUP BY c.id, c.nome, c.tipo
         ORDER BY c.nome ASC'
        );
        $stmt->execute([':user_id' => $userId, ':user_id_join' => $userId]);
        return $stmt->fetchAll();
    }

    public function findByIdAndUserId(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, nome AS name, tipo AS type
         FROM categories
         WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function insert(array $data, int $userId): array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (user_id, nome, tipo)
            VALUES (:user_id, :nome, :tipo)'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':nome'    => $data['name'],
            ':tipo'    => $data['type'],
        ]);

        $id = (int) $this->db->lastInsertId();
        return $this->findByIdAndUserId($id, $userId);
    }

    public function update(int $id, array $data, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'UPDATE categories
            SET nome = :nome, tipo = :tipo
            WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            ':id'      => $id,
            ':user_id' => $userId,
            ':nome'    => $data['name'],
            ':tipo'    => $data['type'],
        ]);

        return $this->findByIdAndUserId($id, $userId);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM categories WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
