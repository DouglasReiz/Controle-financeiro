<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Controller\Api;

use App\ControleFinanceiro\Controller\AbstractController;
use App\ControleFinanceiro\Service\AuthSession;
use App\ControleFinanceiro\Service\DashboardSummaryService;

class DashboardController extends AbstractController
{
    public function summaryAction(): void
    {
        header('Content-Type: application/json');

        $authUser = AuthSession::get();

        if (!$authUser) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Não autenticado']);
            return;
        }

        $summaryData = DashboardSummaryService::getSummary($authUser->getId());

        $response = [
            'success' => true,
            'data' => $summaryData,
        ];

        echo json_encode($response);
    }
}
