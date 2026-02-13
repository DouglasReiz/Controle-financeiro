<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

/**
 * Persistência técnica de sessão: armazena e recupera a identidade (AuthUser).
 * Não expõe id/nome soltos — apenas get(): ?AuthUser.
 * Controllers e middleware usam AuthSession + AuthUser; não acessam $_SESSION.
 */
final class AuthSession
{
    private const KEY = 'auth_user';

    private static function ensureStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($user): void
    {
        self::ensureStarted();
        // Salva na chave correta definida na constante KEY
        $_SESSION[self::KEY] = $user;
    }

    public static function clear(): void
    {
        self::ensureStarted();
        unset($_SESSION[self::KEY]);
    }

    public static function has(): bool
    {
        self::ensureStarted();
        return isset($_SESSION[self::KEY]);
    }

    public static function get(): ?AuthUser
    {
        self::ensureStarted();
        $data = $_SESSION[self::KEY] ?? null;

        if ($data === null) {
            return null;
        }

        // Se você salvou apenas o ID (como está acontecendo no register)
        if (is_string($data) || is_int($data)) {
            return new AuthUser((int)$data, "Usuário"); // Nome genérico até o próximo login
        }

        // Se for um array ou objeto (como virá do login futuro)
        if (is_array($data) && isset($data['id'], $data['nome'])) {
            return new AuthUser((int)$data['id'], (string)$data['nome']);
        }

        return null;
    }
}
