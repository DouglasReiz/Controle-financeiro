<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

class IndexController extends AbstractController
{
    public function loginAction(): void
    {
        parent::render('index/login');
    }

    public function registerAction(): void
    {
        parent::render('index/register');
    }

    public function dashboardAction(): void
    {
        parent::render('index/dashboard');
    }

    public function transactionsAction(): void
    {
        parent::render('transactions/index');
    }

    public function accountsAction(): void
    {
        parent::render('accounts/index');
    }

    public function categoriesAction(): void
    {
        parent::render('categories/index');
    }
}