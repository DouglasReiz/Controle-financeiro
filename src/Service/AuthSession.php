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

    public static function set(AuthUser $user): void
    {
        self::ensureStarted();
        $_SESSION[self::KEY] = [
            'id' => $user->getId(),
            'nome' => $user->getNome(),
        ];
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
        if ($data === null || !isset($data['id'], $data['nome'])) {
            return null;
        }
        return new AuthUser((int) $data['id'], (string) $data['nome']);
    }
}
