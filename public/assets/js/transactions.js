// Transactions Page - Frontend Only
// Mock temporário. Douglas, pode quebrar isso quando quiser

const TransactionsPage = {
  store: null,
  editingId: null,

  fields: {
    date: { id: 'transaction-date', name: 'Data' },
    description: { id: 'transaction-description', name: 'Descrição' },
    category: { id: 'transaction-category', name: 'Categoria' },
    account: { id: 'transaction-account', name: 'Conta' },
    type: { id: 'transaction-type', name: 'Tipo' },
    value: { id: 'transaction-value', name: 'Valor' }
  },

  init() {
    this.initializeStore();
    this.setupEventListeners();
    this.render();
  },

  initializeStore() {
    // PONTO DE ENTRADA DA API: Substituir por fetch GET /api/transactions
    // Hoje: dados em memória (mock)
    // Amanhã: const data = await fetch('/api/transactions').then(r => r.json());
    const initialData = [
      {
        id: 1,
        date: '2025-02-08',
        description: 'Salário',
        category: 'Renda',
        account: 'Conta Corrente',
        type: 'income',
        value: 5000.00
      },
      {
        id: 2,
        date: '2025-02-07',
        description: 'Supermercado',
        category: 'Alimentação',
        account: 'Débito',
        type: 'expense',
        value: 250.50
      },
      {
        id: 3,
        date: '2025-02-06',
        description: 'Aluguel',
        category: 'Moradia',
        account: 'Conta Corrente',
        type: 'expense',
        value: 1500.00
      }
    ];

    this.store = StateUtils.createStore(initialData);
  },

  setupEventListeners() {
    const btnNewTransaction = document.getElementById('btn-new-transaction');
    const btnNewTransactionEmpty = document.getElementById('btn-new-transaction-empty');
    const transactionForm = document.getElementById('transaction-form');

    if (btnNewTransaction) {
      btnNewTransaction.addEventListener('click', () => this.openModal());
    }

    if (btnNewTransactionEmpty) {
      btnNewTransactionEmpty.addEventListener('click', () => this.openModal());
    }

    if (transactionForm) {
      transactionForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    ModalUtils.setupDefaultListeners('transaction-modal', () => this.onModalClose());
  },

  openModal(transactionId = null) {
    this.editingId = transactionId;
    const modalTitle = document.getElementById('modal-title');

    if (transactionId) {
      const transaction = this.store.findById(transactionId);
      if (transaction) {
        modalTitle.textContent = 'Editar Lançamento';
        document.getElementById('transaction-date').value = transaction.date;
        document.getElementById('transaction-description').value = transaction.description;
        document.getElementById('transaction-category').value = transaction.category;
        document.getElementById('transaction-account').value = transaction.account;
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

  closeModal() {
    ModalUtils.close('transaction-modal');
  },

  onModalClose() {
    this.editingId = null;
    FormUtils.resetForm('transaction-form');
  },

  validateForm() {
    FormUtils.clearErrors('transaction-form');
    let isValid = true;

    isValid &= FormUtils.validateRequired(this.fields.date.id, this.fields.date.name);
    isValid &= FormUtils.validateRequired(this.fields.description.id, this.fields.description.name);
    isValid &= FormUtils.validateRequired(this.fields.category.id, this.fields.category.name);
    isValid &= FormUtils.validateRequired(this.fields.account.id, this.fields.account.name);
    isValid &= FormUtils.validateRequired(this.fields.type.id, this.fields.type.name);
    isValid &= FormUtils.validateNumber(this.fields.value.id, this.fields.value.name, 0);

    return isValid;
  },

  handleFormSubmit(e) {
    e.preventDefault();

    if (!this.validateForm()) {
      return;
    }

    const formData = new FormData(document.getElementById('transaction-form'));
    const transactionData = {
      date: formData.get('date'),
      description: formData.get('description'),
      category: formData.get('category'),
      account: formData.get('account'),
      type: formData.get('type'),
      value: parseFloat(formData.get('value'))
    };

    // PONTO DE ENTRADA DA API
    // Hoje: atualizar estado em memória
    // Amanhã: POST /api/transactions (novo) ou PUT /api/transactions/:id (edição)
    if (this.editingId) {
      this.store.update(this.editingId, transactionData);
      // await fetch(`/api/transactions/${this.editingId}`, { method: 'PUT', body: JSON.stringify(transactionData) });
    } else {
      this.store.add(transactionData);
      // await fetch('/api/transactions', { method: 'POST', body: JSON.stringify(transactionData) });
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
      // Amanhã: DELETE /api/transactions/:id
      this.store.remove(id);
      // await fetch(`/api/transactions/${id}`, { method: 'DELETE' });
      
      this.render();
    }
  },

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

    tbody.innerHTML = transactions.map(transaction => `
      <tr>
        <td class="transaction-date">${this.formatDate(transaction.date)}</td>
        <td class="transaction-description">${transaction.description}</td>
        <td><span class="transaction-category">${transaction.category}</span></td>
        <td class="transaction-account">${transaction.account}</td>
        <td class="transaction-type ${transaction.type}">${this.formatType(transaction.type)}</td>
        <td class="transaction-value ${transaction.type}">${this.formatCurrency(transaction.value)}</td>
        <td class="transaction-actions">
          <button class="btn-action" onclick="TransactionsPage.handleEdit(${transaction.id})">Editar</button>
          <button class="btn-action delete" onclick="TransactionsPage.handleDelete(${transaction.id})">Deletar</button>
        </td>
      </tr>
    `).join('');
  },

  formatDate(dateString) {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('pt-BR');
  },

  formatType(type) {
    return type === 'income' ? 'Entrada' : 'Saída';
  },

  formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(value);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  TransactionsPage.init();
});
