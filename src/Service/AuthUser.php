<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

/**
 * Identidade do usuário autenticado (domínio).
 * Representa "quem está logado" — não é estado técnico de sessão.
 */
final class AuthUser
{
    public function __construct(
        private readonly int $id,
        private readonly string $nome,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
}
