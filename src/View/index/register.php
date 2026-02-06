<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facilite • Cadastro</title>
  <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>

  <!-- Container principal da página de cadastro - DanielTomazDev - 05/02/2026 -->
  <main class="register-container">
    
    <!-- Título Facilite - DanielTomazDev - 05/02/2026 -->
    <h1 class="page-title">Facilite</h1>
    
    <!-- Card do formulário - DanielTomazDev - 05/02/2026 -->
    <div class="register-card">
      <h2 class="card-title">Crie sua conta</h2>
      
      <!-- Botões de cadastro social - DanielTomazDev - 05/02/2026 -->
      <button type="button" class="social-btn facebook-btn">
        <span class="social-icon facebook-icon">f</span>
        <span>Cadastre-se com o Facebook</span>
      </button>
      
      <button type="button" class="social-btn google-btn">
        <span class="social-icon google-icon">G</span>
        <span>Cadastre-se com o Google</span>
      </button>
      
      <!-- Separador - DanielTomazDev - 05/02/2026 -->
      <div class="separator">
        <span>ou</span>
      </div>
      
      <!-- Formulário de cadastro - DanielTomazDev - 05/02/2026 -->
      <form id="registerForm" method="post" action="#">
        <!-- Campo de nome - DanielTomazDev - 05/02/2026 -->
        <label for="name">Seu nome</label>
        <input 
          id="name" 
          type="text" 
          name="name" 
          required
          placeholder="Digite seu nome completo"
        >

        <!-- Campo de email - DanielTomazDev - 05/02/2026 -->
        <label for="email">Seu email</label>
        <input 
          id="email" 
          type="email" 
          name="email" 
          required
          placeholder="seu@email.com"
        >

        <!-- Campo de senha - DanielTomazDev - 05/02/2026 -->
        <label for="password">Sua senha</label>
        <input 
          id="password" 
          type="password" 
          name="password" 
          required
          placeholder="Mínimo 6 caracteres"
          minlength="6"
        >

        <!-- Campo de confirmação de senha - DanielTomazDev - 05/02/2026 -->
        <label for="confirmPassword">Confirme sua senha</label>
        <input 
          id="confirmPassword" 
          type="password" 
          name="confirmPassword" 
          required
          placeholder="Digite a senha novamente"
        >

        <!-- Mensagem de erro (inicialmente oculta) - DanielTomazDev - 05/02/2026 -->
        <p class="error" style="display: none;" role="alert"></p>

        <!-- Botão de submit - DanielTomazDev - 05/02/2026 -->
        <button type="submit" class="submit-btn">Criar conta</button>
      </form>
    </div>
    
    <!-- Link de login - DanielTomazDev - 05/02/2026 -->
    <div class="login-link">
      <span>Já possui conta?</span>
      <a href="/login">Faça o login!</a>
    </div>
  </main>

  <!-- Script de validação e controle do formulário - DanielTomazDev - 05/02/2026 -->
  <script src="/assets/js/register.js"></script>
</body>
</html>
