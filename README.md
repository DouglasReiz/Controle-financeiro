# Facilite - Controle Financeiro

Sistema de controle financeiro pessoal desenvolvido em PHP com arquitetura MVC. Simples, direto e sem gambiarras.

## Estrutura do Projeto

```
Controle-financeiro/
├── public/
│   ├── index.php                     # Front controller
│   └── assets/                       # CSS, JS, imagens
├── src/
│   ├── Controller/
│   │   ├── AbstractController.php    # Base com helpers de resposta HTTP
│   │   ├── AuthController.php        # Login/logout
│   │   ├── AccountController.php     # CRUD de contas
│   │   ├── TransactionController.php # CRUD de lançamentos
│   │   └── CategoryController.php    # CRUD de categorias
│   ├── Service/
│   │   ├── AuthSession.php           # Persistência de sessão
│   │   ├── AuthUser.php              # Identidade do usuário
│   │   ├── AccountService.php        # Regras de contas
│   │   ├── TransactionService.php    # Regras de lançamentos
│   │   └── CategoryService.php       # Regras de categorias
│   ├── Repository/
│   │   ├── AccountRepository.php     # Queries de contas
│   │   ├── TransactionRepository.php # Queries de lançamentos
│   │   └── CategoryRepository.php    # Queries de categorias
│   ├── Middleware/
│   │   └── RequireAuth.php           # Valida autenticação
│   ├── View/
│   │   ├── accounts/
│   │   ├── transactions/
│   │   └── categories/
│   ├── Db/
│   │   └── Database.php              # Singleton PDO
│   └── Connection/                   # Credenciais do banco
├── config/
│   ├── routes.php                    # Todas as rotas
│   └── helpers.php
├── docs/
│   └── api.md
└── vendor/
```

## Como Rodar Localmente

```bash
php -S localhost:8000 -t public
```

Acesse `http://localhost:8000` no navegador.

## Banco de Dados

### Setup inicial

Execute os scripts abaixo **na ordem** para criar o schema:

```sql
-- 1. Categorias
CREATE TABLE IF NOT EXISTS categories (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    BIGINT UNSIGNED NOT NULL,
    nome       VARCHAR(50)     NOT NULL,
    tipo       VARCHAR(20)     NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 2. Contas
CREATE TABLE IF NOT EXISTS accounts (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NOT NULL,
    name        VARCHAR(100)    NOT NULL,
    type        ENUM('checking', 'savings', 'credit') NOT NULL,
    institution VARCHAR(100)    NOT NULL DEFAULT '',
    balance     DECIMAL(15, 2)  NOT NULL DEFAULT 0.00,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Lançamentos
CREATE TABLE IF NOT EXISTS transactions (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED NOT NULL,
    account_id  BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    description VARCHAR(150)    NOT NULL,
    value       DECIMAL(15, 2)  NOT NULL,
    type        ENUM('income', 'expense') NOT NULL,
    date        DATE            NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
    FOREIGN KEY (account_id)  REFERENCES accounts(id)   ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

> **Atenção:** Todos os `user_id` devem ser `BIGINT UNSIGNED` para compatibilidade com `users.id`. Se a tabela `categories` já existir com `user_id INT`, rode `ALTER TABLE categories MODIFY user_id BIGINT UNSIGNED;` antes de criar `transactions`.

## Arquitetura de Dados

```
Frontend (JS)
    ↓ fetch + Accept: application/json
Controller
    ↓
Service  (validação e regras de negócio)
    ↓
Repository  (queries SQL com PDO)
    ↓
Database (MySQL)
```

## Fluxo de Autenticação

1. **Usuário não logado** → Acessa `/` → Redireciona para `/login`
2. **Login** → Preenche form → Fetch para `/auth` → Cria sessão → Redireciona para `/dashboard`
3. **Páginas protegidas** → Middleware `RequireAuth` valida sessão → Renderiza página
4. **Logout** → Limpa sessão → Redireciona para `/login`

## Padrões do Projeto

**Formato de resposta JSON**
Todas as respostas seguem o padrão definido no `AbstractController`:
```json
{ "success": true, "data": {} }
```

**Segurança**
Todas as queries filtram por `user_id`, garantindo isolamento de dados entre usuários. Prepared statements em todas as queries, sem concatenação de strings SQL.

**Fetch no frontend**
Todas as requisições enviam `Accept: application/json` para acionar o `wantsJson()` do `RequestHandler`. Não é necessário prefixo `/api/` nas rotas.

**Regras gerais**
- Nunca acesse `$_SESSION` diretamente — use `AuthSession`
- O middleware cuida da autenticação — não valide manualmente em cada controller
- Controllers não contêm regras de negócio — delegue ao Service
- Services não acessam o banco diretamente — delegue ao Repository

## Rotas Disponíveis

### Contas
| Método | Rota | Ação |
|---|---|---|
| GET | `/contas` | Lista todas as contas |
| POST | `/contas_create` | Cria nova conta |
| PUT | `/contas/{id}_update` | Atualiza conta |
| DELETE | `/contas/{id}_delete` | Deleta conta |

### Lançamentos
| Método | Rota | Ação |
|---|---|---|
| GET | `/lancamentos` | Lista todos os lançamentos |
| GET | `/lancamentos/form-data` | Retorna contas e categorias para os selects |
| POST | `/lancamentos_create` | Cria novo lançamento |
| PUT | `/lancamentos/{id}_update` | Atualiza lançamento |
| DELETE | `/lancamentos/{id}_delete` | Deleta lançamento |

### Categorias
| Método | Rota | Ação |
|---|---|---|
| GET | `/categorias` | Lista todas as categorias |
| POST | `/categorias_create` | Cria nova categoria |
| PUT | `/categorias/{id}_update` | Atualiza categoria |
| DELETE | `/categorias/{id}_delete` | Deleta categoria |

## Próximos Passos

- [x] Integrar banco de dados
- [x] Criar camada Repository
- [x] CRUD de Contas
- [x] CRUD de Lançamentos
- [x] CRUD de Categorias
- [ ] Dashboard com resumo financeiro
- [ ] Expandir dashboard com gráficos
- [ ] Testes automatizados

## 👥 Equipe e Responsabilidades

| Função | Responsável | Atribuições |
|---|---|---|
| 🎨 **Designer** | **Diorge** | Layouts (Figma), UX/UI, design responsivo, identidade visual |
| 💻 **Front-end** | **Daniel** | HTML/CSS/JS, consumo de APIs, interatividade, acessibilidade |
| ⚙️ **Back-end** | **Douglas** | Lógica de negócio, APIs, banco de dados, segurança, autenticação |

### ⚠️ Orientações
- **Sincronia:** Comunique mudanças em contratos de API ou estrutura de layout antes de implementar
- **Git:** Respeite as áreas de atuação de cada membro para evitar conflitos de merge
- **UX:** O feedback do designer tem prioridade na implementação da interface