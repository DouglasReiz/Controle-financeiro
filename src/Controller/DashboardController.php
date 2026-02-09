<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller;

use App\ControleFinanceiro\Http\RequestHandler;
use App\ControleFinanceiro\Service\DashboardSummaryService;

class DashboardController extends AbstractController
{
    private RequestHandler $request;

    public function __construct()
    {
        $this->request = new RequestHandler();
    }

    public function index(): void
    {
        $this->requireAuth();

        $authUser = $this->getAuthUser();
        $summaryData = DashboardSummaryService::getSummary($authUser->getId());

        if ($this->request->wantsJson()) {
            $this->json(['success' => true, 'data' => $summaryData]);
            return;
        }

        $this->render('index/dashboard', ['summary' => $summaryData]);
    }
}
