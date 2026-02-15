// Accounts Page - Frontend Only
// Mock temporário. Douglas, pode quebrar isso quando quiser

const AccountsPage = {
  store: null,
  editingId: null,

  fields: {
    name: { id: 'account-name', name: 'Nome' },
    type: { id: 'account-type', name: 'Tipo' },
    institution: { id: 'account-institution', name: 'Instituição' },
    balance: { id: 'account-balance', name: 'Saldo' }
  },

  init() {
    this.initializeStore();
    this.setupEventListeners();
    this.render();
  },

  initializeStore() {
    // PONTO DE ENTRADA DA API: Substituir por fetch GET /api/accounts
    // Hoje: dados em memória (mock)
    // Amanhã: const data = await fetch('/api/accounts').then(r => r.json());
    const initialData = [
      {
        id: 1,
        name: 'Conta Corrente',
        type: 'checking',
        balance: 3500.00,
        institution: 'Banco XYZ',
        lastUpdate: '2025-02-08'
      },
      {
        id: 2,
        name: 'Poupança',
        type: 'savings',
        balance: 8750.50,
        institution: 'Banco XYZ',
        lastUpdate: '2025-02-08'
      },
      {
        id: 3,
        name: 'Cartão de Crédito',
        type: 'credit',
        balance: -1200.00,
        institution: 'Banco ABC',
        lastUpdate: '2025-02-07'
      }
    ];

    this.store = StateUtils.createStore(initialData);
  },

  setupEventListeners() {
    const btnNewAccount = document.getElementById('btn-new-account');
    const btnNewAccountEmpty = document.getElementById('btn-new-account-empty');
    const accountForm = document.getElementById('account-form');

    if (btnNewAccount) {
      btnNewAccount.addEventListener('click', () => this.openModal());
    }

    if (btnNewAccountEmpty) {
      btnNewAccountEmpty.addEventListener('click', () => this.openModal());
    }

    if (accountForm) {
      accountForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    ModalUtils.setupDefaultListeners('account-modal', () => this.onModalClose());
  },

  openModal(accountId = null) {
    this.editingId = accountId;
    const modalTitle = document.getElementById('modal-title');

    if (accountId) {
      const account = this.store.findById(accountId);
      if (account) {
        modalTitle.textContent = 'Editar Conta';
        document.getElementById('account-name').value = account.name;
        document.getElementById('account-type').value = account.type;
        document.getElementById('account-institution').value = account.institution;
        document.getElementById('account-balance').value = account.balance;
      }
    } else {
      modalTitle.textContent = 'Nova Conta';
      FormUtils.resetForm('account-form');
    }

    ModalUtils.open('account-modal');
    document.getElementById('account-name').focus();
  },

  closeModal() {
    ModalUtils.close('account-modal');
  },

  onModalClose() {
    this.editingId = null;
    FormUtils.resetForm('account-form');
  },

  validateForm() {
    FormUtils.clearErrors('account-form');
    let isValid = true;

    isValid &= FormUtils.validateRequired(this.fields.name.id, this.fields.name.name);
    isValid &= FormUtils.validateRequired(this.fields.type.id, this.fields.type.name);
    isValid &= FormUtils.validateRequired(this.fields.institution.id, this.fields.institution.name);
    isValid &= FormUtils.validateNumber(this.fields.balance.id, this.fields.balance.name, -Infinity);

    return isValid;
  },

  handleFormSubmit(e) {
    e.preventDefault();

    if (!this.validateForm()) {
      return;
    }

    const formData = new FormData(document.getElementById('account-form'));
    const accountData = {
      name: formData.get('name'),
      type: formData.get('type'),
      institution: formData.get('institution'),
      balance: parseFloat(formData.get('balance')),
      lastUpdate: new Date().toISOString().split('T')[0]
    };

    // PONTO DE ENTRADA DA API
    // Hoje: atualizar estado em memória
    // Amanhã: POST /api/accounts (novo) ou PUT /api/accounts/:id (edição)
    if (this.editingId) {
      this.store.update(this.editingId, accountData);
      // await fetch(`/api/accounts/${this.editingId}`, { method: 'PUT', body: JSON.stringify(accountData) });
    } else {
      this.store.add(accountData);
      // await fetch('/api/accounts', { method: 'POST', body: JSON.stringify(accountData) });
    }

    this.render();
    this.closeModal();
  },

  handleEdit(id) {
    this.openModal(id);
  },

  handleDelete(id) {
    if (confirm('Tem certeza que quer deletar?')) {
      // PONTO DE ENTRADA DA API
      // Hoje: remover do estado em memória
      // Amanhã: DELETE /api/accounts/:id
      this.store.remove(id);
      // await fetch(`/api/accounts/${id}`, { method: 'DELETE' });
      
      this.render();
    }
  },

  render() {
    const accounts = this.store.getAll();
    const container = document.getElementById('accounts-container');
    const grid = document.getElementById('accounts-grid');
    const list = document.getElementById('accounts-list');

    if (!accounts || accounts.length === 0) {
      container.style.display = 'flex';
      grid.style.display = 'none';
      return;
    }

    container.style.display = 'none';
    grid.style.display = 'grid';

    list.innerHTML = accounts.map(account => `
      <div class="account-card ${account.type}">
        <div class="account-header">
          <h3 class="account-name">${account.name}</h3>
          <span class="account-type">${this.formatType(account.type)}</span>
        </div>

        <div class="account-balance">
          <div class="balance-label">Saldo</div>
          <div class="balance-value ${account.balance >= 0 ? 'positive' : 'negative'}">
            ${this.formatCurrency(account.balance)}
          </div>
        </div>

        <div class="account-meta">
          <span>${account.institution}</span>
          <span>${this.formatDate(account.lastUpdate)}</span>
        </div>

        <div class="account-actions">
          <button class="btn-action" onclick="AccountsPage.handleEdit(${account.id})">Editar</button>
          <button class="btn-action delete" onclick="AccountsPage.handleDelete(${account.id})">Deletar</button>
        </div>
      </div>
    `).join('');
  },

  formatType(type) {
    const types = {
      checking: 'Corrente',
      savings: 'Poupança',
      credit: 'Crédito'
    };
    return types[type] || type;
  },

  formatDate(dateString) {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('pt-BR');
  },

  formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(value);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  AccountsPage.init();
});
