# Padrão Frontend - CRUD em Memória

Documentação do padrão de frontend usado em Lançamentos, Contas e Categorias.

## Visão Geral

O frontend segue um padrão simples e reutilizável para CRUD (Create, Read, Update, Delete) em memória, sem dependência de backend. Três utilitários fazem todo o trabalho pesado:

- **StateUtils** - Gerencia estado (dados em memória)
- **ModalUtils** - Controla abertura/fechamento de modais
- **FormUtils** - Valida formulários com feedback visual

Cada página (Transactions, Accounts, Categories) é uma instância desse padrão.

## Arquitetura

```
Page (TransactionsPage, AccountsPage, CategoriesPage)
├── store (StateUtils)
├── modal (ModalUtils)
├── form (FormUtils)
└── render()
```

## StateUtils - Gerenciamento de Estado

**Responsabilidade:** Fonte única de verdade para dados em memória.

### Uso

```javascript
// Criar store com dados iniciais
const store = StateUtils.createStore([
  { id: 1, name: 'Item 1' },
  { id: 2, name: 'Item 2' }
]);

// Operações CRUD
store.add({ name: 'Item 3' });           // Adiciona com ID auto-incrementado
store.update(1, { name: 'Novo Nome' });  // Atualiza item
store.remove(1);                          // Remove item
store.findById(1);                        // Busca por ID
store.getAll();                           // Retorna cópia de todos
```

### Características

- IDs são auto-incrementados
- `add()` retorna o novo item com ID
- `getAll()` retorna cópia (não referência)
- Operações são imutáveis (não modifica original)

## ModalUtils - Controle de Modais

**Responsabilidade:** Abrir, fechar e gerenciar estado do modal.

### Uso

```javascript
// Abrir modal
ModalUtils.open('transaction-modal');

// Fechar modal
ModalUtils.close('transaction-modal');

// Setup automático de listeners
ModalUtils.setupDefaultListeners('transaction-modal', () => {
  // Callback quando modal fecha
  FormUtils.resetForm('transaction-form');
});
```

### Características

- Suporta ESC para fechar automaticamente
- Overlay clicável fecha modal
- Botões `[data-modal-close]` e `[data-modal-cancel]` funcionam automaticamente
- Bloqueia scroll do body quando aberto

### HTML Esperado

```html
<div id="transaction-modal" class="modal" style="display: none;">
  <div class="modal-overlay"></div>
  <div class="modal-content">
    <div class="modal-header">
      <h2 id="modal-title">Novo Item</h2>
      <button type="button" data-modal-close class="modal-close">×</button>
    </div>
    <form id="transaction-form">
      <!-- campos -->
    </form>
  </div>
</div>
```

## FormUtils - Validação Visual

**Responsabilidade:** Validar campos e mostrar erros visuais.

### Uso

```javascript
// Limpar erros anteriores
FormUtils.clearErrors('transaction-form');

// Validar campo obrigatório
FormUtils.validateRequired('transaction-name', 'Nome');

// Validar número
FormUtils.validateNumber('transaction-value', 'Valor', 0); // min = 0

// Resetar formulário
FormUtils.resetForm('transaction-form');
```

### Características

- Adiciona classe `.error` ao campo e grupo
- Mostra mensagem de erro abaixo do campo
- Fundo vermelho em campos com erro
- Limpa erros antes de validar novamente

### HTML Esperado

```html
<div class="form-group">
  <label for="transaction-name">Nome *</label>
  <input type="text" id="transaction-name" name="name" required>
  <!-- Mensagem de erro aparece aqui automaticamente -->
</div>
```

## Fluxo Típico: Novo Item

```
1. Usuário clica "+ Novo"
   └─> openModal() sem ID

2. Modal abre
   └─> ModalUtils.open('modal-id')
   └─> Focus no primeiro campo

3. Usuário preenche formulário

4. Usuário clica "Salvar"
   └─> handleFormSubmit(e)
   └─> e.preventDefault()

5. Validar campos
   └─> validateForm()
   └─> FormUtils.validateRequired()
   └─> Se inválido: mostra erros e retorna

6. Extrair dados do formulário
   └─> new FormData(form)
   └─> Converter tipos (parseFloat, etc)

7. Atualizar estado
   └─> store.add(data)

8. Re-renderizar
   └─> render()
   └─> Atualiza DOM com novos dados

9. Fechar modal
   └─> closeModal()
   └─> FormUtils.resetForm()
```

