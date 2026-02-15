document.addEventListener('DOMContentLoaded', function() {
  const loginForm = document.getElementById('loginForm');
  const errorMessage = document.querySelector('.error');

  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;

      if (!email || !password) {
        showError('Por favor, preencha todos os campos');
        return;
      }

      if (!isValidEmail(email)) {
        showError('Por favor, insira um email válido');
        return;
      }

      submitForm(email, password);
    });
  }

  function submitForm(email, password) {
    const submitBtn = loginForm.querySelector('.submit-btn');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Entrando...';

    // Douglas: Credenciais mockadas. Quando o banco entrar, isso morre 💀
    fetch('/auth', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showSuccess('Login realizado com sucesso! Redirecionando...');
        setTimeout(() => {
          window.location.href = '/dashboard';
        }, 1500);
      } else {
        showError(data.message || 'Email ou senha incorretos');
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
