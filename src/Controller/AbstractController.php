<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

abstract class AbstractController
{
    public function render(string $viewName): void
    {
        include __DIR__ . "/../View/$viewName.php";
    }
}
