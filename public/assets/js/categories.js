// Categories Page - Frontend Only
// Mock temporário. Douglas, pode quebrar isso quando quiser

const CategoriesPage = {
  store: null,
  editingId: null,

  fields: {
    name: { id: 'category-name', name: 'Nome' },
    type: { id: 'category-type', name: 'Tipo' },
    icon: { id: 'category-icon', name: 'Ícone' }
  },

  init() {
    this.initializeStore();
    this.setupEventListeners();
    this.render();
  },

  initializeStore() {
    // PONTO DE ENTRADA DA API: Substituir por fetch GET /api/categories
    // Hoje: dados em memória (mock)
    // Amanhã: const data = await fetch('/api/categories').then(r => r.json());
    const initialData = [
      {
        id: 1,
        name: 'Alimentação',
        type: 'expense',
        icon: '🍔',
        transactionCount: 12,
        totalSpent: 850.00
      },
      {
        id: 2,
        name: 'Transporte',
        type: 'expense',
        icon: '🚗',
        transactionCount: 8,
        totalSpent: 320.00
      },
      {
        id: 3,
        name: 'Salário',
        type: 'income',
        icon: '💰',
        transactionCount: 1,
        totalSpent: 5000.00
      },
      {
        id: 4,
        name: 'Moradia',
        type: 'expense',
        icon: '🏠',
        transactionCount: 1,
        totalSpent: 1500.00
      },
      {
        id: 5,
        name: 'Saúde',
        type: 'expense',
        icon: '⚕️',
        transactionCount: 3,
        totalSpent: 450.00
      },
      {
        id: 6,
        name: 'Lazer',
        type: 'expense',
        icon: '🎬',
        transactionCount: 5,
        totalSpent: 280.00
      }
    ];

    this.store = StateUtils.createStore(initialData);
  },

  setupEventListeners() {
    const btnNewCategory = document.getElementById('btn-new-category');
    const btnNewCategoryEmpty = document.getElementById('btn-new-category-empty');
    const categoryForm = document.getElementById('category-form');

    if (btnNewCategory) {
      btnNewCategory.addEventListener('click', () => this.openModal());
    }

    if (btnNewCategoryEmpty) {
      btnNewCategoryEmpty.addEventListener('click', () => this.openModal());
    }

    if (categoryForm) {
      categoryForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    ModalUtils.setupDefaultListeners('category-modal', () => this.onModalClose());
  },

  openModal(categoryId = null) {
    this.editingId = categoryId;
    const modalTitle = document.getElementById('modal-title');

    if (categoryId) {
      const category = this.store.findById(categoryId);
      if (category) {
        modalTitle.textContent = 'Editar Categoria';
        document.getElementById('category-name').value = category.name;
        document.getElementById('category-type').value = category.type;
        document.getElementById('category-icon').value = category.icon;
      }
    } else {
      modalTitle.textContent = 'Nova Categoria';
      FormUtils.resetForm('category-form');
    }

    ModalUtils.open('category-modal');
    document.getElementById('category-name').focus();
  },

  closeModal() {
    ModalUtils.close('category-modal');
  },

  onModalClose() {
    this.editingId = null;
    FormUtils.resetForm('category-form');
  },

  validateForm() {
    FormUtils.clearErrors('category-form');
    let isValid = true;

    isValid &= FormUtils.validateRequired(this.fields.name.id, this.fields.name.name);
    isValid &= FormUtils.validateRequired(this.fields.type.id, this.fields.type.name);
    isValid &= FormUtils.validateRequired(this.fields.icon.id, this.fields.icon.name);

    return isValid;
  },

  handleFormSubmit(e) {
    e.preventDefault();

    if (!this.validateForm()) {
      return;
    }

    const formData = new FormData(document.getElementById('category-form'));
    const categoryData = {
      name: formData.get('name'),
      type: formData.get('type'),
      icon: formData.get('icon'),
      transactionCount: 0,
      totalSpent: 0
    };

    // PONTO DE ENTRADA DA API
    // Hoje: atualizar estado em memória
    // Amanhã: POST /api/categories (novo) ou PUT /api/categories/:id (edição)
    if (this.editingId) {
      this.store.update(this.editingId, categoryData);
      // await fetch(`/api/categories/${this.editingId}`, { method: 'PUT', body: JSON.stringify(categoryData) });
    } else {
      this.store.add(categoryData);
      // await fetch('/api/categories', { method: 'POST', body: JSON.stringify(categoryData) });
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
      // Amanhã: DELETE /api/categories/:id
      this.store.remove(id);
      // await fetch(`/api/categories/${id}`, { method: 'DELETE' });
      
      this.render();
    }
  },

  render() {
    const categories = this.store.getAll();
    const container = document.getElementById('categories-container');
    const grid = document.getElementById('categories-grid');
    const list = document.getElementById('categories-list');

    if (!categories || categories.length === 0) {
      container.style.display = 'flex';
      grid.style.display = 'none';
      return;
    }

    container.style.display = 'none';
    grid.style.display = 'grid';

    list.innerHTML = categories.map(category => `
      <div class="category-card ${category.type}">
        <div class="category-icon">${category.icon}</div>
        <h3 class="category-name">${category.name}</h3>
        <span class="category-type">${this.formatType(category.type)}</span>

        <div class="category-stats">
          <div class="stat-item">
            <span class="stat-label">Transações</span>
            <span class="stat-value">${category.transactionCount}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Total</span>
            <span class="stat-value">${this.formatCurrency(category.totalSpent)}</span>
          </div>
        </div>

        <div class="category-actions">
          <button class="btn-action" onclick="CategoriesPage.handleEdit(${category.id})">Editar</button>
          <button class="btn-action delete" onclick="CategoriesPage.handleDelete(${category.id})">Deletar</button>
        </div>
      </div>
    `).join('');
  },

  formatType(type) {
    const types = {
      income: 'Entrada',
      expense: 'Saída',
      transfer: 'Transferência'
    };
    return types[type] || type;
  },

  formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'BRL'
    }).format(value);
  }
};

document.addEventListener('DOMContentLoaded', () => {
  CategoriesPage.init();
});
