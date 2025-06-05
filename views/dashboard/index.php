<?php
// views/dashboard/index.php
$title = $title ?? 'Dashboard - Concessionária';
include_once ROOT . '/views/layouts/header.php';
?>

<main class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
          <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>        <!-- Dashboard Welcome Section -->
        <div class="dashboard-welcome" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">            <?php if ($is_admin): ?>
                <h1>🛠️ Painel Administrativo</h1>
                <p>Bem-vindo, <strong><?= htmlspecialchars($user_name) ?></strong>! Gerencie o estoque de veículos da concessionária.</p>
                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="<?= BASE_URL ?>car/add" class="btn btn-success" style="background: rgba(255,255,255,0.2); border: 2px solid white;">➕ Adicionar Novo Carro</a>
                    <a href="<?= BASE_URL ?>user" class="btn btn-warning" style="background: rgba(255,255,255,0.2); border: 2px solid white;">👥 Gerenciar Usuários</a>
                    <a href="<?= BASE_URL ?>dashboard/stats" class="btn btn-info" style="background: rgba(255,255,255,0.2); border: 2px solid white;">📊 Ver Estatísticas</a>
                </div>
            <?php else: ?>
                <h1> Meu Dashboard Personalizado</h1>
                <p>Olá, <strong><?= htmlspecialchars($user_name) ?></strong>! Sua área pessoal para explorar carros e gerenciar seus favoritos.</p>                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="<?= BASE_URL ?>favorite" class="btn btn-primary" style="background: rgba(255,255,255,0.2); border: 2px solid white;">❤️ Ver Meus Favoritos</a>
                    <a href="<?= BASE_URL ?>car" class="btn btn-info" style="background: rgba(255,255,255,0.2); border: 2px solid white;">📋 Catálogo Completo</a>
                    <a href="<?= BASE_URL ?>" class="btn btn-secondary" style="background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.5);">🏠 Página Inicial</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="dashboard-header">
            <div class="search-container">
                <form method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Buscar por modelo ou marca..." 
                           value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-primary">🔍 Buscar</button>
                    <?php if (!empty($search)): ?>
                        <a href="<?= BASE_URL ?>dashboard" class="btn btn-secondary">Limpar</a>
                    <?php endif; ?>                </form>
            </div>
        </div>

        <div class="cars-grid">
            <?php if (empty($cars)): ?>
                <div class="no-cars">
                    <h3>Nenhum carro encontrado</h3>
                    <p>
                        <?php if (!empty($search)): ?>
                            Tente alterar os termos de busca.
                        <?php elseif ($is_admin): ?>
                            <a href="<?= BASE_URL ?>car/add" class="btn btn-primary">Adicionar o primeiro carro</a>
                        <?php else: ?>
                            Entre em contato conosco para ver nossos veículos disponíveis.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>                <?php foreach ($cars as $car): ?>
                    <div class="car-card <?= ($is_admin && $car['ativo'] == 0) ? 'car-inactive' : '' ?>">
                        <?php if ($is_admin && $car['ativo'] == 0): ?>
                            <div class="inactive-badge">
                                <span> DESATIVADO</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="car-image">                            <?php if ($car['imagem']): ?>
                                <img src="<?= BASE_URL ?>uploads/cars/<?= htmlspecialchars($car['imagem']) ?>" 
                                     alt="<?= htmlspecialchars($car['modelo']) ?>"                                     onerror="this.src='<?= BASE_URL ?>assets/images/car-placeholder.jpg'">
                            <?php else: ?>
                                <img src="<?= BASE_URL ?>assets/images/car-placeholder.jpg" 
                                     alt="Sem imagem">
                            <?php endif; ?>
                        </div>
                        
                        <div class="car-info">
                            <h3><?= htmlspecialchars($car['marca'] . ' ' . $car['modelo']) ?></h3>
                            <div class="car-details">
                                <span class="year">📅 <?= $car['ano'] ?></span>
                                <span class="km">🛣️ <?= number_format($car['km'], 0, ',', '.') ?> km</span>
                            </div>
                            <div class="car-price">
                                💰 R$ <?= number_format($car['preco'], 2, ',', '.') ?>
                            </div>                            <?php if ($is_admin): ?>
                                <div class="car-actions">
                                    <?php if ($car['ativo'] == 1): ?>
                                        <!-- Carro ativo - ações normais -->
                                        <a href="<?= BASE_URL ?>car/edit/<?= $car['id'] ?>" class="btn btn-edit">✏️ Editar</a>
                                        <button onclick="deleteCar(<?= $car['id'] ?>)" class="btn btn-delete">🗑️ Desativar</button>
                                    <?php else: ?>
                                        <!-- Carro desativado - ações de reativação -->
                                        <button onclick="reactivateCar(<?= $car['id'] ?>)" class="btn btn-success">✅ Reativar</button>
                                        <button onclick="hardDeleteCar(<?= $car['id'] ?>)" class="btn btn-danger">🗑️ Deletar Permanentemente</button>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <!-- Usuário logado comum - botões com favoritos -->
                                <div class="car-actions-logged">
                                    <button class="btn btn-favorite <?= isset($car['is_favorite']) && $car['is_favorite'] ? 'favorited' : '' ?>" 
                                            onclick="toggleFavorite(<?= $car['id'] ?>, this)"
                                            data-car-id="<?= $car['id'] ?>"
                                            title="<?= isset($car['is_favorite']) && $car['is_favorite'] ? 'Remover dos favoritos' : 'Adicionar aos favoritos' ?>">
                                        <i class="fas fa-heart"></i>
                                        <?= isset($car['is_favorite']) && $car['is_favorite'] ? 'Favoritado' : 'Favoritar' ?>
                                    </button>
                                    <a href="<?= BASE_URL ?>car/details/<?= $car['id'] ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Ver Detalhes
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>    </main>

    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    <script>
        <?php if (!$is_admin): ?>
        // Função para toggle de favoritos (usuários logados comuns)
        function toggleFavorite(carId, button) {
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
            
            fetch('<?= BASE_URL ?>favorite/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'car_id=' + carId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar visual do botão
                    if (data.is_favorite) {
                        button.classList.add('favorited');
                        button.innerHTML = '<i class="fas fa-heart"></i> Favoritado';
                        button.title = 'Remover dos favoritos';
                    } else {
                        button.classList.remove('favorited');
                        button.innerHTML = '<i class="fas fa-heart"></i> Favoritar';
                        button.title = 'Adicionar aos favoritos';
                    }
                    showModal(data.message, 'success');
                } else {
                    button.innerHTML = originalContent;
                    showModal(data.message || 'Erro ao processar favorito', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                button.innerHTML = originalContent;
                showModal('Erro ao processar favorito', 'error');
            })
            .finally(() => {
                button.disabled = false;
            });
        }        <?php endif; ?>
    </script>
<?php include_once ROOT . '/views/layouts/footer.php'; ?>
