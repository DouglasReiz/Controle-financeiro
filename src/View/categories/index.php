<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Controle Financeiro</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/categories.css">
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
                    <li><a href="/contas" class="nav-link">Contas</a></li>
                    <li><a href="/categorias" class="nav-link active">Categorias</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="page-header">
                <h1>Categorias</h1>
                <button id="btn-new-category" class="btn btn-primary" data-action="create" data-resource="category" aria-label="Adicionar nova categoria">
                    + Nova Categoria
                </button>
            </header>

            <section class="content-area" data-page="categories">
                <div id="categories-container" class="categories-container" data-list="categories" data-state="empty">
                    <article class="empty-state">
                        <div class="empty-state-icon">🏷️</div>
                        <h2 class="empty-state-title">Nenhuma categoria criada</h2>
                        <p class="empty-state-message">Organize suas transações criando categorias personalizadas</p>
                        <button id="btn-new-category-empty" class="btn btn-primary btn-large" aria-label="Criar primeira categoria">
                            Criar primeira categoria
                        </button>
                    </article>
                </div>

                <div id="categories-grid" class="categories-grid" data-list="categories" data-state="loaded" style="display: none;">
                    <div id="categories-list" class="categories-list" data-items="category">
                    </div>
                </div>
            </section>

            <!-- Modal de Nova Categoria -->
            <div id="category-modal" class="modal" style="display: none;">
                <div class="modal-overlay"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 id="modal-title">Nova Categoria</h2>
                        <button type="button" data-modal-close class="modal-close" aria-label="Fechar modal">×</button>
                    </div>

                    <form id="category-form" class="category-form">
                        <div class="form-group">
                            <label for="category-name">Nome *</label>
                            <input type="text" id="category-name" name="name" placeholder="Ex: Alimentação, Transporte..." required>
                        </div>

                        <div class="form-group">
                            <label for="category-type">Tipo *</label>
                            <select id="category-type" name="type" required>
                                <option value="">Selecione o tipo</option>
                                <option value="income">Entrada</option>
                                <option value="expense">Saída</option>
                                <option value="transfer">Transferência</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="category-icon">Ícone *</label>
                            <input type="text" id="category-icon" name="icon" placeholder="Ex: 🍔, 🚗, 💰..." maxlength="2" required>
                        </div>

                        <div class="form-actions">
                            <button type="button" data-modal-cancel class="btn btn-secondary">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Categoria</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="/assets/js/modal-utils.js" defer></script>
    <script src="/assets/js/form-utils.js" defer></script>
    <script src="/assets/js/state-utils.js" defer></script>
    <script src="/assets/js/categories.js" defer></script>
</body>
</html>
