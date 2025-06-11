<?php
// views/favorites/index.php
$title = $title ?? 'Meus Favoritos';
include_once ROOT . '/views/layouts/header.php';
?>

<style>
.favorites-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    min-height: 100vh;
    padding: 20px;
    color: #ffffff;
    display: block;
    width: 100%;
}

.favorites-hero {
    background: linear-gradient(135deg,rgb(231, 204, 114) 0%, #f4e18f 50%, #d4af37 100%);
    color: #000000;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(212, 175, 55, 0.4);
    text-align: center;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.favorites-hero h1 {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    color: #000000;
}

.favorites-hero p {
    font-size: 1.1em;
    margin-bottom: 20px;
    color: #1a1a1a;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
}

.favorites-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.empty-favorites {
    background: rgba(45, 45, 45, 0.9);
    padding: 60px 40px;
    border-radius: 12px;
    text-align: center;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.empty-favorites-icon {
    font-size: 4em;
    color: #d4af37;
    margin-bottom: 20px;
}

.empty-favorites h3 {
    font-size: 1.8em;
    margin-bottom: 15px;
    color: #ffffff;
}

.empty-favorites p {
    color: #cccccc;
    margin-bottom: 25px;
    font-size: 1.1em;
}

.favorites-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
    margin-bottom: 0;
    justify-items: stretch;
    align-items: stretch;
    width: 100%;
    grid-auto-flow: row;
}

.favorite-card {
    background: rgba(45, 45, 45, 0.95);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    position: relative;
    display: flex;
    flex-direction: column;
    height: auto;
    min-height: 520px;
}

.favorite-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #f4e18f, #d4af37);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.favorite-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(212, 175, 55, 0.25);
    border-color: rgba(212, 175, 55, 0.7);
}

.favorite-card:hover::before {
    opacity: 1;
}

.favorite-card-image {
    position: relative;
    height: 250px;
    overflow: hidden;
    background: #2a2a2a;
    border-radius: 15px 15px 0 0;
}

.favorite-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
    border-radius: 15px 15px 0 0;
    background: linear-gradient(135deg, #2a2a2a 0%, #3a3a3a 100%);
}

.favorite-card-image img[src*="car-placeholder"] {
    object-fit: contain;
    padding: 20px;
    background: linear-gradient(135deg, #2a2a2a 0%, #3a3a3a 100%);
}

.favorite-card:hover .favorite-card-image img {
    transform: scale(1.05);
}

.car-year-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(212, 175, 55, 0.9);
    color: #1a1a1a;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9em;
}

.favorite-card-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.favorite-car-title h3 {
    font-size: 1.4em;
    color: #d4af37;
    margin: 0 0 5px 0;
}

.favorite-car-title h4 {
    font-size: 1.1em;
    color: #ffffff;
    margin: 0 0 20px 0;
    font-weight: normal;
}

.favorite-car-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin: 20px 0;
}

.favorite-detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 12px;
    background: rgba(26, 26, 26, 0.5);
    border-radius: 8px;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.favorite-detail-item i {
    color: #d4af37;
    font-size: 1.2em;
}

.favorite-detail-label {
    color: #cccccc;
    font-size: 0.9em;
}

.favorite-detail-value {
    color: #ffffff;
    font-weight: bold;
}

.favorite-car-price {
    margin: 20px 0;
    padding: 20px;
    background: rgba(26, 26, 26, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    text-align: center;
}

.favorite-price-value {
    font-size: 1.8em;
    font-weight: bold;
    color: #d4af37;
    margin-bottom: 5px;
}

.favorite-price-date {
    color: #cccccc;
    font-size: 0.9em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.favorite-card-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 15px;
}

.modern-btn {
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9em;
}

.modern-btn-primary {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 100%);
    color: #1a1a1a;
}

.modern-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
}

.modern-btn-secondary {
    background: rgba(45, 45, 45, 0.8);
    color: #ffffff;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.modern-btn-secondary:hover {
    background: rgba(212, 175, 55, 0.2);
    border-color: rgba(212, 175, 55, 0.6);
}

.modern-btn-outline {
    background: transparent;
    color: #d4af37;
    border: 2px solid #d4af37;
}

.modern-btn-outline:hover {
    background: #d4af37;
    color: #1a1a1a;
}

