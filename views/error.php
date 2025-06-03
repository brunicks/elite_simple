<?php
$title = 'Erro - ' . ($title ?? 'Página não encontrada');
include_once ROOT . '/views/layouts/header.php';
?><main class="container">
    <div class="error-container" style="text-align: center; padding: 50px; background: white; border-radius: 10px; max-width: 600px; margin: 50px auto; box-shadow: 0 0 20px rgba(0,0,0,0.1);">
        <h1 style="color: #e74c3c; font-size: 48px; margin-bottom: 20px;">500</h1>
        <h2>Erro Interno do Servidor</h2>
        <p style="font-size: 18px; color: #666; margin-bottom: 30px;">Desculpe, ocorreu um erro inesperado. Nossa equipe foi notificada e está trabalhando para resolver o problema.</p>
        <a href="<?= BASE_URL ?>" class="modern-btn modern-btn-primary">Voltar ao Início</a>
    </div>
</main>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
