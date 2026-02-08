// Form Utilities - Validação visual centralizada

const FormUtils = {
  clearErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.querySelectorAll('.form-group.error').forEach(group => {
      group.classList.remove('error');
      const errorMsg = group.querySelector('.error-message');
      if (errorMsg) {
        errorMsg.remove();
      }
    });

    form.querySelectorAll('input.error, select.error, textarea.error').forEach(field => {
      field.classList.remove('error');
    });
  },

  showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (!field) return;

    const group = field.closest('.form-group');
    if (!group) return;

    group.classList.add('error');
    field.classList.add('error');

    const existingError = group.querySelector('.error-message');
    if (existingError) {
      existingError.remove();
    }

    const errorMsg = document.createElement('span');
    errorMsg.className = 'error-message';
    errorMsg.textContent = message;
    group.appendChild(errorMsg);
  },

  validateRequired(fieldId, fieldName) {
    const field = document.getElementById(fieldId);
    if (!field) return true;

    const value = field.value?.trim();
    if (!value) {
      this.showFieldError(fieldId, `${fieldName} é obrigatório`);
      return false;
    }

    return true;
  },

  validateNumber(fieldId, fieldName, minValue = 0) {
    const field = document.getElementById(fieldId);
    if (!field) return true;

    const value = parseFloat(field.value);
    if (isNaN(value) || value <= minValue) {
      this.showFieldError(fieldId, `${fieldName} deve ser maior que ${minValue}`);
      return false;
    }

    return true;
  },

  resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
      form.reset();
      this.clearErrors(formId);
    }
  }
};
