# API Contracts

## GET /api/dashboard/summary

**Auth:** Obrigatório (middleware RequireAuth)

**Response:**
```json
{
  "success": true,
  "data": {
    "month": "string",
    "income": "float",
    "expenses": "float",
    "balance": "float"
  }
}
```

**Status Codes:**
- `200` - Sucesso
- `401` - Não autenticado (middleware redireciona para /login)
- `500` - Erro no servidor

**Implementação:**
- Hoje: Mockado em `src/Service/DashboardSummaryService`
- Amanhã: Douglas integra com banco via `DashboardRepository`

## Arquitetura de Serviços

### Fluxo de Dados

```
Controller (Api/DashboardController)
    ↓
Service (DashboardSummaryService) ← Mock aqui agora
    ↓
Repository (DashboardRepository) ← Douglas cria quando quiser
    ↓
Database
```

### Responsabilidades

- **Controller**: Recebe request, valida autenticação, chama service, retorna JSON
- **Service**: Lógica de negócio, orquestra dados, sem acesso a $_SESSION
- **Repository**: Acesso ao banco (não existe ainda)

### Quando o Banco Entrar

1. Douglas cria `src/Repository/DashboardRepository.php`
2. Implementa `getSummaryByUserId(int $userId): array`
3. Atualiza `DashboardSummaryService::getSummary()` para usar repository
4. Nenhuma alteração no Controller ou Frontend (Diorge não mexe em nada)
