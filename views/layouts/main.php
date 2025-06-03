<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Concessionária EliteMotors' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <h1>🚗 Concessionária EliteMotors</h1>                <div class="header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Usuário logado -->
                        <span class="user-welcome">Olá, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuário') ?></span>
                          <?php if (($_SESSION['user_type'] ?? '') === 'admin'): ?>
                            <!-- ADMIN - Acesso completo ao sistema -->
                            <span class="admin-badge">ADMIN</span>
                            <a href="<?= BASE_URL ?>dashboard" class="modern-btn modern-btn-danger">🛠️ Painel Admin</a>
                            <a href="<?= BASE_URL ?>user" class="modern-btn modern-btn-warning">👥 Usuários</a>
                            <a href="<?= BASE_URL ?>car/add" class="modern-btn modern-btn-success">➕ Novo Carro</a>
                            <a href="<?= BASE_URL ?>car" class="modern-btn modern-btn-outline">📋 Catálogo</a>
                        <?php else: ?>
                            <!-- USUÁRIO - Acesso limitado -->
                            <a href="<?= BASE_URL ?>dashboard/user" class="modern-btn modern-btn-info">📊 Meu Dashboard</a>
                            <a href="<?= BASE_URL ?>favorite" class="modern-btn modern-btn-secondary">❤️ Favoritos</a>
                            <a href="<?= BASE_URL ?>car" class="modern-btn modern-btn-outline">📋 Catálogo</a>
                        <?php endif; ?>
                        
                        <a href="<?= BASE_URL ?>" class="modern-btn modern-btn-outline">🏠 Início</a>
                        <a href="<?= BASE_URL ?>auth/logout" class="modern-btn modern-btn-secondary">Sair</a>
                    <?php else: ?>
                        <!-- VISITANTE - Acesso público -->
                        <a href="<?= BASE_URL ?>car" class="modern-btn modern-btn-primary">📋 Ver Carros</a>
                        <a href="<?= BASE_URL ?>" class="modern-btn modern-btn-outline">🏠 Início</a>
                        <a href="<?= BASE_URL ?>auth" class="modern-btn modern-btn-success">🔑 Entrar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>    </header>

    <main class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>        <!-- O conteúdo específico da página será inserido aqui -->
        <!-- NOTA: Este layout está DEPRECIADO. Use header.php + footer.php -->
    </main>

    <script src="<?= BASE_URL ?>js/script.js"></script>
</body>
</html>
