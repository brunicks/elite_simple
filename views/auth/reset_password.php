<?php
$title = $title ?? 'Nova Senha';
include_once ROOT . '/views/layouts/header.php';
?>
<style>
    body {
        background: rgb(0,0,0) !important;
    }
</style>
<body>
    <div class="container">
        <div class="auth-container">
            <h1>EliteMotors</h1>
            <h2>Definir Nova Senha</h2>
              <?php if (isset($_SESSION['error'])): ?>
                <div class="modern-alert modern-alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="info-box">
                <p><strong>Olá <?= htmlspecialchars($user['nome']) ?>!</strong></p>
                <p>Defina sua nova senha abaixo:</p>
            </div>
            
            <form method="POST" action="<?= BASE_URL ?>auth/update_password" class="auth-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="form-group">
                    <label for="senha">Nova Senha:</label>
                    <input type="password" id="senha" name="senha" required 
                           minlength="3" placeholder="Digite sua nova senha">
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required 
                           minlength="3" placeholder="Digite novamente sua nova senha">
                </div>
                
                <button type="submit" class="modern-btn modern-btn-primary">
                    Alterar Senha
                </button>
                
                <div class="auth-links">
                    <a href="<?= BASE_URL ?>" class="auth-link">
                        ← Voltar para Login
                    </a>
                </div>
            </form>
            
            <div class="info-box">
                <h3>Dicas de Segurança:</h3>
                <ul>
                    <li>Use pelo menos 8 caracteres</li>
                    <li>Combine letras, números e símbolos</li>
                    <li>Não use informações pessoais</li>
                    <li>Mantenha sua senha segura</li>
                </ul>
            </div>
        </div>
    </div>
    
<?php include_once ROOT . '/views/layouts/footer.php'; ?>
