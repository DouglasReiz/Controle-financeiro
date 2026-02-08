<?php

declare(strict_types=1);

namespace App\ControleFinanceiro\Service;

/**
 * DashboardSummaryService
 *
 * Fornece dados resumidos do dashboard.
 * Hoje: Mock. Amanhã: Integrado com DashboardRepository.
 *
 * Contrato: Recebe userId, retorna array com month, income, expenses, balance.
 * Diorge: Layout dos cards já está pronto pra receber esses dados ✨
 */
final class DashboardSummaryService
{
    /**
     * Obter resumo do dashboard para um usuário
     *
     * @param int $userId ID do usuário autenticado
     * @return array{month: string, income: float, expenses: float, balance: float}
     */
    public static function getSummary(int $userId): array
    {
        // Mock temporário. Douglas, pode quebrar isso quando quiser 😄
        // Quando o banco entrar, integra com DashboardRepository aqui.
        return [
            'month' => 'Fevereiro',
            'income' => 5200.00,
            'expenses' => 3100.00,
            'balance' => 2100.00,
        ];
    }
}
