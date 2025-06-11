<?php
// views/cars/details.php
$title = $title ?? 'Detalhes do Veículo';
include_once ROOT . '/views/layouts/header.php';
?>

<style>
/* Car Details Elite Motors Styling */
.car-details-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    min-height: 100vh;
    padding: 20px;
    color: #ffffff;
}

.page-header {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 50%, #d4af37 100%);
    color: #1a1a1a;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(212, 175, 55, 0.3);
    text-align: center;
}

.page-header h1 {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 1.2em;
    opacity: 0.8;
    margin: 0;
}

.car-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
    align-items: start;
}

.car-image-section {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 440px;
}

.car-main-image {
    width: 100%;
    max-width: 100%;
    height: 400px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    background: #2a2a2a;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.car-main-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    transition: opacity 0.3s ease;
    background: linear-gradient(135deg, #2a2a2a 0%, #3a3a3a 100%);
}

/* Estilo específico para placeholders */
.car-main-image img[src*="car-placeholder"] {
    object-fit: contain;
    padding: 40px;
    background: linear-gradient(135deg, #2a2a2a 0%, #3a3a3a 100%);
}

/* Para imagens reais, usar contain para se ajustar no box */
.car-main-image img:not([src*="car-placeholder"]) {
    object-fit: contain;
}

/* Loading state para imagens */
.car-main-image img {
    opacity: 0;
    transition: opacity 0.5s ease;
}

.car-main-image img.loaded {
    opacity: 1;
}

.car-info-section {
    display: flex;
    flex-direction: column;
    gap: 20px;
    min-height: 440px;
    justify-content: flex-start;
}

.price-card {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    padding: 25px;
    border: 2px solid rgba(212, 175, 55, 0.5);
    backdrop-filter: blur(10px);
    text-align: center;
    flex-shrink: 0;
}

.price-main {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.price-label {
    color: #cccccc;
    font-size: 1.1em;
    font-weight: 500;
}

.price-value {
    color: #d4af37;
    font-size: 2.5em;
    font-weight: bold;
}

.specs-card {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.specs-card h3 {
    color: #d4af37;
    font-size: 1.3em;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.specs-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: rgba(26, 26, 26, 0.8);
    border-radius: 8px;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.spec-item i {
    color: #d4af37;
    font-size: 1.2em;
    width: 20px;
    text-align: center;
}

.spec-item div {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.spec-label {
    color: #cccccc;
    font-size: 0.85em;
}

.spec-value {
    color: #ffffff;
    font-weight: 600;
    font-size: 0.95em;
}

.description-card {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.description-card h3 {
    color: #d4af37;
    font-size: 1.3em;
    margin-bottom: 15px;
}

.description-text {
    color: #cccccc;
    line-height: 1.6;
    font-size: 1em;
}

.action-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: auto;
    padding-top: 20px;
}

.btn-favorite-large, .btn-comparison, .btn-whatsapp, .btn-share {
    padding: 15px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1em;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.btn-favorite-large {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.btn-favorite-large.favorited {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.btn-favorite-large:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
}

.btn-favorite-large.favorited:hover {
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
}

.btn-comparison {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.btn-comparison.in-comparison {
    background: linear-gradient(135deg, #ffc107, #ffeb3b);
    color: #1a1a1a;
}

.btn-comparison:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
}

.btn-whatsapp {
    background: linear-gradient(135deg, #25d366, #128c7e);
    color: white;
}

.btn-whatsapp:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
}

.btn-share {
    background: linear-gradient(135deg, #6c757d, #495057);
    color: white;
}

.btn-share:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
}

.additional-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.info-card, .contact-info {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.info-card h3, .contact-info h3 {
    color: #d4af37;
    font-size: 1.3em;
    margin-bottom: 20px;
}

.info-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-card li {
    color: #cccccc;
    margin-bottom: 12px;
    font-size: 1em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.contact-info p {
    color: #cccccc;
    margin-bottom: 20px;
    line-height: 1.5;
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: rgba(26, 26, 26, 0.8);
    border-radius: 8px;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.contact-item i {
    color: #d4af37;
    font-size: 1.1em;
    width: 20px;
    text-align: center;
}

.contact-item span {
    color: #ffffff;
    font-weight: 500;
}

/* Toast Notifications */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    max-width: 350px;
}

.toast {
    background: rgba(45, 45, 45, 0.95);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
    transform: translateX(100%);
    transition: all 0.4s ease;
    display: flex;
    align-items: center;
    gap: 15px;
}

.toast.show {
    transform: translateX(0);
}

.toast.success {
    border-left: 4px solid #28a745;
}

.toast.error {
    border-left: 4px solid #dc3545;
}

.toast.warning {
    border-left: 4px solid #ffc107;
}

.toast.info {
    border-left: 4px solid #17a2b8;
}

.toast-icon {
    color: #d4af37;
    font-size: 1.2em;
}

.toast-content {
    flex: 1;
    color: #ffffff;
}

.toast-title {
    font-weight: 600;
    margin-bottom: 4px;
}

.toast-message {
    font-size: 0.9em;
    color: #cccccc;
}

.toast-close {
    background: none;
    border: none;
    color: #cccccc;
    cursor: pointer;
    font-size: 1em;
    padding: 5px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.toast-close:hover {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .car-details-grid {
        grid-template-columns: 1fr;
        align-items: stretch;
    }
    
    .car-info-section {
        min-height: auto;
    }
    
    .additional-info {
        grid-template-columns: 1fr;
    }
    
    .specs-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .car-details-container {
        padding: 15px;
    }
    
    .page-header {
        padding: 20px;
    }
    
    .page-header h1 {
        font-size: 2em;
    }
      .price-value {
        font-size: 2em;
    }
    
    .car-image-section {
        height: 340px;
    }
    
    .car-main-image {
        height: 300px;
    }
    
    .car-main-image img {
        height: 100%;
    }
    
    .car-info-section {
        min-height: auto;
    }
}

/* Animation classes */
.fade-in {
    animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<main class="car-details-container">    <!-- Título da página -->
    <div class="page-header">
        <h1><?= htmlspecialchars($car['marca'] . ' ' . $car['modelo']) ?></h1>
        <p class="page-subtitle">Ano <?= $car['ano'] ?> • <?= number_format($car['km']) ?> km rodados</p>
    </div>

    <div class="car-details-grid">
        <!-- Seção da Imagem -->
        <div class="car-image-section">
            <div class="car-main-image">
                <?php if ($car['imagem']): ?>
                    <img src="<?= BASE_URL ?>uploads/cars/<?= htmlspecialchars($car['imagem']) ?>" 
                         alt="<?= htmlspecialchars($car['modelo']) ?>"                         onerror="this.src='<?= BASE_URL ?>assets/images/car-placeholder.jpg'">
                <?php else: ?>
                    <img src="<?= BASE_URL ?>assets/images/car-placeholder.jpg" 
                         alt="Sem imagem">
                <?php endif; ?>
            </div>
        </div>

        <!-- Seção de Informações -->
        <div class="car-info-section">
            <div class="price-card">
                <div class="price-main">
                    <span class="price-label">Preço à vista</span>
                    <span class="price-value">R$ <?= number_format($car['preco'], 2, ',', '.') ?></span>
                </div>
            </div>            <div class="specs-card">
                <h3>📋 Especificações Básicas</h3>
                <div class="specs-grid">
                    <div class="spec-item">
                        <i class="fas fa-calendar"></i>
                        <div>
                            <span class="spec-label">Ano</span>
                            <span class="spec-value"><?= $car['ano'] ?></span>
                        </div>
                    </div>
                    <div class="spec-item">
                        <i class="fas fa-road"></i>
                        <div>
                            <span class="spec-label">Quilometragem</span>
                            <span class="spec-value"><?= number_format($car['km']) ?> km</span>
                        </div>
                    </div>
                    <div class="spec-item">
                        <i class="fas fa-tag"></i>
                        <div>
                            <span class="spec-label">Marca</span>
                            <span class="spec-value"><?= htmlspecialchars($car['marca']) ?></span>
                        </div>
                    </div>
                    <div class="spec-item">
                        <i class="fas fa-car"></i>
                        <div>
                            <span class="spec-label">Modelo</span>
                            <span class="spec-value"><?= htmlspecialchars($car['modelo']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($car['cv']) || !empty($car['motor']) || !empty($car['torque']) || !empty($car['combustivel']) || !empty($car['transmissao']) || !empty($car['portas']) || !empty($car['cor']) || !empty($car['consumo_medio'])): ?>
            <div class="specs-card tech-specs">
                <h3>⚙️ Especificações Técnicas</h3>
                <div class="specs-grid">
                    <?php if (!empty($car['cv'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <div>
                            <span class="spec-label">Potência</span>
                            <span class="spec-value"><?= $car['cv'] ?> cv</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($car['motor'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-cog"></i>
                        <div>
                            <span class="spec-label">Motor</span>
                            <span class="spec-value"><?= htmlspecialchars($car['motor']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($car['torque'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-bolt"></i>
                        <div>
                            <span class="spec-label">Torque</span>
                            <span class="spec-value"><?= htmlspecialchars($car['torque']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($car['combustivel'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-gas-pump"></i>
                        <div>
                            <span class="spec-label">Combustível</span>
                            <span class="spec-value"><?= htmlspecialchars($car['combustivel']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($car['transmissao'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-cogs"></i>
                        <div>
                            <span class="spec-label">Transmissão</span>
                            <span class="spec-value"><?= htmlspecialchars($car['transmissao']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($car['portas'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-door-open"></i>
                        <div>
                            <span class="spec-label">Portas</span>
                            <span class="spec-value"><?= $car['portas'] ?> portas</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($car['cor'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-palette"></i>
                        <div>
                            <span class="spec-label">Cor</span>
                            <span class="spec-value"><?= htmlspecialchars($car['cor']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($car['consumo_medio'])): ?>
                    <div class="spec-item">
                        <i class="fas fa-leaf"></i>
                        <div>
                            <span class="spec-label">Consumo</span>
                            <span class="spec-value"><?= $car['consumo_medio'] ?> km/l</span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($car['descricao'])): ?>
            <div class="description-card">
                <h3>📝 Descrição</h3>
                <p class="description-text"><?= nl2br(htmlspecialchars($car['descricao'])) ?></p>
            </div>
            <?php endif; ?>            <!-- Ações -->
            <div class="action-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Usuário logado -->                    <button class="btn-favorite-large <?= $is_favorite ? 'favorited' : '' ?>" 
                            onclick="toggleFavorite(<?= $car['id'] ?>, this)"
                            data-car-id="<?= $car['id'] ?>"
                            title="<?= $is_favorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos' ?>">
                        <i class="fas fa-heart"></i>
                        <?= $is_favorite ? 'Favoritado' : 'Favoritar' ?>
                    </button>
                <?php else: ?>                    <!-- Usuário não logado -->
                    <button class="btn-favorite-large" onclick="showLoginModal()">
                        <i class="fas fa-heart"></i>
                        Favoritar
                    </button>
                <?php endif; ?>
                
                <button class="btn-whatsapp" onclick="contactWhatsApp()">
                    <i class="fab fa-whatsapp"></i>
                    Contato WhatsApp
                </button>
                
                <button class="btn-share" onclick="shareVehicle()">
                    <i class="fas fa-share-alt"></i>
                    Compartilhar
                </button>
            </div>
        </div>
    </div>    <!-- Informações adicionais -->
    <div class="additional-info">
        <div class="info-card">
            <h3>💡 Informações Importantes</h3>
            <ul>
                <li>✅ Veículo revisado e vistoriado</li>
                <li>✅ Documentação em dia</li>
                <li>✅ Garantia de procedência</li>
                <li>✅ Aceita financiamento</li>
                <li>✅ Aceita troca</li>
            </ul>
        </div>

        <div class="contact-info">
            <h3>📞 Entre em Contato</h3>
            <p>Nossos consultores estão prontos para atendê-lo:</p>
            <div class="contact-methods">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>(11) 9999-9999</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>vendas@EliteMotors.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Rua das Concessionárias, 123 - São Paulo/SP</span>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Função para favoritar (usuários logados)
<?php if (isset($_SESSION['user_id'])): ?>
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
              // Mostrar notificação com toast
            showToast(data.message, 'success');
        } else {
            button.innerHTML = originalContent;
            showToast(data.message || 'Erro ao processar favorito', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        button.innerHTML = originalContent;
        showToast('Erro ao processar favorito', 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}

// Sistema de Toast Notifications
function createToastContainer() {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    return container;
}

function showToast(message, type = 'info', title = null) {
    const container = createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    // Ícones para cada tipo
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    // Títulos padrão para cada tipo
    const defaultTitles = {
        success: 'Sucesso!',
        error: 'Erro!',
        warning: 'Atenção!',
        info: 'Informação'
    };
    
    const toastTitle = title || defaultTitles[type];
    
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="${icons[type]}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${toastTitle}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="removeToast(this.parentElement)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Mostrar toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto remover após 5 segundos
    setTimeout(() => {
        removeToast(toast);
    }, 5000);
}

function removeToast(toast) {
    if (toast && toast.parentNode) {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 400);
    }
}

// Melhorar a função de contato WhatsApp
function contactWhatsApp() {
    showToast('Redirecionando para WhatsApp...', 'info');
    
    setTimeout(() => {
        const message = `Olá! Tenho interesse no <?= $car['marca'] . ' ' . $car['modelo'] ?> ano <?= $car['ano'] ?>. Gostaria de mais informações.`;
        const phone = '5511999999999'; // Número da concessionária
        const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        window.open(url, '_blank');
    }, 800);
}

// Melhorar a função de compartilhamento
function shareVehicle() {
    const title = '<?= $car['marca'] . ' ' . $car['modelo'] ?>';
    const url = window.location.href;
    const price = 'R$ <?= number_format($car['preco'], 2, ',', '.') ?>';
    
    if (navigator.share) {
        navigator.share({
            title: `Confira este ${title}!`,
            text: `${title} - ${price}`,
            url: url
        }).then(() => {
            showToast('Conteúdo compartilhado com sucesso!', 'success');
        }).catch((error) => {
            console.log('Erro ao compartilhar:', error);
            fallbackShare(url);
        });
    } else {
        fallbackShare(url);
    }
}

function fallbackShare(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link copiado para a área de transferência!', 'success', 'Compartilhado!');
        }).catch(() => {
            showManualCopy(url);
        });
    } else {
        showManualCopy(url);
    }
}

function showManualCopy(url) {
    const message = `URL para compartilhar: ${url}`;
    showToast(message, 'info', 'Copie o link:');
}

// Sistema de loading para imagens
document.addEventListener('DOMContentLoaded', function() {
    const carImage = document.querySelector('.car-main-image img');
    if (carImage) {
        // Função para quando a imagem carrega
        function onImageLoad() {
            this.classList.add('loaded');
            this.style.opacity = '1';
        }
        
        // Se a imagem já carregou
        if (carImage.complete && carImage.naturalHeight !== 0) {
            onImageLoad.call(carImage);
        } else {
            // Aguardar o carregamento
            carImage.addEventListener('load', onImageLoad);
            carImage.addEventListener('error', function() {
                this.style.opacity = '1';
                this.classList.add('loaded');
            });
        }
        
        // Adicionar loading state se a imagem ainda não carregou
        if (!carImage.complete) {
            carImage.style.opacity = '0.5';
        }
    }
      // Adicionar animações de entrada aos elementos
    const animatedElements = document.querySelectorAll('.specs-card, .description-card, .price-card');
    animatedElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.1}s`;
        element.classList.add('fade-in');
    });
});

// Função para mostrar toast notifications
function showToast(message, type = 'info') {
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 300px;
        `;
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.style.cssText = `
        background: ${type === 'success' ? '#28a745' : type === 'warning' ? '#ffc107' : '#007bff'};
        color: ${type === 'warning' ? '#000' : '#fff'};
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-size: 0.9rem;
    `;
    toast.textContent = message;
    
    toastContainer.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 5000);
}

<?php else: ?>
function showLoginModal() {
    showToast('Para favoritar carros, você precisa fazer login ou criar uma conta.', 'warning', 'Login Necessário');
    
    setTimeout(() => {
        if (confirm('Deseja ir para a página de login agora?')) {
            window.location.href = '<?= BASE_URL ?>auth';
        }
    }, 2000);
}
<?php endif; ?>


</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>