<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title><?= $title ?? 'Concessionária EliteMotors' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body>    <header class="header-modern">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <a href="<?= BASE_URL ?>" class="logo-link">
                        <div class="logo-icon">🏆</div>
                        <div class="logo-text">
                            <span class="logo-main">EliteMotors</span>
                            <span class="logo-sub">Premium Collection</span>
                        </div>
                    </a>
                </div>
                
                <nav class="main-nav">
                    <a href="<?= BASE_URL ?>" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Início</span>
                    </a>
                    <a href="<?= BASE_URL ?>car" class="nav-link">
                        <i class="fas fa-car"></i>
                        <span>Catálogo</span>
                    </a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (($_SESSION['user_type'] ?? '') !== 'admin'): ?>
                            <a href="<?= BASE_URL ?>favorite" class="nav-link">
                                <i class="fas fa-heart"></i>
                                <span>Favoritos</span>
                            </a>
                        <?php endif; ?>
                        <?php if (($_SESSION['user_type'] ?? '') === 'admin'): ?>
                            <a href="<?= BASE_URL ?>dashboard" class="nav-link admin-link">
                                <i class="fas fa-crown"></i>
                                <span>Admin</span>
                            </a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>dashboard/user" class="nav-link">
                                <i class="fas fa-user"></i>
                                <span>Dashboard</span>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </nav>

                <div class="header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-menu">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="user-info">
                                <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuário') ?></span>
                                <?php if (($_SESSION['user_type'] ?? '') === 'admin'): ?>
                                    <span class="user-badge admin">ADMIN</span>
                                <?php else: ?>
                                    <span class="user-badge">CLIENTE</span>
                                <?php endif; ?>
                            </div>
                            <div class="user-dropdown">
                                <a href="<?= BASE_URL ?>auth/logout" class="dropdown-link">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Sair
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>auth" class="btn-auth">
                            <i class="fas fa-user-plus"></i>
                            <span>Entre na Elite</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>    <?php if (isset($_SESSION['error'])): ?>
        <div class="container">
            <div class="modern-alert modern-alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="container">
            <div class="modern-alert modern-alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- O conteúdo específico da página será inserido aqui -->
