<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

use App\ControleFinanceiro\Db\Database;

final class AuthService
{
    public function validateLoginCredentials(string $email, string $password): array
    {
        $errors = [];

        if (empty($email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }

        if (empty($password)) {
            $errors['password'] = 'Senha é obrigatória';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Senha deve ter no mínimo 6 caracteres';
        }

        return $errors;
    }

    public function validateRegistrationData(string $name, string $email, string $password): array
    {
        $errors = [];

        if (empty($name)) {
            $errors['name'] = 'Nome é obrigatório';
        } elseif (strlen($name) < 3) {
            $errors['name'] = 'Nome deve ter no mínimo 3 caracteres';
        }

        if (empty($email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }

        if (empty($password)) {
            $errors['password'] = 'Senha é obrigatória';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Senha deve ter no mínimo 6 caracteres';
        }

        return $errors;
    }

    public function authenticate(string $email, string $password): ?AuthUser
    {
        // Mock temporário - substituir por consulta ao banco
        if ($email === 'teste@example.com' && $password === 'senha123') {
            return new AuthUser(1, 'Usuário Teste');
        }

        return null;
    }

// No AuthService.php
    public function createUser($name, $email, $password)
    {
        $db = Database::getConnection();

        // Certificando de que as senhas estão sendo hasheadas!
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $db->prepare($sql);

        // O execute() retorna true ou false. Verifique isso!
        if (!$stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword
        ])) {
            throw new \Exception("Falha ao inserir usuário no banco.");
        }

        return $db->lastInsertId(); // Retorna o ID para a sessão
    }
}
