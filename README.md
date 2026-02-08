# Facilite - Controle Financeiro

Sistema de controle financeiro em PHP com arquitetura MVC. Simples, direto e sem gambiarras.

## Estrutura do Projeto

```
Controle-financeiro/
├── public/                    # Ponto de entrada (rotas públicas)
│   ├── index.php             # Front controller - orquestra tudo
│   └── assets/               # CSS, JS, imagens
├── src/
│   ├── Controller/           # Lógica de orquestração
│   │   ├── Api/              # Endpoints da API
│   │   ├── AuthController    # Login/logout
│   │   └── ...
│   ├── Service/              # Lógica de negócio
│   │   ├── AuthSession       # Persistência de sessão
│   │   ├── AuthUser          # Identidade do usuário
│   │   └── DashboardSummaryService
│   ├── Middleware/           # Proteção de rotas
│   │   └── RequireAuth       # Valida autenticação
│   ├── View/                 # Templates HTML
│   │   └── index/            # Páginas organizadas por tema
│   └── Connection/           # Banco de dados (quando entrar)
├── config/                   # Configurações centralizadas
│   ├── routes.php            # Todas as rotas em um lugar
│   └── helpers.php           # Funções auxiliares
├── docs/                     # Documentação
│   └── api.md               # Contratos da API
└── vendor/                   # Dependências (composer)
```

## Como Rodar Localmente

```bash
php -S localhost:8000 -t public
```

Depois acessa `http://localhost:8000` no navegador.

## Fluxo de Autenticação

1. **Usuário não logado** → Acessa `/` → Redireciona para `/login`
2. **Login** → Preenche form → Fetch para `/auth` → Cria sessão → Redireciona para `/dashboard`
3. **Dashboard** → Middleware valida sessão → Renderiza página → JS faz fetch em `/api/dashboard/summary`
4. **Logout** → Clica em "Sair" → Limpa sessão → Redireciona para `/login`

## Arquitetura de Dados

```
Frontend (JS)
    ↓ fetch
API Controller
    ↓
Service (lógica)
    ↓
Repository (banco) ← Será criado quando Douglas quiser
    ↓
Database
```

## Importante

- **Não acesse `$_SESSION` direto** - Use `AuthSession`
- **Middleware cuida da segurança** - Não precisa validar em cada controller
- **Comentários têm personalidade** - Leia pra entender o contexto
- **Mock é temporário** - Quando o banco entrar, tudo muda de uma vez

## Próximos Passos

- [ ] Integrar banco de dados (Douglas)
- [ ] Criar Repository para dados
- [ ] Adicionar mais endpoints da API
- [ ] Expandir dashboard com gráficos (Diorge)
- [ ] Testes automatizados

---

**Desenvolvido com** ☕ **e** 😤 **por um time que sabe o que tá fazendo.**
