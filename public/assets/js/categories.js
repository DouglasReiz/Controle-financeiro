// Categories Page - Frontend Only
// Mock temporário. Douglas, pode quebrar isso quando quiser
// Douglas: Não quebrou
const CategoriesPage = {
  store: null,
  editingId: null,
  fields: {
    name: { id: 'category-name', name: 'Nome' },
    type: { id: 'category-type', name: 'Tipo' },
  },

  async init() {
    this.store = StateUtils.createStore([]);
    this.setupEventListeners();
    await this.loadCategories();
  },

  // ─── API ─────────────────────────────────────────────────────────────────

  async loadCategories() {
    try {
      const response = await fetch('/categorias', {
        headers: { 'Accept': 'application/json' }
      });
      if (!response.ok) throw new Error(`Erro ${response.status}`);

      const json = await response.json();
      const categories = json.data ?? [];
      this.store = StateUtils.createStore(categories);
    } catch (err) {
      console.error('Erro ao carregar categorias:', err);
      this.showError('Não foi possível carregar as categorias.');
    } finally {
      this.render();
    }
  },

  async createCategory(data) {
    const response = await fetch('/categorias_create', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    const json = await response.json();
    return json.data;
  },

  async updateCategory(id, data) {
    const response = await fetch(`/categorias/${id}_update`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    const json = await response.json();
    return json.data;
  },

  async deleteCategory(id) {
    const response = await fetch(`/categorias/${id}_delete`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    });
    if (response.status === 204) return true;
    if (!response.ok) throw new Error(`Erro ${response.status}`);
    return true;
  },

  // ─── Eventos ─────────────────────────────────────────────────────────────

  setupEventListeners() {
    const btnNew = document.getElementById('btn-new-category');
    const btnNewEmpty = document.getElementById('btn-new-category-empty');
    const form = document.getElementById('category-form');

    if (btnNew) btnNew.addEventListener('click', () => this.openModal());
    if (btnNewEmpty) btnNewEmpty.addEventListener('click', () => this.openModal());
    if (form) form.addEventListener('submit', (e) => this.handleFormSubmit(e));

    ModalUtils.setupDefaultListeners('category-modal', () => this.onModalClose());
  },

  // ─── Modal ───────────────────────────────────────────────────────────────

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

  // ─── Formulário ──────────────────────────────────────────────────────────

  validateForm() {
    FormUtils.clearErrors('category-form');
    let isValid = true;
    isValid &= FormUtils.validateRequired(this.fields.name.id, this.fields.name.name);
    isValid &= FormUtils.validateRequired(this.fields.type.id, this.fields.type.name);
    return isValid;
  },

  async handleFormSubmit(e) {
    e.preventDefault();
    if (!this.validateForm()) return;

    const formData = new FormData(document.getElementById('category-form'));
    const data = {
      name: formData.get('name'),
      type: formData.get('type'),
      icon: formData.get('icon')
    };

    const submitBtn = document.querySelector('#category-form [type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Salvando...';

    try {
      if (this.editingId) {
        const updated = await this.updateCategory(this.editingId, data);
        this.store.update(this.editingId, updated ?? { ...data, id: this.editingId });
      } else {
        const created = await this.createCategory(data);
        this.store.add(created ?? data);
      }
      this.render();
      this.closeModal();
    } catch (err) {
      console.error('Erro ao salvar categoria:', err);
      alert('Não foi possível salvar a categoria. Tente novamente.');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Salvar Categoria';
    }
  },

  // ─── Ações da lista ──────────────────────────────────────────────────────

  handleEdit(id) {
    this.openModal(id);
  },

  async handleDelete(id) {
    if (!confirm('Tem certeza que quer deletar esta categoria?')) return;
    try {
      await this.deleteCategory(id);
      this.store.remove(id);
      this.render();
    } catch (err) {
      console.error('Erro ao deletar categoria:', err);
      alert('Não foi possível deletar a categoria. Tente novamente.');
    }
  },

  // ─── Render ──────────────────────────────────────────────────────────────

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
      <div class="category-icon">${this.getIcon(category.type)}</div>
      <h3 class="category-name">${category.name}</h3>
        <span class="category-type">${this.formatType(category.type)}</span>
        <div class="category-stats">
          <div class="stat-item">
            <span class="stat-label">Transações</span>
            <span class="stat-value">${category.transactionCount ?? 0}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Total</span>
            <span class="stat-value">${this.formatCurrency(category.totalSpent ?? 0)}</span>
          </div>
        </div>
        <div class="category-actions">
          <button class="btn-action" onclick="CategoriesPage.handleEdit(${category.id})">Editar</button>
          <button class="btn-action delete" onclick="CategoriesPage.handleDelete(${category.id})">Deletar</button>
        </div>
      </div>
    `).join('');
  },

  // ─── Helpers ─────────────────────────────────────────────────────────────

  showError(message) {
    const container = document.getElementById('categories-container');
    const grid = document.getElementById('categories-grid');
    container.style.display = 'flex';
    grid.style.display = 'none';
    container.innerHTML = `
      <article class="empty-state">
        <div class="empty-state-icon">⚠️</div>
        <h2 class="empty-state-title">Algo deu errado</h2>
        <p class="empty-state-message">${message}</p>
        <button class="btn btn-primary btn-large" onclick="CategoriesPage.loadCategories()">
          Tentar novamente
        </button>
      </article>
    `;
  },

  formatType(type) {
    const types = { income: 'Entrada', expense: 'Saída', transfer: 'Transferência' };
    return types[type] || type;
  },

  formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
  },

  getIcon(type) {
    const icons = { income: '💰', expense: '💸', transfer: '🔄' };
    return icons[type] ?? '📌';
  },
};

document.addEventListener('DOMContentLoaded', () => CategoriesPage.init());