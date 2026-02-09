# Estrutura de Controllers Refatorada

## Visão Geral

```
src/Controller/
├── AbstractController.php          ← Base com métodos auxiliares
├── AuthController.php              ← Autenticação (login, logout)
├── AccountController.php           ← Views de contas
├── TransactionController.php       ← Views de lançamentos
├── CategoryController.php          ← Views de categorias
├── DashboardController.php         ← Dashboard
└── Api/
    ├── AccountController.php       ← API REST de contas
    ├── TransactionController.php   ← API REST de lançamentos
    └── CategoryController.php      ← API REST de categorias
```

---

## Controllers por Domínio

### 1. AuthController
**Responsabilidade**: Autenticação e autorização

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `create()` | GET | `/login` | Exibir formulário de login |
| `store()` | POST | `/auth` | Processar login |
| `delete()` | GET | `/logout` | Fazer logout |

**Exemplo de Uso**:
```php
$auth = new AuthController();
$auth->create();  // Renderiza formulário
$auth->store();   // Processa credenciais
$auth->delete();  // Limpa sessão
```

---

### 2. AccountController (Views)
**Responsabilidade**: Renderizar views de contas

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `index()` | GET | `/contas` | Listar todas as contas |
| `create()` | GET | `/contas/criar` | Formulário novo |
| `show(id)` | GET | `/contas/{id}` | Detalhes de uma conta |
| `edit(id)` | GET | `/contas/{id}/editar` | Formulário edição |

**Exemplo de Uso**:
```php
$accounts = new AccountController();
$accounts->index();      // Lista contas
$accounts->create();     // Formulário novo
$accounts->show(1);      // Detalhes da conta 1
$accounts->edit(1);      // Editar conta 1
```

---

### 3. Api/AccountController (REST)
**Responsabilidade**: API REST para contas

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `index()` | GET | `/api/accounts` | Listar todas (JSON) |
| `store()` | POST | `/api/accounts` | Criar nova (JSON) |
| `show(id)` | GET | `/api/accounts/{id}` | Detalhes (JSON) |
| `update(id)` | PUT | `/api/accounts/{id}` | Atualizar (JSON) |
| `delete(id)` | DELETE | `/api/accounts/{id}` | Deletar (JSON) |

**Exemplo de Uso**:
```php
$api = new Api\AccountController();
$api->index();        // GET /api/accounts
$api->store();        // POST /api/accounts
$api->show(1);        // GET /api/accounts/1
$api->update(1);      // PUT /api/accounts/1
$api->delete(1);      // DELETE /api/accounts/1
```

---

### 4. TransactionController (Views)
**Responsabilidade**: Renderizar views de lançamentos

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `index()` | GET | `/lancamentos` | Listar todos |
| `create()` | GET | `/lancamentos/criar` | Formulário novo |
| `show(id)` | GET | `/lancamentos/{id}` | Detalhes |
| `edit(id)` | GET | `/lancamentos/{id}/editar` | Formulário edição |

---

### 5. Api/TransactionController (REST)
**Responsabilidade**: API REST para lançamentos

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `index()` | GET | `/api/transactions` | Listar todas (JSON) |
| `store()` | POST | `/api/transactions` | Criar nova (JSON) |
| `show(id)` | GET | `/api/transactions/{id}` | Detalhes (JSON) |
| `update(id)` | PUT | `/api/transactions/{id}` | Atualizar (JSON) |
| `delete(id)` | DELETE | `/api/transactions/{id}` | Deletar (JSON) |

---

### 6. CategoryController (Views)
**Responsabilidade**: Renderizar views de categorias

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `index()` | GET | `/categorias` | Listar todas |
| `create()` | GET | `/categorias/criar` | Formulário novo |
| `show(id)` | GET | `/categorias/{id}` | Detalhes |
| `edit(id)` | GET | `/categorias/{id}/editar` | Formulário edição |

---

