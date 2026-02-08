# API Contracts - Facilite

Documentação oficial dos contratos entre Frontend e Backend.
**Tá documentado aqui, eu segui isso.**

---

## 1. POST /auth - Login

**Descrição:** Autentica usuário com email e senha.

**Autenticação:** Não requerida

**Request:**
```json
{
  "email": "usuario@example.com",
  "password": "senha123"
}
```

**Headers:**
```
Content-Type: application/json
```

**Response - Sucesso (200):**
```json
{
  "success": true,
  "message": "Login realizado com sucesso"
}
```

**Response - Erro (401):**
```json
{
  "success": false,
  "message": "Email ou senha incorretos"
}
```

**Response - Erro (400):**
```json
{
  "success": false,
  "message": "Email e senha são obrigatórios"
}
```

**Status Codes:**
- `200` - Login bem-sucedido, sessão criada
- `400` - Campos obrigatórios faltando
- `401` - Credenciais inválidas
- `405` - Método não permitido (apenas POST)
- `500` - Erro no servidor

**Comportamento Frontend:**
- Valida email e senha antes de enviar
- Exibe "Entrando..." no botão durante requisição
- Se sucesso: redireciona para `/dashboard` após 1.5s
- Se erro: exibe mensagem de erro por 5s

**Implementação:**
- Hoje: Mock com `teste@example.com` / `senha123`
- Amanhã: Douglas integra com banco via `UserRepository`

---

## 2. POST /register - Cadastro

**Descrição:** Cria nova conta de usuário.

**Autenticação:** Não requerida

**Request:**
```json
{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "senha123"
}
```

**Headers:**
```
Content-Type: application/json
```

**Response - Sucesso (200):**
```json
{
  "success": true,
  "message": "Conta criada com sucesso"
}
```

**Response - Erro (400):**
```json
{
  "success": false,
  "message": "Todos os campos são obrigatórios"
}
```

**Status Codes:**
- `200` - Conta criada, sessão iniciada
- `400` - Campos obrigatórios faltando
- `500` - Erro no servidor

**Validações Frontend (antes de enviar):**
- Nome: não vazio
- Email: formato válido (regex: `/^[^\s@]+@[^\s@]+\.[^\s@]+$/`)
- Senha: mínimo 6 caracteres
- Confirmação: deve ser igual à senha

**Comportamento Frontend:**
- Exibe "Criando conta..." no botão durante requisição
- Se sucesso: exibe mensagem de sucesso e redireciona para `/login` após 2s
- Se erro: exibe mensagem de erro por 5s

**Implementação:**
- Hoje: Mock, cria sessão automaticamente
- Amanhã: Douglas valida email único e salva no banco

---

## 3. GET /api/dashboard/summary - Resumo do Dashboard

**Descrição:** Retorna dados resumidos do dashboard do usuário autenticado.

**Autenticação:** Obrigatória (middleware RequireAuth)

**Request:**
```
GET /api/dashboard/summary
```

**Headers:**
```
Content-Type: application/json
```

**Response - Sucesso (200):**
```json
{
  "success": true,
  "data": {
    "month": "Fevereiro",
    "income": 5200.00,
    "expenses": 3100.00,
    "balance": 2100.00
  }
}
```

**Response - Erro (401):**
```json
{
  "success": false,
  "message": "Não autenticado"
}
```

**Status Codes:**
- `200` - Dados retornados com sucesso
- `401` - Não autenticado (middleware redireciona para /login)
- `500` - Erro no servidor

**Tipos de Dados:**
- `month`: string (nome do mês em português)
- `income`: float (valores em BRL, 2 casas decimais)
- `expenses`: float (valores em BRL, 2 casas decimais)
- `balance`: float (income - expenses)

**Comportamento Frontend:**
- Exibe "Carregando…" nos cards enquanto aguarda resposta
- Se sucesso: formata valores como moeda BRL e exibe nos cards
- Se erro: exibe mensagem "Erro ao carregar dados" em box vermelho
- Timeout: se não responder em 30s, exibe erro genérico

