# Diagrama de Arquitetura - Controllers Refatorados

## Estrutura Geral

```
┌─────────────────────────────────────────────────────────────────┐
│                         FRONT CONTROLLER                         │
│                      (public/index.php)                          │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                      ROUTER                                      │
│              (config/routes.php)                                 │
│  Mapeia URL → Controller::action                                │
└────────────────────────────┬────────────────────────────────────┘
                             │
                ┌────────────┼────────────┐
                │            │            │
                ▼            ▼            ▼
        ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
        │   VIEWS      │ │   API REST   │ │    AUTH      │
        │ Controllers  │ │ Controllers  │ │ Controller   │
        └──────────────┘ └──────────────┘ └──────────────┘
                │            │                    │
                ▼            ▼                    ▼
        ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
        │   RENDER     │ │   JSON       │ │  SESSION     │
        │   HTML       │ │   RESPONSE   │ │  MANAGEMENT  │
        └──────────────┘ └──────────────┘ └──────────────┘
```

---

## Fluxo de Requisição - Views

```
GET /contas
    │
    ▼
┌─────────────────────────────────────────┐
│ Router                                  │
│ Mapeia para: AccountController::index() │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ AccountController::index()              │
│ 1. requireAuth()                        │
│ 2. render('accounts/index')             │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ View: src/View/accounts/index.php       │
│ Renderiza HTML com dados                │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ Resposta HTTP 200                       │
│ Content-Type: text/html                 │
│ Body: HTML da página                    │
└─────────────────────────────────────────┘
```

---

## Fluxo de Requisição - API REST

```
POST /api/accounts
Content-Type: application/json
Body: {"name":"Nova Conta","type":"checking"}
    │
    ▼
┌─────────────────────────────────────────┐
│ Router                                  │
│ Mapeia para: Api\AccountController::store() │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ Api\AccountController::store()          │
│ 1. requireAuth()                        │
│ 2. $data = $this->request->json()       │
│ 3. Validar dados                        │
│ 4. $this->accountService->create($data) │
│ 5. json(['success'=>true, 'data'=>...]) │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ Resposta HTTP 201                       │
│ Content-Type: application/json          │
│ Body: {"success":true,"data":{...}}     │
└─────────────────────────────────────────┘
```

---

## Fluxo de Autenticação

```
POST /auth
Email: teste@example.com
Password: senha123
    │
    ▼
┌─────────────────────────────────────────┐
│ Router                                  │
│ Mapeia para: AuthController::store()    │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ AuthController::store()                 │
│ 1. $email = $this->request->post()      │
│ 2. $password = $this->request->post()   │
│ 3. Validar credenciais                  │
│ 4. AuthSession::set($user)              │
│ 5. json(['success'=>true])              │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ AuthSession::set()                      │
│ Armazena em $_SESSION                   │
└─────────────────────────────────────────┘
    │
    ▼
┌─────────────────────────────────────────┐
│ Resposta HTTP 200                       │
│ Set-Cookie: PHPSESSID=...               │
│ Body: {"success":true,"redirect":"..."}  │
└─────────────────────────────────────────┘
```

---

## Hierarquia de Controllers

```
AbstractController (base)
    │
    ├── AuthController
    │   ├── create()      → GET /login
    │   ├── store()       → POST /auth
    │   └── delete()      → GET /logout
    │
    ├── AccountController
    │   ├── index()       → GET /contas
    │   ├── create()      → GET /contas/criar
    │   ├── show(id)      → GET /contas/{id}
    │   └── edit(id)      → GET /contas/{id}/editar
    │
    ├── TransactionController
    │   ├── index()       → GET /lancamentos
    │   ├── create()      → GET /lancamentos/criar
    │   ├── show(id)      → GET /lancamentos/{id}
    │   └── edit(id)      → GET /lancamentos/{id}/editar
    │
    ├── CategoryController
    │   ├── index()       → GET /categorias
    │   ├── create()      → GET /categorias/criar
    │   ├── show(id)      → GET /categorias/{id}
    │   └── edit(id)      → GET /categorias/{id}/editar
    │
    ├── DashboardController
    │   └── index()       → GET /dashboard
    │
    └── Api\*Controller
        ├── Api\AccountController
        │   ├── index()       → GET /api/accounts
        │   ├── store()       → POST /api/accounts
        │   ├── show(id)      → GET /api/accounts/{id}
        │   ├── update(id)    → PUT /api/accounts/{id}
        │   └── delete(id)    → DELETE /api/accounts/{id}
        │
        ├── Api\TransactionController
        │   ├── index()       → GET /api/transactions
        │   ├── store()       → POST /api/transactions
        │   ├── show(id)      → GET /api/transactions/{id}
        │   ├── update(id)    → PUT /api/transactions/{id}
        │   └── delete(id)    → DELETE /api/transactions/{id}
        │
        └── Api\CategoryController
            ├── index()       → GET /api/categories
            ├── store()       → POST /api/categories
            ├── show(id)      → GET /api/categories/{id}
            ├── update(id)    → PUT /api/categories/{id}
            └── delete(id)    → DELETE /api/categories/{id}
```

---

## Separação de Responsabilidades

