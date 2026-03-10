const TransactionsPage = {
  store: null,
  editingId: null,
  fields: {
    date: { id: 'transaction-date', name: 'Data' },
    description: { id: 'transaction-description', name: 'Descrição' },
    category_id: { id: 'transaction-category', name: 'Categoria' },
    account_id: { id: 'transaction-account', name: 'Conta' },
    type: { id: 'transaction-type', name: 'Tipo' },
    value: { id: 'transaction-value', name: 'Valor' }
  },

  async init() {
    this.store = StateUtils.createStore([]);
    this.setupEventListeners();
    await this.loadTransactions();
  },

  // ─── API ─────────────────────────────────────────────────────────────────

  async loadTransactions() {
    try {
      const response = await fetch('/lancamentos', {
        headers: { 'Accept': 'application/json' }
      });
      if (!response.ok) throw new Error(`Erro ${response.status}`);

      const json = await response.json();
      const transactions = json.data ?? [];
      this.store = StateUtils.createStore(transactions);
    } catch (err) {
      console.error('Erro ao carregar lançamentos:', err);
      this.showError('Não foi possível carregar os lançamentos.');
    } finally {
      this.render();
    }
  },

  async loadFormData() {
    const response = await fetch('/lancamentos/form-data', {
      headers: { 'Accept': 'application/json' }
    });
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    const json = await response.json();
    return json.data ?? { accounts: [], categories: [] };
  },

  async createTransaction(data) {
    const response = await fetch('/lancamentos_create', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    const json = await response.json();
    return json.data;
  },

  async updateTransaction(id, data) {
    const response = await fetch(`/lancamentos/${id}_update`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    const json = await response.json();
    return json.data;
  },

  async deleteTransaction(id) {
    const response = await fetch(`/lancamentos/${id}_delete`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    });
    if (response.status === 204) return true;
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    return true;
  },

  // ─── Eventos ─────────────────────────────────────────────────────────────

  setupEventListeners() {
    const btnNew = document.getElementById('btn-new-transaction');
    const btnNewEmpty = document.getElementById('btn-new-transaction-empty');
    const form = document.getElementById('transaction-form');

    if (btnNew) btnNew.addEventListener('click', () => this.openModal());
    if (btnNewEmpty) btnNewEmpty.addEventListener('click', () => this.openModal());
    if (form) form.addEventListener('submit', (e) => this.handleFormSubmit(e));

    ModalUtils.setupDefaultListeners('transaction-modal', () => this.onModalClose());
  },

  // ─── Modal ───────────────────────────────────────────────────────────────

  async openModal(transactionId = null) {
    this.editingId = transactionId;

    // Carrega contas e categorias do banco antes de abrir
    try {
      const { accounts, categories } = await this.loadFormData();
      this.populateSelect('transaction-account', accounts, 'id', 'name');
      this.populateSelect('transaction-category', categories, 'id', 'name');
    } catch (err) {
      console.error('Erro ao carregar dados do formulário:', err);
      alert('Não foi possível carregar contas e categorias.');
      return;
    }

    const modalTitle = document.getElementById('modal-title');

    if (transactionId) {
      const transaction = this.store.findById(transactionId);
      if (transaction) {
        modalTitle.textContent = 'Editar Lançamento';
        document.getElementById('transaction-date').value = transaction.date;
        document.getElementById('transaction-description').value = transaction.description;
        document.getElementById('transaction-category').value = transaction.category_id;
        document.getElementById('transaction-account').value = transaction.account_id;
        document.getElementById('transaction-type').value = transaction.type;
        document.getElementById('transaction-value').value = transaction.value;
      }
    } else {
      modalTitle.textContent = 'Novo Lançamento';
      FormUtils.resetForm('transaction-form');
    }

    ModalUtils.open('transaction-modal');
    document.getElementById('transaction-date').focus();
  },

  populateSelect(selectId, items, valueKey, labelKey) {
    const select = document.getElementById(selectId);
    const currentValue = select.value;
    // Mantém apenas o primeiro option (placeholder)
    select.innerHTML = `<option value="">${select.options[0]?.text ?? 'Selecione'}</option>`;
    items.forEach(item => {
      const option = document.createElement('option');
      option.value = item[valueKey];
      option.textContent = item[labelKey];
      select.appendChild(option);
    });
    // Restaura valor selecionado ao editar
    if (currentValue) select.value = currentValue;
  },

  closeModal() {
    ModalUtils.close('transaction-modal');
  },

  onModalClose() {
    this.editingId = null;
    FormUtils.resetForm('transaction-form');
  },

  // ─── Formulário ──────────────────────────────────────────────────────────

  validateForm() {
    FormUtils.clearErrors('transaction-form');
    let isValid = true;
    isValid &= FormUtils.validateRequired(this.fields.date.id, this.fields.date.name);
    isValid &= FormUtils.validateRequired(this.fields.description.id, this.fields.description.name);
    isValid &= FormUtils.validateRequired(this.fields.category_id.id, this.fields.category_id.name);
    isValid &= FormUtils.validateRequired(this.fields.account_id.id, this.fields.account_id.name);
    isValid &= FormUtils.validateRequired(this.fields.type.id, this.fields.type.name);
    isValid &= FormUtils.validateNumber(this.fields.value.id, this.fields.value.name, 0);
    return isValid;
  },

  async handleFormSubmit(e) {
    e.preventDefault();
    if (!this.validateForm()) return;

    const formData = new FormData(document.getElementById('transaction-form'));
    const data = {
      date: formData.get('date'),
      description: formData.get('description'),
      category_id: parseInt(formData.get('category')),
      account_id: parseInt(formData.get('account')),
      type: formData.get('type'),
      value: parseFloat(formData.get('value'))
    };

    const submitBtn = document.querySelector('#transaction-form [type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Salvando...';

    try {
      if (this.editingId) {
        const updated = await this.updateTransaction(this.editingId, data);
        this.store.update(this.editingId, updated ?? { ...data, id: this.editingId });
      } else {
        const created = await this.createTransaction(data);
        this.store.add(created ?? data);
      }
      this.render();
      this.closeModal();
    } catch (err) {
      console.error('Erro ao salvar lançamento:', err);
      alert('Não foi possível salvar o lançamento. Tente novamente.');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Salvar Lançamento';
    }
  },

  // ─── Ações da lista ──────────────────────────────────────────────────────

  handleEdit(id) {
    this.openModal(id);
  },

  async handleDelete(id) {
    if (!confirm('Tem certeza que quer deletar este lançamento?')) return;
    try {
      await this.deleteTransaction(id);
      this.store.remove(id);
      this.render();
    } catch (err) {
      console.error('Erro ao deletar lançamento:', err);
      alert('Não foi possível deletar o lançamento. Tente novamente.');
    }
  },

  // ─── Render ──────────────────────────────────────────────────────────────

  render() {
    const transactions = this.store.getAll();
    const container = document.getElementById('transactions-container');
    const list = document.getElementById('transactions-list');
    const tbody = document.getElementById('transactions-tbody');

    if (!transactions || transactions.length === 0) {
      container.style.display = 'flex';
      list.style.display = 'none';
      return;
    }

    container.style.display = 'none';
    list.style.display = 'block';

    tbody.innerHTML = transactions.map(t => `
      <tr>
        <td class="transaction-date">${this.formatDate(t.date)}</td>
        <td class="transaction-description">${t.description}</td>
        <td><span class="transaction-category">${t.category_name ?? ''}</span></td>
        <td class="transaction-account">${t.account_name ?? ''}</td>
        <td class="transaction-type ${t.type}">${this.formatType(t.type)}</td>
        <td class="transaction-value ${t.type}">${this.formatCurrency(t.value)}</td>
        <td class="transaction-actions">
          <button class="btn-action" onclick="TransactionsPage.handleEdit(${t.id})">Editar</button>
          <button class="btn-action delete" onclick="TransactionsPage.handleDelete(${t.id})">Deletar</button>
        </td>
      </tr>
    `).join('');
  },

  // ─── Helpers ─────────────────────────────────────────────────────────────

  showError(message) {
    const container = document.getElementById('transactions-container');
    const list = document.getElementById('transactions-list');
    container.style.display = 'flex';
    list.style.display = 'none';
    container.innerHTML = `
      <article class="empty-state">
        <div class="empty-state-icon">⚠️</div>
        <h2 class="empty-state-title">Algo deu errado</h2>
        <p class="empty-state-message">${message}</p>
        <button class="btn btn-primary btn-large" onclick="TransactionsPage.loadTransactions()">
          Tentar novamente
        </button>
      </article>
    `;
  },

  formatDate(dateString) {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('pt-BR');
  },

  formatType(type) {
    return type === 'income' ? 'Entrada' : 'Saída';
  },

  formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
  }
};

document.addEventListener('DOMContentLoaded', () => TransactionsPage.init());