<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller\Api;

use App\ControleFinanceiro\Controller\AbstractController;
use App\ControleFinanceiro\Service\AuthSession;
use App\ControleFinanceiro\Service\DashboardSummaryService;
use App\ControleFinanceiro\Http\RequestHandler;

class DashboardController extends AbstractController
{
    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
    }

    public function summary(): void
    {
        $this->requireAuth();

        $user = $this->getAuthUser();
        $summary = DashboardSummaryService::getSummary($user->getId());

        $this->respondSuccess($summary);
    }
}
