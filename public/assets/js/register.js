document.addEventListener('DOMContentLoaded', function() {
  const registerForm = document.getElementById('registerForm');
  const errorMessage = document.querySelector('.error');

  if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;

      if (!name || !email || !password || !confirmPassword) {
        showError('Por favor, preencha todos os campos');
        return;
      }

      if (!isValidEmail(email)) {
        showError('Por favor, insira um email válido');
        return;
      }

      if (password.length < 6) {
        showError('A senha deve ter no mínimo 6 caracteres');
        return;
      }

      if (password !== confirmPassword) {
        showError('As senhas não coincidem');
        return;
      }

      submitForm(name, email, password);
    });
  }

  function submitForm(name, email, password) {
    const submitBtn = registerForm.querySelector('.submit-btn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Criando conta...';

    // Douglas: Cadastro mockado. Quando o banco entrar, isso morre 💀
    fetch('/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ name, email, password })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showSuccess('Conta criada com sucesso! Redirecionando...');
        setTimeout(() => {
          window.location.href = '/login';
        }, 2000);
      } else {
        showError(data.message || 'Erro ao criar conta');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }
    })
    .catch(error => {
      console.error('Erro:', error);
      showError('Erro ao conectar com o servidor');
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    });
  }

  function showError(message) {
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
    errorMessage.style.color = '#d32f2f';
    errorMessage.style.background = '#ffebee';
    setTimeout(() => {
      errorMessage.style.display = 'none';
    }, 5000);
  }

  function showSuccess(message) {
    errorMessage.textContent = message;
    errorMessage.style.display = 'block';
    errorMessage.style.color = '#388e3c';
    errorMessage.style.background = '#e8f5e9';
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }
});