## Fluxo Típico: Editar Item

```
1. Usuário clica "Editar"
   └─> handleEdit(id)
   └─> openModal(id)

2. Modal abre com dados preenchidos
   └─> ModalUtils.open('modal-id')
   └─> Busca item: store.findById(id)
   └─> Preenche campos com valores

3. Usuário modifica dados

4. Usuário clica "Salvar"
   └─> handleFormSubmit(e)

5. Validar campos (mesmo fluxo)

6. Extrair dados do formulário

7. Atualizar estado
   └─> store.update(editingId, data)

8. Re-renderizar

9. Fechar modal
```

## Estrutura de Página

Cada página segue esse padrão:

```javascript
const PageName = {
  store: null,
  editingId: null,

  fields: {
    fieldName: { id: 'field-id', name: 'Field Label' }
  },

  init() {
    this.initializeStore();
    this.setupEventListeners();
    this.render();
  },

  initializeStore() {
    this.store = StateUtils.createStore([...]);
  },

  setupEventListeners() {
    // Conectar botões e formulário
    ModalUtils.setupDefaultListeners('modal-id', () => this.onModalClose());
  },

  openModal(id = null) {
    this.editingId = id;
    if (id) {
      // Preencher com dados existentes
    }
    ModalUtils.open('modal-id');
  },

  closeModal() {
    ModalUtils.close('modal-id');
  },

  onModalClose() {
    this.editingId = null;
    FormUtils.resetForm('form-id');
  },

  validateForm() {
    FormUtils.clearErrors('form-id');
    let isValid = true;
    isValid &= FormUtils.validateRequired('field-id', 'Field Name');
    return isValid;
  },

  handleFormSubmit(e) {
    e.preventDefault();
    if (!this.validateForm()) return;

    const data = new FormData(document.getElementById('form-id'));
    const item = { /* extrair dados */ };

    if (this.editingId) {
      this.store.update(this.editingId, item);
    } else {
      this.store.add(item);
    }

    this.render();
    this.closeModal();
  },

  handleEdit(id) {
    this.openModal(id);
  },

  handleDelete(id) {
    if (confirm('Tem certeza?')) {
      this.store.remove(id);
      this.render();
    }
  },

  render() {
    const items = this.store.getAll();
    // Atualizar DOM com items
  }
};

document.addEventListener('DOMContentLoaded', () => {
  PageName.init();
});
```

## Convenções de Nomenclatura

### IDs HTML

```
Modal:        {resource}-modal
Formulário:   {resource}-form
Campos:       {resource}-{fieldname}
Título modal: modal-title
Botões:       btn-new-{resource}, btn-new-{resource}-empty
```

Exemplos:
- `transaction-modal`, `transaction-form`, `transaction-date`
- `account-modal`, `account-form`, `account-balance`
- `category-modal`, `category-form`, `category-icon`

### Nomes de Funções

```
openModal(id = null)      // Abre modal (novo ou edição)
closeModal()              // Fecha modal
onModalClose()            // Callback quando fecha
validateForm()            // Valida todos os campos
handleFormSubmit(e)       // Processa submissão
handleEdit(id)            // Abre modal para editar
handleDelete(id)          // Deleta item
render()                  // Atualiza DOM
```

## Integração com Backend

Quando o backend estiver pronto:

1. Remover `initializeStore()` com dados mock
2. Substituir `store.add()` por `fetch POST`
3. Substituir `store.update()` por `fetch PUT`
4. Substituir `store.remove()` por `fetch DELETE`
5. Substituir `store.getAll()` por `fetch GET`

Os utilitários (StateUtils, ModalUtils, FormUtils) não precisam mudar. Apenas a lógica de persistência.

## Debugging

### Ver estado atual
```javascript
console.log(PageName.store.getAll());
```

### Testar validação
```javascript
PageName.validateForm();
```

### Forçar re-render
```javascript
PageName.render();
```

## Notas

- Sem dependências externas (jQuery, React, etc)
- Sem fetch ou chamadas de API (tudo em memória)
- Dados são perdidos ao recarregar página (esperado)
- Validação é apenas visual (sem regras de negócio)
- Cada página é independente (sem estado compartilhado)

---

**Última atualização:** Fevereiro 2025  
**Versão:** 1.0 (Frontend-only, sem backend)
