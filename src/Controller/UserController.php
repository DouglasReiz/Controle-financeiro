<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Service\AuthSession;
use App\ControleFinanceiro\Service\AuthUser;

class UserController extends AbstractController
{
    public function registerAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $this->getInput();
            $name = $input['name'] ?? '';
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';

            if (!$name || !$email || !$password) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios']);
                return;
            }

            // Mock temporário. Douglas, pode quebrar isso quando quiser 😄
            // Quando o banco entrar, salva de verdade aqui.
            $user = new AuthUser(1, $name);
            AuthSession::set($user);

            echo json_encode(['success' => true, 'message' => 'Conta criada com sucesso']);
            return;
        }

        parent::render('index/register');
    }

    private function getInput(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            return json_decode($json, true) ?? [];
        }
        return $_POST;
    }
}
