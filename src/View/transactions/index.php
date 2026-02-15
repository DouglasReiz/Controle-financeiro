<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lançamentos - Controle Financeiro</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/transactions.css">
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
                    <li><a href="/lancamentos" class="nav-link active">Lançamentos</a></li>
                    <li><a href="/contas" class="nav-link">Contas</a></li>
                    <li><a href="/categorias" class="nav-link">Categorias</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="page-header">
                <h1>Lançamentos</h1>
                <button id="btn-new-transaction" class="btn btn-primary" data-action="create" data-resource="transaction" aria-label="Adicionar novo lançamento">
                    + Novo Lançamento
                </button>
            </header>

            <section class="content-area" data-page="transactions">
                <div id="transactions-container" class="transactions-container" data-list="transactions" data-state="empty">
                    <article class="empty-state">
                        <div class="empty-state-icon">📊</div>
                        <h2 class="empty-state-title">Nenhum lançamento por aqui</h2>
                        <p class="empty-state-message">Comece a registrar suas transações para acompanhar suas finanças</p>
                        <button id="btn-new-transaction-empty" class="btn btn-primary btn-large" aria-label="Criar primeiro lançamento">
                            Criar primeiro lançamento
                        </button>
                    </article>
                </div>

                <div id="transactions-list" class="transactions-list" data-list="transactions" data-state="loaded" style="display: none;">
                    <table class="transactions-table" data-table="transactions">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Categoria</th>
                                <th>Conta</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="transactions-tbody" data-items="transaction">
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Modal de Novo Lançamento -->
            <div id="transaction-modal" class="modal" style="display: none;">
                <div class="modal-overlay"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 id="modal-title">Novo Lançamento</h2>
                        <button type="button" data-modal-close class="modal-close" aria-label="Fechar modal">×</button>
                    </div>

                    <form id="transaction-form" class="transaction-form">
                        <div class="form-group">
                            <label for="transaction-date">Data *</label>
                            <input type="date" id="transaction-date" name="date" required>
                        </div>

                        <div class="form-group">
                            <label for="transaction-description">Descrição *</label>
                            <input type="text" id="transaction-description" name="description" placeholder="Ex: Salário, Supermercado..." required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="transaction-category">Categoria *</label>
                                <select id="transaction-category" name="category" required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="Renda">Renda</option>
                                    <option value="Alimentação">Alimentação</option>
                                    <option value="Transporte">Transporte</option>
                                    <option value="Moradia">Moradia</option>
                                    <option value="Saúde">Saúde</option>
                                    <option value="Lazer">Lazer</option>
                                    <option value="Outros">Outros</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="transaction-account">Conta *</label>
                                <select id="transaction-account" name="account" required>
                                    <option value="">Selecione uma conta</option>
                                    <option value="Conta Corrente">Conta Corrente</option>
                                    <option value="Poupança">Poupança</option>
                                    <option value="Débito">Débito</option>
                                    <option value="Crédito">Crédito</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="transaction-type">Tipo *</label>
                                <select id="transaction-type" name="type" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="income">Entrada</option>
                                    <option value="expense">Saída</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="transaction-value">Valor *</label>
                                <input type="number" id="transaction-value" name="value" placeholder="0,00" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" data-modal-cancel class="btn btn-secondary">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Lançamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="/assets/js/modal-utils.js" defer></script>
    <script src="/assets/js/form-utils.js" defer></script>
    <script src="/assets/js/state-utils.js" defer></script>
    <script src="/assets/js/transactions.js" defer></script>
</body>
</html>
