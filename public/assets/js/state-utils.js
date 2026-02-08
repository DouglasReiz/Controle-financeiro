// State Utilities - Gerenciamento de estado em memória

const StateUtils = {
  createStore(initialData = []) {
    return {
      data: initialData,
      nextId: Math.max(...initialData.map(item => item.id || 0), 0) + 1,

      getAll() {
        return [...this.data];
      },

      add(item) {
        const newItem = {
          id: this.nextId++,
          ...item
        };
        this.data.unshift(newItem);
        return newItem;
      },

      remove(id) {
        this.data = this.data.filter(item => item.id !== id);
      },

      update(id, updates) {
        const index = this.data.findIndex(item => item.id === id);
        if (index !== -1) {
          this.data[index] = { ...this.data[index], ...updates };
        }
      },

      findById(id) {
        return this.data.find(item => item.id === id);
      }
    };
  }
};
