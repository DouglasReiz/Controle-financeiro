<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facilite • Dashboard</title>
  <link rel="stylesheet" href="/assets/css/app.css">
  <link rel="stylesheet" href="/assets/css/dashboard.css">
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
          <li><a href="/dashboard" class="nav-link active">Dashboard</a></li>
          <li><a href="/lancamentos" class="nav-link">Lançamentos</a></li>
          <li><a href="/contas" class="nav-link">Contas</a></li>
          <li><a href="/categorias" class="nav-link">Categorias</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <header class="page-header">
        <h1>Dashboard</h1>
        <button class="logout-btn" id="logoutBtn">Sair</button>
      </header>

      <section class="content-area">
        <div class="dashboard-cards" id="dashboardCards">
          <article class="dashboard-card">
            <h3 class="card-title">Mês</h3>
            <p class="card-value" id="dashboard-month">Carregando…</p>
          </article>
          <article class="dashboard-card">
            <h3 class="card-title">Entradas</h3>
            <p class="card-value" id="dashboard-income">Carregando…</p>
          </article>
          <article class="dashboard-card">
            <h3 class="card-title">Saídas</h3>
            <p class="card-value" id="dashboard-expenses">Carregando…</p>
          </article>
          <article class="dashboard-card">
            <h3 class="card-title">Saldo</h3>
            <p class="card-value" id="dashboard-balance">Carregando…</p>
          </article>
        </div>
        <div class="error-message" id="errorMessage" style="display: none;"></div>
      </section>
    </main>
  </div>

  <script src="/assets/js/dashboard.js" defer></script>
</body>
</html>
