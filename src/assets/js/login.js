/**
 * ============================================
 * CONTROLE DO FORMULÁRIO DE LOGIN - DanielTomazDev - 05/02/2026
 * ============================================
 */

// Aguarda o carregamento completo da página - DanielTomazDev - 05/02/2026
document.addEventListener('DOMContentLoaded', function() {
  
  // Obtém referência ao formulário - DanielTomazDev - 05/02/2026
  const loginForm = document.getElementById('loginForm');
  
  // Verifica se o formulário existe na página - DanielTomazDev - 05/02/2026
  if (!loginForm) {
    console.warn('Formulário de login não encontrado na página');
    return;
  }

  // Adiciona listener para o evento de submit - DanielTomazDev - 05/02/2026
  loginForm.addEventListener('submit', function(event) {
    // Previne o comportamento padrão (recarregar a página) - DanielTomazDev - 05/02/2026
    event.preventDefault();

    // ============================================
    // CAPTURA DOS ELEMENTOS DO FORMULÁRIO - DanielTomazDev - 05/02/2026
    // ============================================
    
    const form = event.target;
    const emailInput = form.querySelector('[name="email"]');
    const passwordInput = form.querySelector('[name="password"]');
    const errorMessage = document.querySelector('.error');
    
    // Extrai os valores dos campos - DanielTomazDev - 05/02/2026
    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    // ============================================
    // LIMPEZA DE ERROS ANTERIORES - DanielTomazDev - 05/02/2026
    // ============================================
    
    errorMessage.style.display = 'none';
    emailInput.classList.remove('error');
    passwordInput.classList.remove('error');

    // ============================================
    // VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS - DanielTomazDev - 05/02/2026
    // ============================================
    
    if (!email || !password) {
      // Exibe mensagem de erro - DanielTomazDev - 05/02/2026
      errorMessage.textContent = 'Preenche tudo ai cara';
      errorMessage.style.display = 'block';
      
      // Destaca os campos vazios - DanielTomazDev - 05/02/2026
      if (!email) {
        emailInput.classList.add('error');
      }
      if (!password) {
        passwordInput.classList.add('error');
      }
      
      // Interrompe o processamento - DanielTomazDev - 05/02/2026
      return;
    }

    // ============================================
    // PREPARAÇÃO DOS DADOS PARA ENVIO - DanielTomazDev
    // ============================================
    
    const loginData = {
      email: email,
      password: password
    };

    // MOCK: Simulação de envio ao backend do douglas- DanielTomazDev - 05/02/2026
    // TODO: Substituir por chamada real à API quando o backend estiver pronto - DanielTomazDev - 05/02/2026    
    console.log('Dados prontos para envio:', loginData);

  });

});
