document.addEventListener('DOMContentLoaded', function() {
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarNav = document.querySelector('.sidebar-nav');
  const logoutBtn = document.getElementById('logoutBtn');

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function() {
      sidebarNav.classList.toggle('active');
    });
  }

  const navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(link => {
    link.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        sidebarNav.classList.remove('active');
      }
    });
  });

  if (logoutBtn) {
    logoutBtn.addEventListener('click', function() {
      if (confirm('Tem certeza que deseja sair?')) {
        window.location.href = '/logout';
      }
    });
  }

  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      sidebarNav.classList.remove('active');
    }
  });

  loadDashboardData();
});

function loadDashboardData() {
  setLoadingState(true);

  // Diorge: Se o backend cair, a mensagem de erro já está pronta 😇
  fetch('/api/dashboard/summary')
    .then(response => {
      if (!response.ok) {
        throw new Error('Erro ao carregar dados');
      }
      return response.json();
    })
    .then(data => {
      if (data.success && data.data) {
        renderDashboard(data.data);
        setLoadingState(false);
      } else {
        showError('Erro ao carregar dados do dashboard');
        setLoadingState(false);
      }
    })
    .catch(error => {
      console.error('Erro:', error);
      showError('Erro ao conectar com o servidor');
      setLoadingState(false);
    });
}

function setLoadingState(isLoading) {
  const monthElement = document.getElementById('dashboard-month');
  const incomeElement = document.getElementById('dashboard-income');
  const expensesElement = document.getElementById('dashboard-expenses');
  const balanceElement = document.getElementById('dashboard-balance');

  const loadingText = 'Carregando…';

  if (isLoading) {
    if (monthElement) monthElement.textContent = loadingText;
    if (incomeElement) incomeElement.textContent = loadingText;
    if (expensesElement) expensesElement.textContent = loadingText;
    if (balanceElement) balanceElement.textContent = loadingText;
  }
}

function renderDashboard(data) {
  const monthElement = document.getElementById('dashboard-month');
  const incomeElement = document.getElementById('dashboard-income');
  const expensesElement = document.getElementById('dashboard-expenses');
  const balanceElement = document.getElementById('dashboard-balance');
  const errorElement = document.getElementById('errorMessage');

  if (errorElement) {
    errorElement.style.display = 'none';
  }

  if (monthElement) {
    monthElement.textContent = data.month || '-';
  }

  if (incomeElement) {
    incomeElement.textContent = formatCurrency(data.income);
  }

  if (expensesElement) {
    expensesElement.textContent = formatCurrency(data.expenses);
  }

  if (balanceElement) {
    balanceElement.textContent = formatCurrency(data.balance);
  }
}

function formatCurrency(value) {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(value);
}

function showError(message) {
  console.error(message);
  const errorElement = document.getElementById('errorMessage');
  if (errorElement) {
    errorElement.textContent = 'Erro ao carregar dados';
    errorElement.style.display = 'block';
  }
}
