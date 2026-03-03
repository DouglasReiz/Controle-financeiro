const AccountsPage = {
  store: null,
  editingId: null,
  fields: {
    name: { id: 'account-name', name: 'Nome' },
    type: { id: 'account-type', name: 'Tipo' },
    institution: { id: 'account-institution', name: 'Instituição' },
    balance: { id: 'account-balance', name: 'Saldo' }
  },

  async init() {
    this.store = StateUtils.createStore([]);
    this.setupEventListeners();
    await this.loadAccounts();
  },

  // ─── API ───────────────────────────────────────────────────────────────────

  async loadAccounts() {
    try {
      const response = await fetch('/contas', {
        headers: { 'Accept': 'application/json' }
      });

      if (!response.ok) throw new Error(`Erro ${response.status}`);

      const json = await response.json();
      // Resposta: { success: true, data: { data: [...] } }
      const accounts = json.data?.data ?? json.data ?? [];
      this.store = StateUtils.createStore(accounts);
    } catch (err) {
      console.error('Erro ao carregar contas:', err);
      this.showError('Não foi possível carregar as contas.');
    } finally {
      this.render();
    }
  },

  async createAccount(accountData) {
    const response = await fetch('/contas_create', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(accountData)
    });

    if (!response.ok) throw new Error(`Erro ${response.status}`);
    const json = await response.json();
    // AbstractController->respondCreated retorna { success: true, data: {...} }
    return json.data;
  },

  async updateAccount(id, accountData) {
    const response = await fetch(`/contas/${id}_update`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(accountData)
    });

    if (!response.ok) throw new Error(`Erro ${response.status}`);
    const json = await response.json();
    // AbstractController->respondSuccess retorna { success: true, data: { data: {...} } }
    return json.data?.data ?? json.data;
  },

  async deleteAccount(id) {
    const response = await fetch(`/contas/${id}_delete`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    });

    // respondNoContent retorna 204 sem body
    if (response.status === 204) return true;
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    return true;
  },

  // ─── Eventos ───────────────────────────────────────────────────────────────

  setupEventListeners() {
    const btnNewAccount = document.getElementById('btn-new-account');
    const btnNewAccountEmpty = document.getElementById('btn-new-account-empty');
    const accountForm = document.getElementById('account-form');

    if (btnNewAccount) btnNewAccount.addEventListener('click', () => this.openModal());
    if (btnNewAccountEmpty) btnNewAccountEmpty.addEventListener('click', () => this.openModal());
    if (accountForm) accountForm.addEventListener('submit', (e) => this.handleFormSubmit(e));

    ModalUtils.setupDefaultListeners('account-modal', () => this.onModalClose());
  },

  // ─── Modal ─────────────────────────────────────────────────────────────────

  openModal(accountId = null) {
    this.editingId = accountId;
    const modalTitle = document.getElementById('modal-title');

    if (accountId) {
      const account = this.store.findById(accountId);
      if (account) {
        modalTitle.textContent = 'Editar Conta';
        document.getElementById('account-name').value = account.name;
        document.getElementById('account-type').value = account.type;
        document.getElementById('account-institution').value = account.institution ?? '';
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

  // ─── Formulário ────────────────────────────────────────────────────────────

  validateForm() {
    FormUtils.clearErrors('account-form');
    let isValid = true;
    isValid &= FormUtils.validateRequired(this.fields.name.id, this.fields.name.name);
    isValid &= FormUtils.validateRequired(this.fields.type.id, this.fields.type.name);
    isValid &= FormUtils.validateRequired(this.fields.institution.id, this.fields.institution.name);
    isValid &= FormUtils.validateNumber(this.fields.balance.id, this.fields.balance.name, -Infinity);
    return isValid;
  },

  async handleFormSubmit(e) {
    e.preventDefault();
    if (!this.validateForm()) return;

    const formData = new FormData(document.getElementById('account-form'));
    const accountData = {
      name: formData.get('name'),
      type: formData.get('type'),
      institution: formData.get('institution'),
      balance: parseFloat(formData.get('balance'))
    };

    const submitBtn = document.querySelector('#account-form [type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Salvando...';

    try {
      if (this.editingId) {
        const updated = await this.updateAccount(this.editingId, accountData);
        this.store.update(this.editingId, updated ?? { ...accountData, id: this.editingId });
      } else {
        const created = await this.createAccount(accountData);
        this.store.add(created ?? accountData);
      }

      this.render();
      this.closeModal();
    } catch (err) {
      console.error('Erro ao salvar conta:', err);
      this.showError('Não foi possível salvar a conta. Tente novamente.');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Salvar Conta';
    }
  },

  // ─── Ações da lista ────────────────────────────────────────────────────────

  handleEdit(id) {
    this.openModal(id);
  },

  async handleDelete(id) {
    if (!confirm('Tem certeza que quer deletar esta conta?')) return;

    try {
      await this.deleteAccount(id);
      this.store.remove(id);
      this.render();
    } catch (err) {
      console.error('Erro ao deletar conta:', err);
      alert('Não foi possível deletar a conta. Tente novamente.');
    }
  },

  // ─── Render ────────────────────────────────────────────────────────────────

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
          <span>${account.institution ?? ''}</span>
          <span>${account.lastUpdate ? this.formatDate(account.lastUpdate) : ''}</span>
        </div>
        <div class="account-actions">
          <button class="btn-action" onclick="AccountsPage.handleEdit(${account.id})">Editar</button>
          <button class="btn-action delete" onclick="AccountsPage.handleDelete(${account.id})">Deletar</button>
        </div>
      </div>
    `).join('');
  },

  // ─── Helpers ───────────────────────────────────────────────────────────────

  showError(message) {
    const container = document.getElementById('accounts-container');
    const grid = document.getElementById('accounts-grid');
    container.style.display = 'flex';
    grid.style.display = 'none';
    container.innerHTML = `
      <article class="empty-state">
        <div class="empty-state-icon">⚠️</div>
        <h2 class="empty-state-title">Algo deu errado</h2>
        <p class="empty-state-message">${message}</p>
        <button class="btn btn-primary btn-large" onclick="AccountsPage.loadAccounts()">
          Tentar novamente
        </button>
      </article>
    `;
  },

  formatType(type) {
    const types = { checking: 'Corrente', savings: 'Poupança', credit: 'Crédito' };
    return types[type] || type;
  },

  formatDate(dateString) {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('pt-BR');
  },

  formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
  }
};

document.addEventListener('DOMContentLoaded', () => AccountsPage.init());