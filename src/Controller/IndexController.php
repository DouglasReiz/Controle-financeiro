<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

class IndexController extends AbstractController
{
    public function indexAction(): void
    {
        session_start();

        // Se NÃO estiver logado, manda para o login
        if (!isset($_SESSION['id'])) {
            header('Location: ' . route('login'));
            exit;
        }

        // Se estiver logado, você pode renderizar a home ou redirecionar
        echo "Bem-vindo, " . $_SESSION['nome'];
        // require_once __DIR__ . '/../Views/home.php';

        parent::render('index/index');
    }

    public function loginAction(): void
    {
        parent::render('index/login');
    }
}