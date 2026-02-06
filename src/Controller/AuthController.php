<?php

namespace App\ControleFinanceiro\Controller;

use App\Model\User;
use function App\config\helpers\route;

class AuthController
{

    public function authAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $user = User::findByEmail($email);

            // Verifique se o usuário existe E se a senha bate
            if ($user && password_verify($senha, $user->senha)) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['id'] = $user->id;
                $_SESSION['nome'] = $user->nome;

                header('Location: ' . route('home')); // Redirecione para uma área logada
                exit; // Sempre use exit após header location
            } else {
                // Use uma mensagem genérica por segurança
                echo 'E-mail ou senha inválidos';
            }
        }
    }
}