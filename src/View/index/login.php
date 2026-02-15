<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facilite • Login</title>
  <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
  <main class="login-container">
    <h1 class="page-title">Facilite</h1>
    
    <div class="login-card">
      <h2 class="card-title">Acesse sua conta</h2>
      
      <form id="loginForm" method="post" action="/auth">
        <label for="email">Email</label>
        <input 
          id="email" 
          type="email" 
          name="email" 
          required
        >

        <label for="password">Senha</label>
        <input 
          id="password" 
          type="password" 
          name="password" 
          required
        >
        
        <a href="#" class="forgot-password">Esqueci minha senha</a>

        <p class="error" style="display: none;" role="alert"></p>

        <button type="submit" class="submit-btn">Entrar</button>
      </form>
    </div>
    
    <div class="register-link">
      <span>Não possui conta?</span>
      <a href="/register">Cadastre-se</a>
    </div>
  </main>

  <script src="/assets/js/login.js"></script>
</body>
</html>
