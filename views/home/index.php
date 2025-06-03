<?php
// views/home/index.php - Elite Motors Premium Homepage
$title = $title ?? 'EliteMotors - Concessionária Premium de Veículos de Luxo';
include_once ROOT . '/views/layouts/header.php';
?>

<main>    <?php if (isset($_SESSION['error'])): ?>
        <div class="modern-alert modern-alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
      
    <?php if (isset($_SESSION['success'])): ?>
        <div class="modern-alert modern-alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Hero Section Premium -->
    <section class="hero-premium">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">PREMIUM COLLECTION</div>
            <h1 class="hero-title">EliteMotors</h1>
            <p class="hero-subtitle">Excelência em cada detalhe, luxo em cada quilômetro</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Anos de Experiência</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Veículos Vendidos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Satisfação</div>
                </div>
            </div>
            <?php if (!isset($user_logged) || !$user_logged): ?>
                <a href="<?= BASE_URL ?>auth" class="btn-hero">
                    <i class="fas fa-user-crown"></i>
                    Entre na Elite
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>dashboard" class="btn-hero">
                    <i class="fas fa-tachometer-alt"></i>
                    Seu Painel Elite
                </a>
            <?php endif; ?>
        </div>
        <div class="hero-scroll">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Seção de Valores Premium -->
    <section class="premium-values">
        <div class="container">
            <div class="values-grid">                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Garantia Elite</h3>
                    <p>Todos os nossos veículos passam por inspeção rigorosa com garantia estendida</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3>Curadoria Exclusiva</h3>
                    <p>Seleção criteriosa de veículos premium e de luxo para clientes exigentes</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h3>Atendimento VIP</h3>
                    <p>Experiência personalizada com suporte dedicado durante todo o processo</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Veículos Premium em Destaque -->
    <?php if (!empty($cars)): ?>
    <section class="featured-collection">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">SELEÇÃO EXCLUSIVA</div>
                <h2 class="section-title">Coleção Premium</h2>
                <p class="section-subtitle">Veículos cuidadosamente selecionados para o cliente mais exigente</p>
            </div>

            <div class="premium-carousel">
                <div class="carousel-track" id="premiumCarousel">
                    <?php foreach ($cars as $index => $car): ?>
                        <div class="premium-card">
                            <div class="card-image">
                                <?php if ($car['imagem']): ?>
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($car['imagem']) ?>" 
                                         alt="<?= htmlspecialchars($car['modelo']) ?>"
                                         onerror="this.src='https://via.placeholder.com/600x400/1a1a1a/ffffff?text=<?= urlencode($car['marca'] . ' ' . $car['modelo']) ?>'">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/600x400/1a1a1a/ffffff?text=<?= urlencode($car['marca'] . ' ' . $car['modelo']) ?>" 
                                         alt="<?= htmlspecialchars($car['modelo']) ?>">
                                <?php endif; ?>
                                <div class="card-overlay">
                                    <div class="card-year"><?= $car['ano'] ?></div>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-brand"><?= htmlspecialchars($car['marca']) ?></div>
                                <h3 class="card-model"><?= htmlspecialchars($car['modelo']) ?></h3>
                                <div class="card-specs">
                                    <span class="spec-item">
                                        <i class="fas fa-road"></i>
                                        <?= number_format($car['km'], 0, '.', '.') ?> km
                                    </span>
                                </div>
                                <div class="card-price">
                                    R$ <?= number_format($car['preco'], 0, ',', '.') ?>
                                </div>
                                <div class="card-actions">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <button class="btn-favorite <?= isset($car['is_favorite']) && $car['is_favorite'] ? 'favorited' : '' ?>" 
                                                onclick="toggleFavorite(<?= $car['id'] ?>, this)" data-car-id="<?= $car['id'] ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-favorite" onclick="showLoginModal()">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>car/details/<?= $car['id'] ?>" class="btn-details">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (count($cars) > 1): ?>
                <button class="carousel-btn carousel-prev" onclick="movePremiumCarousel(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn carousel-next" onclick="movePremiumCarousel(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <?php endif; ?>
            </div>
        </div>    </section>
    <?php endif; ?>

    <!-- CTA Final -->
    <section class="final-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para a Experiência Elite?</h2>
                <p>Explore nosso catálogo completo com filtros avançados e encontre o veículo dos seus sonhos</p>
                <a href="<?= BASE_URL ?>car" class="btn-cta">
                    <i class="fas fa-cars"></i>
                    Explorar Catálogo Completo
                </a>
            </div>
        </div>
    </section>
</main>

<!-- Estilos específicos da homepage premium -->
<style>
/* Hero Section Premium */
.hero-premium {
    position: relative;
    height: 100vh;
    min-height: 800px;
    background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.5)), 
                url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(ellipse at center, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.8) 100%);
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    padding: 0 20px;
    animation: fadeInUp 1s ease-out;
}

.hero-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255,215,0,0.2);
    border: 1px solid #ffd700;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 30px;
    color: #ffd700;
}

.hero-title {
    font-size: clamp(3rem, 8vw, 6rem);
    font-weight: 100;
    margin-bottom: 20px;
    letter-spacing: 3px;
    background: linear-gradient(45deg, #ffffff, #ffd700);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: clamp(1.2rem, 3vw, 1.8rem);
    margin-bottom: 50px;
    opacity: 0.9;
    font-weight: 300;
    line-height: 1.4;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 60px;
    margin-bottom: 50px;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #ffd700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
    letter-spacing: 1px;
}

.btn-hero {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 40px;
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    color: #000;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 10px 30px rgba(255,215,0,0.3);
}

.btn-hero:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(255,215,0,0.4);
    background: linear-gradient(45deg, #ffed4a, #ffd700);
}

.hero-scroll {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    animation: bounce 2s infinite;
    color: rgba(255,255,255,0.7);
    font-size: 1.5rem;
}

/* Seção de Valores Premium */
.premium-values {
    padding: 100px 0;
    background: #f8f9fa;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
}

.value-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 1px solid #eee;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.15);
}

