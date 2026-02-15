<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Http;

/**
 * Abstração para requisições HTTP
 * Encapsula $_SERVER, $_POST, $_GET, php://input
 * Controllers usam isso em vez de acessar superglobals diretamente
 */
final class RequestHandler
{
    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    public function json(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') === false) {
            return [];
        }
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function uri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    public function wantsJson(): bool
    {
        // 1. Verifica header Accept
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (strpos($accept, 'application/json') !== false) {
            return true;
        }

        // 2. Verifica prefixo /api na rota
        $uri = $this->uri();
        if (str_starts_with($uri, '/api/')) {
            return true;
        }

        // 3. Fallback: se Content-Type é JSON, assume resposta JSON
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            return true;
        }

        return false;
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isPut(): bool
    {
        return $this->method() === 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->method() === 'DELETE';
    }

    public function isPatch(): bool
    {
        return $this->method() === 'PATCH';
    }
}
