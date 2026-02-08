<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facilite • Cadastro</title>
  <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
  <main class="register-container">
    <h1 class="page-title">Facilite</h1>
    
    <div class="register-card">
      <h2 class="card-title">Crie sua conta</h2>
      
      <form id="registerForm" method="post" action="/register">
        <label for="name">Nome completo</label>
        <input 
          id="name" 
          type="text" 
          name="name" 
          required
        >

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
          minlength="6"
        >

        <label for="confirmPassword">Confirme a senha</label>
        <input 
          id="confirmPassword" 
          type="password" 
          name="confirmPassword" 
          required
        >

        <p class="error" style="display: none;" role="alert"></p>

        <button type="submit" class="submit-btn">Criar conta</button>
      </form>
    </div>
    
    <div class="login-link">
      <span>Já possui conta?</span>
      <a href="/login">Faça login</a>
    </div>
  </main>

  <script src="/assets/js/register.js"></script>
</body>
</html>
