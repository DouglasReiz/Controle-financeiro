<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

abstract class AbstractController
{
    public function render(string $viewName): void
    {
        include dirname(__DIR__) . "/../src/View/$viewName.php";
    }
}