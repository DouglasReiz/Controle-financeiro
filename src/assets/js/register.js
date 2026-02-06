/**
 * ============================================
 * CONTROLE DO FORMULÁRIO DE CADASTRO - DanielTomazDev - 05/02/2026
 * ============================================
 */

// Aguarda o carregamento completo da página - DanielTomazDev - 05/02/2026
document.addEventListener('DOMContentLoaded', function() {
  
  // Obtém referência ao formulário - DanielTomazDev - 05/02/2026
  const registerForm = document.getElementById('registerForm');
  
  // Verifica se o formulário existe na página - DanielTomazDev - 05/02/2026
  if (!registerForm) {
    console.warn('Formulário de cadastro não encontrado na página');
    return;
  }

  // Adiciona listener para o evento de submit - DanielTomazDev - 05/02/2026
  registerForm.addEventListener('submit', function(event) {
    // Previne o comportamento padrão (recarregar a página) - DanielTomazDev - 05/02/2026
    event.preventDefault();

    // ============================================
    // CAPTURA DOS ELEMENTOS DO FORMULÁRIO - DanielTomazDev - 05/02/2026
    // ============================================
    
    const form = event.target;
    const nameInput = form.querySelector('[name="name"]');
    const emailInput = form.querySelector('[name="email"]');
    const passwordInput = form.querySelector('[name="password"]');
    const confirmPasswordInput = form.querySelector('[name="confirmPassword"]');
    const errorMessage = document.querySelector('.error');
    
    // Extrai os valores dos campos - DanielTomazDev - 05/02/2026
    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();
    const confirmPassword = confirmPasswordInput.value.trim();

    // ============================================
    // LIMPEZA DE ERROS ANTERIORES - DanielTomazDev - 05/02/2026
    // ============================================
    
    errorMessage.style.display = 'none';
    nameInput.classList.remove('error');
    emailInput.classList.remove('error');
    passwordInput.classList.remove('error');
    confirmPasswordInput.classList.remove('error');

    // ============================================
    // VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS - DanielTomazDev - 05/02/2026
    // ============================================
    
    if (!name || !email || !password || !confirmPassword) {
      errorMessage.textContent = 'Preencha todos os campos';
      errorMessage.style.display = 'block';
      
      if (!name) nameInput.classList.add('error');
      if (!email) emailInput.classList.add('error');
      if (!password) passwordInput.classList.add('error');
      if (!confirmPassword) confirmPasswordInput.classList.add('error');
      
      return;
    }

    // ============================================
    // VALIDAÇÃO DE SENHA MÍNIMA - DanielTomazDev - 05/02/2026
    // ============================================
    
    if (password.length < 6) {
      errorMessage.textContent = 'A senha deve ter no mínimo 6 caracteres';
      errorMessage.style.display = 'block';
      passwordInput.classList.add('error');
      return;
    }

    // ============================================
    // VALIDAÇÃO DE CONFIRMAÇÃO DE SENHA - DanielTomazDev - 05/02/2026
    // ============================================
    
    if (password !== confirmPassword) {
      errorMessage.textContent = 'As senhas não coincidem';
      errorMessage.style.display = 'block';
      passwordInput.classList.add('error');
      confirmPasswordInput.classList.add('error');
      return;
    }

    // ============================================
    // PREPARAÇÃO DOS DADOS PARA ENVIO - DanielTomazDev - 05/02/2026
    // ============================================
    
    const registerData = {
      name: name,
      email: email,
      password: password
    };

    // ============================================
    // SIMULAÇÃO DE ENVIO AO BACKEND DO douglas - DanielTomazDev - 05/02/2026
    // ============================================
    
    // MOCK: Simulação de fetch para /register
    // TODO: Substituir por chamada real quando o backend estiver pronto
    console.log('Dados prontos para envio:', registerData);
    console.log('Endpoint simulado: POST /register');

  });

});
