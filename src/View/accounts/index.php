<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas - Controle Financeiro</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/accounts.css">
    <link rel="stylesheet" href="/assets/css/empty-states.css">
    <link rel="stylesheet" href="/assets/css/modal.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1 class="app-name">Facilite</h1>
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Alternar menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/dashboard" class="nav-link">Dashboard</a></li>
                    <li><a href="/lancamentos" class="nav-link">Lançamentos</a></li>
                    <li><a href="/contas" class="nav-link active">Contas</a></li>
                    <li><a href="/categorias" class="nav-link">Categorias</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="page-header">
                <h1>Contas</h1>
                <button id="btn-new-account" class="btn btn-primary" data-action="create" data-resource="account" aria-label="Adicionar nova conta">
                    + Nova Conta
                </button>
            </header>

            <section class="content-area" data-page="accounts">
                <div id="accounts-container" class="accounts-container" data-list="accounts" data-state="empty">
                    <article class="empty-state">
                        <div class="empty-state-icon">🏦</div>
                        <h2 class="empty-state-title">Nenhuma conta registrada</h2>
                        <p class="empty-state-message">Crie sua primeira conta para começar a organizar suas finanças</p>
                        <button id="btn-new-account-empty" class="btn btn-primary btn-large" aria-label="Criar primeira conta">
                            Criar primeira conta
                        </button>
                    </article>
                </div>

                <div id="accounts-grid" class="accounts-grid" data-list="accounts" data-state="loaded" style="display: none;">
                    <div id="accounts-list" class="accounts-list" data-items="account">
                    </div>
                </div>
            </section>

            <!-- Modal de Nova Conta -->
            <div id="account-modal" class="modal" style="display: none;">
                <div class="modal-overlay"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 id="modal-title">Nova Conta</h2>
                        <button type="button" data-modal-close class="modal-close" aria-label="Fechar modal">×</button>
                    </div>

                    <form id="account-form" class="account-form">
                        <div class="form-group">
                            <label for="account-name">Nome *</label>
                            <input type="text" id="account-name" name="name" placeholder="Ex: Conta Corrente, Poupança..." required>
                        </div>

                        <div class="form-group">
                            <label for="account-type">Tipo *</label>
                            <select id="account-type" name="type" required>
                                <option value="">Selecione o tipo</option>
                                <option value="checking">Conta Corrente</option>
                                <option value="savings">Poupança</option>
                                <option value="credit">Cartão de Crédito</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="account-institution">Instituição *</label>
                            <input type="text" id="account-institution" name="institution" placeholder="Ex: Banco XYZ..." required>
                        </div>

                        <div class="form-group">
                            <label for="account-balance">Saldo Inicial *</label>
                            <input type="number" id="account-balance" name="balance" placeholder="0,00" step="0.01" required>
                        </div>

                        <div class="form-actions">
                            <button type="button" data-modal-cancel class="btn btn-secondary">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Conta</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="/assets/js/modal-utils.js" defer></script>
    <script src="/assets/js/form-utils.js" defer></script>
    <script src="/assets/js/state-utils.js" defer></script>
    <script src="/assets/js/accounts.js" defer></script>
</body>
</html>