### 7. Api/CategoryController (REST)
**Responsabilidade**: API REST para categorias

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `index()` | GET | `/api/categories` | Listar todas (JSON) |
| `store()` | POST | `/api/categories` | Criar nova (JSON) |
| `show(id)` | GET | `/api/categories/{id}` | Detalhes (JSON) |
| `update(id)` | PUT | `/api/categories/{id}` | Atualizar (JSON) |
| `delete(id)` | DELETE | `/api/categories/{id}` | Deletar (JSON) |

---

### 8. DashboardController
**Responsabilidade**: Renderizar dashboard

| Método | HTTP | Rota | Descrição |
|--------|------|------|-----------|
| `index()` | GET | `/dashboard` | Exibir dashboard |

---

## Padrão de Métodos

### Views Controllers (AccountController, TransactionController, CategoryController)

```php
public function index(): void
{
    $this->requireAuth();
    $this->render('accounts/index');
}

public function create(): void
{
    $this->requireAuth();
    $this->render('accounts/form', ['mode' => 'create']);
}

public function show(int $id): void
{
    $this->requireAuth();
    $this->render('accounts/show', ['id' => $id]);
}

public function edit(int $id): void
{
    $this->requireAuth();
    $this->render('accounts/form', ['mode' => 'edit', 'id' => $id]);
}
```

### API Controllers (Api/AccountController, Api/TransactionController, Api/CategoryController)

```php
public function index(): void
{
    $this->requireAuth();
    $data = $this->service->getAll();
    $this->json(['success' => true, 'data' => $data]);
}

public function store(): void
{
    $this->requireAuth();
    $data = $this->request->json();
    
    // Validação
    if (!$this->validate($data)) {
        http_response_code(400);
        $this->json(['success' => false, 'errors' => $this->errors]);
        return;
    }
    
    $result = $this->service->create($data);
    http_response_code(201);
    $this->json(['success' => true, 'data' => $result]);
}

public function show(int $id): void
{
    $this->requireAuth();
    $data = $this->service->getById($id);
    
    if (!$data) {
        http_response_code(404);
        $this->json(['success' => false, 'message' => 'Não encontrado']);
        return;
    }
    
    $this->json(['success' => true, 'data' => $data]);
}

public function update(int $id): void
{
    $this->requireAuth();
    $data = $this->request->json();
    
    $result = $this->service->update($id, $data);
    $this->json(['success' => true, 'data' => $result]);
}

public function delete(int $id): void
{
    $this->requireAuth();
    $this->service->delete($id);
    $this->json(['success' => true, 'message' => 'Deletado com sucesso']);
}
```

---

## Fluxo de Requisição

### Exemplo: Criar uma Conta

**Frontend (JavaScript)**:
```javascript
fetch('/api/accounts', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ name: 'Conta Nova', type: 'checking' })
})
.then(r => r.json())
.then(data => console.log(data));
```

**Backend (Router)**:
```php
'/api/accounts' => [
    'controller' => Api\AccountController::class,
    'action' => 'store',
    'middleware' => [RequireAuth::class]
]
```

**Backend (Controller)**:
```php
public function store(): void
{
    $this->requireAuth();  // Verifica autenticação
    
    $data = $this->request->json();  // Obtém JSON
    
    // Valida
    if (empty($data['name'])) {
        http_response_code(400);
        $this->json(['success' => false, 'errors' => ['name' => 'Obrigatório']]);
        return;
    }
    
    // Delega para service
    $account = $this->accountService->create($data);
    
    // Retorna JSON
    http_response_code(201);
    $this->json(['success' => true, 'data' => $account]);
}
```

---

## Benefícios da Refatoração

✅ **Separação Clara**: Views e API em controllers diferentes  
✅ **Padrão CRUD**: Métodos nomeados uniformemente  
✅ **Sem Superglobals**: Tudo via `RequestHandler` e `AuthSession`  
✅ **Testável**: Cada controller pode ser testado isoladamente  
✅ **Escalável**: Fácil adicionar novos controllers  
✅ **Manutenível**: Código previsível e organizado  
✅ **Responsabilidade Única**: Cada método faz uma coisa bem
