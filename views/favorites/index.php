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
}

.favorites-hero {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 50%, #d4af37 100%);
    color: #1a1a1a;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(212, 175, 55, 0.3);
    text-align: center;
}

.favorites-hero h1 {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.favorites-hero p {
    font-size: 1.1em;
    margin-bottom: 20px;
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
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
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
    height: 220px;
    overflow: hidden;
}

.favorite-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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
    margin-bottom: 20px;
}

.favorite-detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
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
    margin-bottom: 25px;
    padding: 20px;
    background: rgba(26, 26, 26, 0.8);
    border-radius: 8px;
    border: 1px solid rgba(212, 175, 55, 0.3);
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
            <?php foreach ($favorites as $car): ?>
                <div class="favorite-card">
                    <div class="favorite-card-image">
                        <img src="<?= $car['imagem'] ? BASE_URL . 'uploads/' . htmlspecialchars($car['imagem']) : 'https://via.placeholder.com/350x220?text=Sem+Imagem' ?>" 
                             alt="<?= htmlspecialchars($car['modelo']) ?>"
                             onerror="this.src='https://via.placeholder.com/350x220?text=Sem+Imagem'">
                        
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
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script>
// Função para toggle de favoritos
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
</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