/* Paginação dos Favoritos */
.favorites-pagination-wrapper {
    width: 100%;
    display: block;
    text-align: center;
    margin: 40px auto 20px auto;
    clear: both;
    grid-column: 1 / -1;
}

.favorites-pagination {
    display: inline-flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
    justify-content: center;
}

.favorites-pagination-btn,
.favorites-pagination-current {
    padding: 12px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
    min-width: 45px;
    justify-content: center;
}

.favorites-pagination-btn {
    background: rgba(45, 45, 45, 0.8);
    color: #ffffff;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.favorites-pagination-btn:hover {
    background: rgba(212, 175, 55, 0.2);
    border-color: rgba(212, 175, 55, 0.6);
    color: #d4af37;
    transform: translateY(-2px);
}

.favorites-pagination-current {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 100%);
    color: #1a1a1a;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
}

/* Responsivo para paginação */
@media (max-width: 768px) {
    .favorites-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .favorite-card {
        min-height: 480px;
    }
    
    .favorite-card-image {
        height: 220px;
    }
    
    .favorite-car-details {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .favorite-card-actions {
        flex-direction: column;
        gap: 12px;
    }
    
    .modern-btn {
        width: 100%;
        justify-content: center;
    }
    
    .favorites-pagination {
        gap: 4px;
    }
    
    .favorites-pagination-btn,
    .favorites-pagination-current {
        padding: 10px 14px;
        font-size: 0.9em;
    }
    
    .favorites-pagination-wrapper {
        margin: 30px 0 15px 0;
    }
}
</style>

<main class="favorites-container">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="modern-alert modern-alert-error">
            <span class="modern-alert-icon">⚠️</span>
            <span class="modern-alert-text"><?= htmlspecialchars($_SESSION['error']) ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="modern-alert modern-alert-success">
            <span class="modern-alert-icon">✅</span>
            <span class="modern-alert-text"><?= htmlspecialchars($_SESSION['success']) ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Hero Header -->
    <div class="favorites-hero">
        <h1>
            <i class="fas fa-heart"></i>
            Meus Favoritos
        </h1>
        
        <p>
            <?php if ($favoritesCount > 0): ?>
                ✨ Você tem <strong><?= $favoritesCount ?></strong> veículo<?= $favoritesCount !== 1 ? 's' : '' ?> especialmente selecionado<?= $favoritesCount !== 1 ? 's' : '' ?>!
            <?php else: ?>
                💝 Seu espaço especial para os carros dos seus sonhos
            <?php endif; ?>
        </p>
        
        <div class="favorites-actions">
            <a href="<?= ($_SESSION['user_type'] ?? '') === 'admin' ? BASE_URL . 'dashboard' : BASE_URL . 'dashboard/user' ?>" class="modern-btn modern-btn-outline">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="<?= BASE_URL ?>car" class="modern-btn modern-btn-primary">
                <i class="fas fa-search"></i> Explorar Carros
            </a>
        </div>
    </div>

    <?php
    // Paginação dos favoritos
    $perPage = 4;
    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $totalFavorites = count($favorites);
    $totalPages = max(1, ceil($totalFavorites / $perPage));
    $start = ($currentPage - 1) * $perPage;
    $favoritesPage = array_slice($favorites, $start, $perPage);
    ?>
    <?php if (empty($favorites)): ?>
        <!-- Empty State -->
        <div class="empty-favorites">
            <div class="empty-favorites-icon">
                <i class="fas fa-heart-broken"></i>
            </div>
            
            <h3>Nenhum favorito ainda</h3>
            <p>Você ainda não favoritou nenhum veículo. Explore nosso catálogo e favorite os carros que mais chamarem sua atenção!</p>
            
            <div class="favorites-actions">
                <a href="<?= BASE_URL ?>car" class="modern-btn modern-btn-primary">
                    <i class="fas fa-search"></i> Explorar Catálogo
                </a>
                <a href="<?= BASE_URL ?>" class="modern-btn modern-btn-outline">
                    <i class="fas fa-home"></i> Voltar ao Início
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Grid de Favoritos -->
        <div class="favorites-grid">
            <?php foreach ($favoritesPage as $car): ?>
                <div class="favorite-card">
                    <div class="favorite-card-image">                        <img src="<?= $car['imagem'] ? BASE_URL . 'uploads/cars/' . htmlspecialchars($car['imagem']) : BASE_URL . 'assets/images/car-placeholder.jpg' ?>" 
                             alt="<?= htmlspecialchars($car['modelo']) ?>"
                             onerror="this.src='<?= BASE_URL ?>assets/images/car-placeholder.jpg'">
                        
                        <div class="car-year-badge">
                            <?= $car['ano'] ?>
                        </div>
                    </div>
                    
                    <div class="favorite-card-content">
                        <div class="favorite-car-title">
                            <h3><?= htmlspecialchars($car['marca']) ?></h3>
                            <h4><?= htmlspecialchars($car['modelo']) ?></h4>
                        </div>
                        
                        <div class="favorite-car-details">
                            <div class="favorite-detail-item">
                                <i class="fas fa-road"></i>
                                <div class="favorite-detail-label">Quilometragem</div>
                                <div class="favorite-detail-value"><?= number_format($car['km'], 0, ',', '.') ?> km</div>
                            </div>
                            <div class="favorite-detail-item">
                                <i class="fas fa-calendar"></i>
                                <div class="favorite-detail-label">Ano</div>
                                <div class="favorite-detail-value"><?= $car['ano'] ?></div>
                            </div>
                        </div>
                        
                        <div class="favorite-car-price">
                            <div class="favorite-price-value">
                                R$ <?= number_format($car['preco'], 2, ',', '.') ?>
                            </div>
                            <div class="favorite-price-date">
                                <i class="fas fa-heart"></i> 
                                Favoritado em <?= date('d/m/Y', strtotime($car['favorited_at'])) ?>
                            </div>
                        </div>
                        
                        <div class="favorite-card-actions">
                            <a href="<?= BASE_URL ?>car/details/<?= $car['id'] ?>" class="modern-btn modern-btn-primary">
                                <i class="fas fa-eye"></i> Ver Detalhes
                            </a>
                            <button onclick="toggleFavorite(<?= $car['id'] ?>, this)" class="modern-btn modern-btn-secondary" data-car-id="<?= $car['id'] ?>">
                                <i class="fas fa-heart-broken"></i> Remover
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>        </div>
        
        <!-- Paginação - Posicionada abaixo do grid -->
        <?php if ($totalPages > 1): ?>
        <div class="favorites-pagination-wrapper">
            <div class="favorites-pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=1" class="favorites-pagination-btn" title="Primeira página">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="?page=<?= $currentPage - 1 ?>" class="favorites-pagination-btn" title="Página anterior">
                        <i class="fas fa-angle-left"></i>
                    </a>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="favorites-pagination-current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>" class="favorites-pagination-btn"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1 ?>" class="favorites-pagination-btn" title="Próxima página">
                        <i class="fas fa-angle-right"></i>
                    </a>
                    <a href="?page=<?= $totalPages ?>" class="favorites-pagination-btn" title="Última página">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<script>
// Função para toggle de favoritos com atualização global
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
            if (data.action === 'removed') {
                // Remover o card da tela com animação
                const card = button.closest('.favorite-card');
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    card.remove();
                    // Se não há mais favoritos, recarregar página
                    if (document.querySelectorAll('.favorite-card').length === 0) {
                        location.reload();
                    }
                }, 300);
            }
            // Atualizar ícones de coração em outras páginas (catálogo/detalhes)
            updateFavoriteIcons(carId, false);
        } else if (data.action === 'added') {
            updateFavoriteIcons(carId, true);
        } else {
            button.innerHTML = originalContent;
            button.disabled = false;
            alert(data.message || 'Erro ao processar favorito');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        button.innerHTML = originalContent;
        button.disabled = false;
        alert('Erro ao processar favorito');
    });
}

// Atualiza todos os ícones de favoritos na página (catálogo, detalhes, etc)
function updateFavoriteIcons(carId, isFavorited) {
    // Catálogo: botões com data-car-id
    document.querySelectorAll('[data-car-id="' + carId + '"]').forEach(btn => {
        if (isFavorited) {
            btn.classList.add('favorited');
            btn.title = 'Remover dos favoritos';
            btn.innerHTML = '<i class="fas fa-heart"></i> Favoritado';
        } else {
            btn.classList.remove('favorited');
            btn.title = 'Adicionar aos favoritos';
            btn.innerHTML = '<i class="fas fa-heart"></i> Favoritar';
        }
        btn.disabled = false;
    });
}
</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
