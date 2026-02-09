<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

/**
 * DashboardController - Responsável por renderizar o dashboard
 * Métodos: index (exibir dashboard)
 */
class DashboardController extends AbstractController
{
    /**
     * GET /dashboard - Exibir dashboard
     */
    public function index(): void
    {
        $this->requireAuth();
        $this->render('index/dashboard');
    }
}
