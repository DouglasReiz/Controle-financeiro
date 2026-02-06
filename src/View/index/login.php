<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facilite • Login</title>
  <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>

  <!-- Container principal da página de login - DanielTomazDev - 05/02/2026 -->
  <main class="login-container">
    
    <!-- Título Facilite - DanielTomazDev - 05/02/2026 -->
    <h1 class="page-title">Facilite</h1>
    
    <!-- Card do formulário - DanielTomazDev - 05/02/2026 -->
    <div class="login-card">
      <h2 class="card-title">Acesse sua conta</h2>
      
      <!-- Botões de login social - DanielTomazDev - 05/02/2026 -->
      <button type="button" class="social-btn facebook-btn">
        <span class="social-icon facebook-icon">f</span>
        <span>Entre com o Facebook</span>
      </button>
      
      <button type="button" class="social-btn google-btn">
        <span class="social-icon google-icon">G</span>
        <span>Entre com o Google</span>
      </button>
      
      <!-- Separador - DanielTomazDev - 05/02/2026 -->
      <div class="separator">
        <span>ou</span>
      </div>
      
      <!-- Formulário de login - DanielTomazDev - 05/02/2026 -->
      <form id="loginForm" method="post" action="#">
        <!-- Campo de email - DanielTomazDev - 05/02/2026 -->
        <label for="email">Seu email</label>
        <input 
          id="email" 
          type="email" 
          name="email" 
          required
        >

        <!-- Campo de senha - DanielTomazDev - 05/02/2026 -->
        <label for="password">Sua senha</label>
        <input 
          id="password" 
          type="password" 
          name="password" 
          required
        >
        
        <!-- Link esqueci senha - DanielTomazDev - 05/02/2026 -->
        <a href="#" class="forgot-password">Esqueci minha senha</a>

        <!-- Mensagem de erro (inicialmente oculta) - DanielTomazDev - 05/02/2026 -->
        <p class="error" style="display: none;" role="alert"></p>

        <!-- Botão de submit - DanielTomazDev - 05/02/2026 -->
        <button type="submit" class="submit-btn">Entrar</button>
      </form>
    </div>
    
    <!-- Link de cadastro - DanielTomazDev - 05/02/2026 -->
    <div class="register-link">
      <span>Ainda não possui conta?</span>
      <a href="/register">Faça o cadastro!</a>
    </div>
  </main>

  <!-- Script de validação e controle do formulário - DanielTomazDev -->
  <script src="/assets/js/login.js"></script>
</body>
</html>
