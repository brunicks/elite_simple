<?php
$title = $title ?? 'Login';
include_once ROOT . '/views/layouts/header.php';
?>
<body>
    <div class="container">
        <div class="auth-container">
            <h1>EliteMotors</h1>
              <?php if (isset($_SESSION['error'])): ?>
                <div class="modern-alert modern-alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="modern-alert modern-alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
              <div class="auth-tabs">
                <button class="tab-btn active" onclick="showTab('login')"><span>Login</span></button>
                <button class="tab-btn" onclick="showTab('register')"><span>Cadastrar</span></button>
            </div>
            
            <!-- Formulário de Login -->
            <div id="login-tab" class="tab-content active">
                <form method="POST" action="<?= BASE_URL ?>auth/login" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                    
                    <button type="submit" name="login" class="modern-btn modern-btn-primary">Entrar</button>
                </form>
            </div>
            
            <!-- Formulário de Registro -->
            <div id="register-tab" class="tab-content">
                <form method="POST" action="<?= BASE_URL ?>auth/register" class="auth-form">
                    <div class="form-group">
                        <label for="nome_reg">Nome:</label>
                        <input type="text" id="nome_reg" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email_reg">Email:</label>
                        <input type="email" id="email_reg" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha_reg">Senha:</label>
                        <input type="password" id="senha_reg" name="senha" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Senha:</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                    </div>
                    
                    <button type="submit" name="register" class="modern-btn modern-btn-primary">Cadastrar</button>
                </form>
            </div>        </div>
    </div>
    
<?php include_once ROOT . '/views/layouts/footer.php'; ?>
