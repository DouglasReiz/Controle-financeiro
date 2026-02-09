<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

class DashboardController extends AbstractController
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('index/dashboard');
    }
}