.value-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ffd700, #ffed4a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 2rem;
    color: #000;
}

.value-card h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #333;
    font-weight: 600;
}

.value-card p {
    color: #666;
    line-height: 1.6;
    font-size: 1rem;
}

/* Coleção Premium */
.featured-collection {
    padding: 100px 0;
    background: #fff;
}

.section-header {
    text-align: center;
    margin-bottom: 80px;
}

.section-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255,215,0,0.1);
    border: 1px solid #ffd700;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 2px;
    margin-bottom: 20px;
    color: #ffd700;
}

.section-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    margin-bottom: 20px;
    color: #333;
    font-weight: 300;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.premium-carousel {
    position: relative;
    overflow: hidden;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 60px; /* Espaço para os botões */
}

.carousel-track {
    display: flex;
    gap: 30px;
    transition: transform 0.5s ease;
    padding: 20px 0;
    scroll-behavior: smooth;
}

.premium-card {
    min-width: 350px;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.premium-card:hover {
    transform: translateY(-15px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.2);
}

.card-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.premium-card:hover .card-image img {
    transform: scale(1.1);
}

.card-overlay {
    position: absolute;
    top: 20px;
    right: 20px;
}

.card-year {
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.card-content {
    padding: 30px;
}

.card-brand {
    font-size: 0.9rem;
    color: #ffd700;
    font-weight: 600;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.card-model {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

.card-specs {
    margin-bottom: 20px;
}

.spec-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 0.9rem;
}

.spec-item i {
    color: #ffd700;
}

.card-price {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 25px;
}

.card-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.btn-favorite {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 2px solid #ddd;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #999;
}

.btn-favorite:hover,
.btn-favorite.favorited {
    border-color: #e74c3c;
    color: #e74c3c;
    background: #fff5f5;
}

.btn-details {
    flex: 1;
    padding: 15px 20px;
    background: linear-gradient(135deg, #333, #555);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-details:hover {
    background: linear-gradient(135deg, #555, #333);
    transform: translateY(-2px);
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.95);
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.carousel-btn:hover {
    background: #ffd700;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 12px 35px rgba(255, 215, 0, 0.3);
}

.carousel-prev {
    left: 15px;
}

.carousel-next {
    right: 15px;
}

/* CTA Final */
.final-cta {
    padding: 100px 0;
    background: #f8f9fa;
}

.cta-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: #333;
    font-weight: 300;
}

.cta-content p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 40px;
    line-height: 1.6;
}

.btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 15px;
    padding: 20px 40px;
    background: linear-gradient(135deg, #333, #555);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.btn-cta:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    background: linear-gradient(135deg, #555, #333);
}

/* Animações */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateX(-50%) translateY(0);
    }
    40% {
        transform: translateX(-50%) translateY(-10px);
    }
    60% {
        transform: translateX(-50%) translateY(-5px);
    }
}

/* Responsivo */
@media (max-width: 768px) {
    .hero-premium {
        min-height: 600px;
        background-attachment: scroll;
    }
    
    .hero-stats {
        gap: 30px;
    }
    
    .values-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .carousel-track {
        gap: 20px;
    }
    
    .premium-card {
        min-width: 280px;
    }
    
    .search-wrapper {
        flex-direction: column;
    }
    
    .carousel-btn {
        display: none;
    }
}
</style>

<!-- JavaScript para carrossel -->
<script>
let currentSlide = 0;
const carousel = document.getElementById('premiumCarousel');
const cards = carousel ? carousel.children : [];
const totalSlides = cards.length;

function movePremiumCarousel(direction) {
    if (!carousel || totalSlides <= 1) return;
    
    currentSlide += direction;
    
    if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    } else if (currentSlide >= totalSlides) {
        currentSlide = 0;
    }
    
    // Calculate proper card width including margins
    const cardWidth = 350;
    const cardGap = 30;
    const containerWidth = carousel.parentElement.offsetWidth;
    const visibleCards = Math.floor(containerWidth / (cardWidth + cardGap));
    
    // Ensure we don't show empty spaces at the end
    const maxSlide = Math.max(0, totalSlides - visibleCards);
    if (currentSlide > maxSlide) {
        currentSlide = 0;
    }
    
    const translateX = -currentSlide * (cardWidth + cardGap);
    carousel.style.transform = `translateX(${translateX}px)`;
}

// Auto-play do carrossel (only if more than 1 card)
if (totalSlides > 1) {
    setInterval(() => {
        movePremiumCarousel(1);
    }, 5000);
}

// Smooth scroll para hero
document.querySelector('.hero-scroll')?.addEventListener('click', () => {
    document.querySelector('.premium-values').scrollIntoView({ 
        behavior: 'smooth' 
    });
});

// Initialize carousel position
document.addEventListener('DOMContentLoaded', function() {
    if (carousel && totalSlides > 0) {
        carousel.style.transform = 'translateX(0px)';
    }
});
</script>

<?php
// JavaScript específico já incluído no arquivo
include_once ROOT . '/views/layouts/footer.php';
?>
