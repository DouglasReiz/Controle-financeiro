<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Repository;

use App\ControleFinanceiro\Db\Database;
use PDO;

final class AccountRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, type, institution, balance, created_at AS lastUpdate
             FROM accounts
             WHERE user_id = :user_id
             ORDER BY created_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findByIdAndUserId(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, type, institution, balance, created_at AS lastUpdate
             FROM accounts
             WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function insert(array $data, int $userId): array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO accounts (user_id, name, type, institution, balance)
             VALUES (:user_id, :name, :type, :institution, :balance)'
        );
        $stmt->execute([
            ':user_id'     => $userId,
            ':name'        => $data['name'],
            ':type'        => $data['type'],
            ':institution' => $data['institution'] ?? '',
            ':balance'     => $data['balance'] ?? 0,
        ]);

        $id = (int) $this->db->lastInsertId();
        return $this->findByIdAndUserId($id, $userId);
    }

    public function update(int $id, array $data, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'UPDATE accounts
             SET name = :name, type = :type, institution = :institution, balance = :balance
             WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            ':id'          => $id,
            ':user_id'     => $userId,
            ':name'        => $data['name'],
            ':type'        => $data['type'],
            ':institution' => $data['institution'] ?? '',
            ':balance'     => $data['balance'] ?? 0,
        ]);

        return $this->findByIdAndUserId($id, $userId);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM accounts WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
