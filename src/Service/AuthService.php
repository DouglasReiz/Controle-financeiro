<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

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

    public function createUser(string $name, string $email, string $password): AuthUser
    {
        // Mock temporário - substituir por insert no banco
        return new AuthUser(1, $name);
    }
}
