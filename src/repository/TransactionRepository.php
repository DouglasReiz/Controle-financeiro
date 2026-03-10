<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Repository;

use App\ControleFinanceiro\Db\Database;
use PDO;

final class TransactionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.id, t.date, t.description, t.value, t.type,
                    t.account_id,  a.name AS account_name,
                    t.category_id, c.nome AS category_name,
                    t.created_at
             FROM transactions t
             INNER JOIN accounts    a ON a.id = t.account_id
             INNER JOIN categories  c ON c.id = t.category_id
             WHERE t.user_id = :user_id
             ORDER BY t.date DESC, t.created_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findByIdAndUserId(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT t.id, t.date, t.description, t.value, t.type,
                    t.account_id,  a.name AS account_name,
                    t.category_id, c.nome AS category_name,
                    t.created_at
             FROM transactions t
             INNER JOIN accounts    a ON a.id = t.account_id
             INNER JOIN categories  c ON c.id = t.category_id
             WHERE t.id = :id AND t.user_id = :user_id'
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function insert(array $data, int $userId): array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO transactions (user_id, account_id, category_id, description, value, type, date)
             VALUES (:user_id, :account_id, :category_id, :description, :value, :type, :date)'
        );
        $stmt->execute([
            ':user_id'     => $userId,
            ':account_id'  => $data['account_id'],
            ':category_id' => $data['category_id'],
            ':description' => $data['description'],
            ':value'       => $data['value'],
            ':type'        => $data['type'],
            ':date'        => $data['date'],
        ]);

        $id = (int) $this->db->lastInsertId();
        return $this->findByIdAndUserId($id, $userId);
    }

    public function update(int $id, array $data, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'UPDATE transactions
             SET account_id  = :account_id,
                 category_id = :category_id,
                 description = :description,
                 value       = :value,
                 type        = :type,
                 date        = :date
             WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            ':id'          => $id,
            ':user_id'     => $userId,
            ':account_id'  => $data['account_id'],
            ':category_id' => $data['category_id'],
            ':description' => $data['description'],
            ':value'       => $data['value'],
            ':type'        => $data['type'],
            ':date'        => $data['date'],
        ]);

        return $this->findByIdAndUserId($id, $userId);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM transactions WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
