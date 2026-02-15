// Modal Utilities - Padrão reutilizável para modais

const ModalUtils = {
  open(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      this.setupEscapeListener(modalId);
    }
  },

  close(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
      this.removeEscapeListener(modalId);
    }
  },

  setupEscapeListener(modalId) {
    const handler = (e) => {
      if (e.key === 'Escape') {
        this.close(modalId);
        document.removeEventListener('keydown', handler);
      }
    };
    document.addEventListener('keydown', handler);
  },

  removeEscapeListener(modalId) {
    // ESC listener é removido automaticamente quando modal fecha
  },

  setupDefaultListeners(modalId, onClose = null) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const closeBtn = modal.querySelector('[data-modal-close]');
    const cancelBtn = modal.querySelector('[data-modal-cancel]');
    const overlay = modal.querySelector('.modal-overlay');

    const closeHandler = () => {
      this.close(modalId);
      if (onClose) onClose();
    };

    if (closeBtn) {
      closeBtn.addEventListener('click', closeHandler);
    }

    if (cancelBtn) {
      cancelBtn.addEventListener('click', closeHandler);
    }

    if (overlay) {
      overlay.addEventListener('click', closeHandler);
    }
  }
};
