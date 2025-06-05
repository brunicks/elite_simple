<?php
$title = $title ?? 'Esqueci Minha Senha';
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
            <h2>Esqueci Minha Senha</h2>
              <?php if (isset($_SESSION['error'])): ?>
                <div class="modern-alert modern-alert-error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="modern-alert modern-alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <form method="POST" action="<?= BASE_URL ?>auth/request_reset" class="auth-form">
                <div class="form-group">
                    <label for="email">Digite seu email:</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="exemplo@email.com">
                </div>
                
                <button type="submit" class="modern-btn modern-btn-primary">
                    Enviar Link de Reset
                </button>
                
                <div class="auth-links">
                    <a href="<?= BASE_URL ?>" class="auth-link">
                        ← Voltar para Login
                    </a>
                </div>
            </form>
            
            <div class="info-box">
                <h3>Como funciona?</h3>
                <ul>
                    <li>Digite seu email cadastrado</li>
                    <li>Você receberá um link para criar uma nova senha</li>
                    <li>O link expira em 1 hora por segurança</li>
                    <li>Após redefinir, faça login normalmente</li>
                </ul>
            </div>
        </div>
    </div>
    
<?php include_once ROOT . '/views/layouts/footer.php'; ?>