```
┌──────────────────────────────────────────────────────────────┐
│                    CAMADAS                                   │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  PRESENTATION LAYER (Views)                                 │
│  ├── HTML Templates                                         │
│  └── JavaScript (Frontend)                                  │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  CONTROLLER LAYER                                           │
│  ├── AccountController (Views)                              │
│  ├── Api\AccountController (REST)                           │
│  ├── AuthController (Auth)                                  │
│  └── DashboardController (Dashboard)                        │
│                                                              │
│  Responsabilidade:                                          │
│  • Receber requisição                                       │
│  • Validar entrada                                          │
│  • Delegar para Service                                     │
│  • Retornar resposta                                        │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  SERVICE LAYER (Lógica de Negócio)                          │
│  ├── AccountService                                         │
│  ├── TransactionService                                     │
│  ├── CategoryService                                        │
│  └── AuthService                                            │
│                                                              │
│  Responsabilidade:                                          │
│  • Implementar regras de negócio                            │
│  • Validar dados                                            │
│  • Orquestrar operações                                     │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  REPOSITORY LAYER (Acesso a Dados)                          │
│  ├── AccountRepository                                      │
│  ├── TransactionRepository                                  │
│  ├── CategoryRepository                                     │
│  └── UserRepository                                         │
│                                                              │
│  Responsabilidade:                                          │
│  • Consultar banco de dados                                 │
│  • Persistir dados                                          │
│  • Mapear dados para Models                                 │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  MODEL LAYER (Entidades)                                    │
│  ├── Account                                                │
│  ├── Transaction                                            │
│  ├── Category                                               │
│  └── User                                                   │
│                                                              │
│  Responsabilidade:                                          │
│  • Representar entidades                                    │
│  • Validação de dados                                       │
│  • Lógica de domínio                                        │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  DATABASE LAYER                                             │
│  └── MySQL / PostgreSQL                                     │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

---

## Fluxo Completo: Criar Conta

```
┌─────────────────────────────────────────────────────────────┐
│ 1. FRONTEND                                                 │
│    fetch('/api/accounts', {                                 │
│      method: 'POST',                                        │
│      body: JSON.stringify({name: 'Nova', type: 'checking'}) │
│    })                                                       │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│ 2. ROUTER                                                   │
│    POST /api/accounts → Api\AccountController::store()      │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│ 3. CONTROLLER                                               │
│    Api\AccountController::store()                           │
│    • requireAuth()                                          │
│    • $data = $this->request->json()                         │
│    • Validar dados                                          │
│    • $account = $this->service->create($data)              │
│    • json(['success'=>true, 'data'=>$account])             │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│ 4. SERVICE                                                  │
│    AccountService::create($data)                            │
│    • Validar regras de negócio                              │
│    • $account = new Account($data)                          │
│    • $this->repository->save($account)                      │
│    • return $account                                        │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│ 5. REPOSITORY                                               │
│    AccountRepository::save($account)                        │
│    • INSERT INTO accounts (name, type, ...) VALUES (...)    │
│    • return $account com ID                                 │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│ 6. DATABASE                                                 │
│    INSERT INTO accounts ...                                 │
│    ✓ Conta criada com sucesso                               │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│ 7. RESPONSE                                                 │
│    HTTP 201 Created                                         │
│    Content-Type: application/json                           │
│    {                                                        │
│      "success": true,                                       │
│      "data": {                                              │
│        "id": 1,                                             │
│        "name": "Nova",                                      │
│        "type": "checking"                                   │
│      }                                                      │
│    }                                                        │
└─────────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│ 8. FRONTEND                                                 │
│    .then(r => r.json())                                     │
│    .then(data => {                                          │
│      if (data.success) {                                    │
│        // Atualizar lista de contas                         │
│        // Mostrar mensagem de sucesso                       │
│      }                                                      │
│    })                                                       │
└─────────────────────────────────────────────────────────────┘
```

---

## Comparação: Antes vs Depois

### ANTES (Problema)

```
IndexController
├── loginAction()
├── registerAction()
├── dashboardAction()
├── transactionsAction()
├── accountsAction()
└── categoriesAction()

UserController
├── registerAction()

AuthController
(vazio)

LogoutController
├── logoutAction()

Problemas:
❌ Múltiplas responsabilidades
❌ Sem padrão CRUD
❌ Acesso direto a $_SERVER, $_POST
❌ Difícil de testar
❌ Difícil de manter
```

### DEPOIS (Solução)

```
AuthController
├── create()      (GET /login)
├── store()       (POST /auth)
└── delete()      (GET /logout)

AccountController
├── index()       (GET /contas)
├── create()      (GET /contas/criar)
├── show()        (GET /contas/{id})
└── edit()        (GET /contas/{id}/editar)

Api\AccountController
├── index()       (GET /api/accounts)
├── store()       (POST /api/accounts)
├── show()        (GET /api/accounts/{id})
├── update()      (PUT /api/accounts/{id})
└── delete()      (DELETE /api/accounts/{id})

TransactionController
├── index()       (GET /lancamentos)
├── create()      (GET /lancamentos/criar)
├── show()        (GET /lancamentos/{id})
└── edit()        (GET /lancamentos/{id}/editar)

Api\TransactionController
├── index()       (GET /api/transactions)
├── store()       (POST /api/transactions)
├── show()        (GET /api/transactions/{id})
├── update()      (PUT /api/transactions/{id})
└── delete()      (DELETE /api/transactions/{id})

CategoryController
├── index()       (GET /categorias)
├── create()      (GET /categorias/criar)
├── show()        (GET /categorias/{id})
└── edit()        (GET /categorias/{id}/editar)

Api\CategoryController
├── index()       (GET /api/categories)
├── store()       (POST /api/categories)
├── show()        (GET /api/categories/{id})
├── update()      (PUT /api/categories/{id})
└── delete()      (DELETE /api/categories/{id})

DashboardController
└── index()       (GET /dashboard)

Benefícios:
✅ Uma responsabilidade por controller
✅ Padrão CRUD consistente
✅ Sem acesso direto a superglobals
✅ Fácil de testar
✅ Fácil de manter
✅ Escalável
```

---

## Conclusão

A arquitetura refatorada estabelece uma separação clara de responsabilidades, facilitando manutenção, testes e escalabilidade do projeto.