**Implementação:**
- Hoje: Mock em `DashboardSummaryService::getSummary()`
- Amanhã: Douglas integra com `DashboardRepository` para buscar dados reais

---

## 4. GET /logout - Logout

**Descrição:** Encerra sessão do usuário autenticado.

**Autenticação:** Obrigatória (middleware RequireAuth)

**Request:**
```
GET /logout
```

**Response:**
- Redireciona para `/login` (HTTP 302)

**Status Codes:**
- `302` - Redirecionamento bem-sucedido
- `401` - Não autenticado

**Comportamento Frontend:**
- Botão "Sair" no dashboard
- Pede confirmação: "Tem certeza que deseja sair?"
- Se confirmado: redireciona para `/logout`
- Middleware limpa sessão e redireciona para `/login`

**Implementação:**
- `LogoutController::logoutAction()` limpa `AuthSession`

---

## 5. GET / - Redirecionamento Inicial

**Descrição:** Rota raiz que redireciona baseado em autenticação.

**Autenticação:** Não requerida

**Comportamento:**
- Se autenticado: redireciona para `/dashboard`
- Se não autenticado: redireciona para `/login`

**Status Codes:**
- `302` - Redirecionamento bem-sucedido

**Implementação:**
- `AuthController::indexAction()` valida `AuthSession::has()`

---

## 6. GET /login - Página de Login

**Descrição:** Renderiza formulário de login.

**Autenticação:** Não requerida

**Comportamento:**
- Se autenticado: redireciona para `/dashboard`
- Se não autenticado: renderiza formulário

**Implementação:**
- `IndexController::loginAction()` renderiza `src/View/index/login.php`

---

## 7. GET /register - Página de Cadastro

**Descrição:** Renderiza formulário de cadastro.

**Autenticação:** Não requerida

**Comportamento:**
- Renderiza formulário de cadastro

**Implementação:**
- `UserController::registerAction()` renderiza `src/View/index/register.php`

---

## 8. GET /dashboard - Dashboard

**Descrição:** Renderiza página do dashboard.

**Autenticação:** Obrigatória (middleware RequireAuth)

**Comportamento:**
- Se não autenticado: redireciona para `/login`
- Se autenticado: renderiza dashboard

**Implementação:**
- `IndexController::dashboardAction()` renderiza `src/View/index/dashboard.php`
- JS carrega dados via `/api/dashboard/summary`

---

## Arquitetura de Serviços

### Fluxo de Dados

```
Frontend (JS/HTML)
    ↓ fetch JSON
API Controller
    ↓
Service (lógica de negócio)
    ↓
Repository (acesso a dados) ← Será criado quando Douglas quiser
    ↓
Database
```

### Responsabilidades

- **Controller**: Recebe request, valida autenticação, chama service, retorna JSON
- **Service**: Lógica de negócio, orquestra dados, sem acesso a `$_SESSION`
- **Repository**: Acesso ao banco (não existe ainda)
- **Middleware**: Valida autenticação antes de chamar controller

### Quando o Banco Entrar

1. Douglas cria `src/Repository/UserRepository.php` e `DashboardRepository.php`
2. Implementa métodos de busca e inserção
3. Atualiza Services para usar repositories
4. **Nenhuma alteração no Controller ou Frontend** (Diorge não mexe em nada)

---

## Padrões de Resposta

### Sucesso
```json
{
  "success": true,
  "data": { /* dados específicos */ },
  "message": "Descrição opcional"
}
```

### Erro
```json
{
  "success": false,
  "message": "Descrição do erro"
}
```

---

## Segurança

- **Sessão:** Gerenciada por `AuthSession` (não acesse `$_SESSION` direto)
- **Autenticação:** Middleware `RequireAuth` protege rotas
- **CSRF:** Não implementado (adicionar quando necessário)
- **Validação:** Frontend valida, backend valida também

---

## Versionamento

- **Versão Atual:** 1.0.0 (MVP)
- **Status:** Em desenvolvimento
- **Última Atualização:** Fevereiro 2026

---

**Desenvolvido com** ☕ **e** 😤 **por um time que sabe o que tá fazendo.**
