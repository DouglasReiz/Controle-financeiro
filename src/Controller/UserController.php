<?php

namespace App\ControleFinanceiro\Controller;

class UserController extends AbstractController
{
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => password_hash($_POST['senha']),
            ];
        }
    }
}