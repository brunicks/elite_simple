<?php
// views/cars/index.php - Catálogo completo com paginação
$title = $title ?? 'Catálogo Completo - EliteMotors';
include_once ROOT . '/views/layouts/header.php';
?>

<style>
/* ============================================ */
/* CATÁLOGO DE CARROS - TEMA ELITE MOTORS */
/* ============================================ */

.car-catalog-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    min-height: 100vh;
    padding: 20px;
    color: #ffffff;
}

/* Header do Catálogo */
.catalog-header {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 50%, #d4af37 100%);
    color: #1a1a1a;
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(212, 175, 55, 0.3);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.catalog-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.6s ease;
}

.catalog-header:hover::before {
    left: 100%;
}

.catalog-header-content {
    position: relative;
    z-index: 1;
}

.catalog-title {
    font-size: 2.8em;
    font-weight: bold;
    margin-bottom: 15px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.catalog-subtitle {
    font-size: 1.2em;
    margin-bottom: 25px;
    opacity: 0.9;
}

.catalog-results-info {
    background: rgba(0, 0, 0, 0.1);
    padding: 15px;
    border-radius: 10px;
    margin-top: 20px;
}

.results-main {
    font-size: 1.1em;
    font-weight: 600;
    margin-bottom: 5px;
}

.results-pagination {
    font-size: 0.95em;
    opacity: 0.8;
}

/* Seção de Filtros */
.filters-section {
    background: rgba(45, 45, 45, 0.95);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

/* Collapsible Filters Styles */
.filters-header {
    margin-bottom: 20px;
}

.filter-toggle {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 100%);
    color: #1a1a1a;
    border: none;
    padding: 15px 25px;
    border-radius: 10px;
    font-size: 1.1em;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    position: relative;
    overflow: hidden;
}

.filter-toggle:hover {
    background: linear-gradient(135deg, #f4e18f 0%, #d4af37 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
}

.filter-toggle.active {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
}

.filter-toggle-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-icon {
    font-size: 1.2em;
    transition: transform 0.3s ease;
}

.active-filters-count {
    background: #e74c3c;
    color: white;
    font-size: 0.8em;
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 50%;
    min-width: 24px;
    height: 24px;
    display: none;
    align-items: center;
    justify-content: center;
    margin-left: 10px;
}

.filter-content {
    transition: all 0.3s ease;
    overflow: hidden;
}

.filters-container {
    margin-top: 20px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
    opacity: 0;
}

.filters-container.show {
    max-height: 800px; /* Adjust based on content */
    opacity: 1;
}

.filters-title {
    color: #d4af37;
    font-size: 1.5em;
    font-weight: 600;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    color: #ffffff;
    font-weight: 500;
    font-size: 0.95em;
    margin-bottom: 5px;
}

.filter-select {
    background: rgba(26, 26, 26, 0.8);
    border: 1px solid rgba(212, 175, 55, 0.3);
    color: #ffffff;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 0.95em;
    transition: all 0.3s ease;
    cursor: pointer;
    appearance: none; /* Remove default browser styling */
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"><path fill="%23d4af37" d="M2 0L0 2h4zM2 5L0 3h4z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
    padding-right: 35px; /* Make room for the custom arrow */
}

.filter-select::-ms-expand {
    display: none; /* Remove IE arrow */
}

/* Better option styling for dark theme */
.filter-select option {
    background: #1a1a1a;
    color: #ffffff;
    padding: 8px 12px;
    margin: 2px 0;
}

.filter-select option:checked {
    background: rgba(212, 175, 55, 0.2);
    color: #d4af37;
}

.filters-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

/* Enhanced filter visual feedback */
.filter-select.filter-active {
    border-color: rgba(212, 175, 55, 0.8);
    background: rgba(212, 175, 55, 0.1);
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
}

.filter-select.filter-changed {
    animation: filterHighlight 2s ease-out;
}

.filter-select.focused {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

.active-filters-count.pulse {
    animation: pulse 0.5s ease-out;
}

.filter-toggle.has-active-filters {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    border-color: rgba(39, 174, 96, 0.6);
}

.filter-toggle.has-active-filters:hover {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
}

/* Animations */
@keyframes filterHighlight {
    0% { 
        background: rgba(212, 175, 55, 0.3);
        transform: scale(1.02);
    }
    100% { 
        background: rgba(26, 26, 26, 0.8);
        transform: scale(1);
    }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Loading state animations */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 1s linear infinite;
}

/* Responsividade */
@media (max-width: 1024px) {
    .cars-grid {
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }
}

@media (max-width: 768px) {
    .car-catalog-container {
        padding: 15px;
    }
    
    .catalog-header {
        padding: 30px 20px;
    }
    
    .catalog-title {
        font-size: 2.2em;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .filters-section {
        padding: 20px;
    }
      .cars-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .car-card-details {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .car-card-actions {
        flex-direction: column;
    }
    
    .modern-btn {
        justify-content: center;
        width: 100%;
    }
    
    .pagination {
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .pagination-btn,
    .pagination-current {
        padding: 10px 12px;
        font-size: 0.9em;
    }
}

@media (max-width: 480px) {
    .catalog-title {
        font-size: 1.8em;
    }
    
    .cars-grid {
        grid-template-columns: 1fr;
    }
    
    .car-card-specs {
        justify-content: center;
    }
    
    .filters-actions {
        flex-direction: column;
    }
}

/* Alertas Modernos */
.modern-alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    border: 1px solid transparent;
}

.modern-alert-success {
    background: rgba(39, 174, 96, 0.15);
    color: #27ae60;
    border-color: rgba(39, 174, 96, 0.3);
}

.modern-alert-error {
    background: rgba(231, 76, 60, 0.15);
    color: #e74c3c;
    border-color: rgba(231, 76, 60, 0.3);
}

.modern-alert-icon {
    font-size: 1.2em;
}

.modern-alert-text {
    flex: 1;
}
</style>

<main class="car-catalog-container">
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
    <?php endif; ?>    <!-- Header do Catálogo -->
    <div class="catalog-header">
        <div class="catalog-header-content">
            <h1 class="catalog-title">🚗 Catálogo Completo</h1>
            <p class="catalog-subtitle">Explore nossa coleção completa de veículos seminovos de qualidade</p>
            <!-- Info de resultados -->
            <div class="catalog-results-info">
                <?php if (!empty($filters['search']) || !empty($filters['marca']) || !empty($filters['ano_min']) || !empty($filters['preco_min']) || !empty($filters['km_max'])): ?>
                    <p class="results-main">
                        📊 <strong><?= $total_cars ?></strong> resultado(s) encontrado(s) com filtros aplicados
                    </p>
                    <p class="results-pagination">
                        Página <?= $current_page ?> de <?= $total_pages ?>
                    </p>
                <?php else: ?>
                    <p class="results-main">
                        📊 <strong><?= $total_cars ?></strong> veículos disponíveis
                    </p>
                    <p class="results-pagination">
                        Página <?= $current_page ?> de <?= $total_pages ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>    <!-- Filtros Avançados Colapsáveis -->
    <div class="filters-section">
        <div class="filters-header">
            <button type="button" id="toggleFilters" class="filter-toggle">
                <div class="filter-toggle-content">
                    <span class="filter-icon">🔍</span>
                    <span id="filterToggleText">Mostrar Filtros Avançados</span>
                </div>
                <i class="fas fa-chevron-down filter-icon" id="filterToggleIcon"></i>
                <?php 
                $activeFiltersCount = 0;
                if (!empty($filters['search']) || !empty($filters['marca']) || !empty($filters['ano_min']) || 
                    !empty($filters['preco_min']) || !empty($filters['km_max']) || !empty($filters['combustivel']) ||
                    !empty($filters['transmissao']) || !empty($filters['cor']) || !empty($filters['portas'])) {
                    foreach ($filters as $filter) {
                        if (!empty($filter)) $activeFiltersCount++;
                    }
                }
                ?>
                <?php if ($activeFiltersCount > 0): ?>
                    <span class="active-filters-count" id="filterCount"><?= $activeFiltersCount ?></span>
                <?php endif; ?>
            </button>
        </div>
        
        <div id="filtersContainer" class="filters-container" style="display: none;">
            <form method="GET" class="filters-form" id="filtersForm">
                <h3 class="filters-title">🔍 Filtros de Busca</h3>
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">Marca</label>
                    <select name="marca" class="filter-select">
                        <option value="">Todas</option>
                        <?php foreach ($all_brands as $brand): ?>
                            <option value="<?= htmlspecialchars($brand) ?>" <?= $filters['marca'] === $brand ? 'selected' : '' ?>><?= htmlspecialchars($brand) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Ano</label>
                    <select name="ano_min" class="filter-select">
                        <option value="">Mín</option>
                        <?php for ($y = $year_ranges['max_year']; $y >= $year_ranges['min_year']; $y--): ?>
                            <option value="<?= $y ?>" <?= $filters['ano_min'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="ano_max" class="filter-select">
                        <option value="">Máx</option>
                        <?php for ($y = $year_ranges['max_year']; $y >= $year_ranges['min_year']; $y--): ?>
                            <option value="<?= $y ?>" <?= $filters['ano_max'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Preço</label>
                    <select name="preco_min" class="filter-select">
                        <option value="">Mín</option>
                        <?php for ($p = 10000; $p <= $price_ranges['max_price']; $p += 10000): ?>
                            <option value="<?= $p ?>" <?= $filters['preco_min'] == $p ? 'selected' : '' ?>>R$ <?= number_format($p, 0, ',', '.') ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="preco_max" class="filter-select">
                        <option value="">Máx</option>
                        <?php for ($p = 10000; $p <= $price_ranges['max_price']; $p += 10000): ?>
                            <option value="<?= $p ?>" <?= $filters['preco_max'] == $p ? 'selected' : '' ?>>R$ <?= number_format($p, 0, ',', '.') ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">KM Máx</label>
                    <select name="km_max" class="filter-select">
                        <option value="">Qualquer</option>
                        <?php for ($k = 10000; $k <= 200000; $k += 10000): ?>
                            <option value="<?= $k ?>" <?= $filters['km_max'] == $k ? 'selected' : '' ?>><?= number_format($k, 0, ',', '.') ?> km</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Combustível</label>
                    <select name="combustivel" class="filter-select">
                        <option value="">Todos</option>
                        <option value="flex" <?= $filters['combustivel'] === 'flex' ? 'selected' : '' ?>>Flex</option>
                        <option value="gasolina" <?= $filters['combustivel'] === 'gasolina' ? 'selected' : '' ?>>Gasolina</option>
                        <option value="etanol" <?= $filters['combustivel'] === 'etanol' ? 'selected' : '' ?>>Etanol</option>
                        <option value="diesel" <?= $filters['combustivel'] === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                        <option value="elétrico" <?= $filters['combustivel'] === 'elétrico' ? 'selected' : '' ?>>Elétrico</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Transmissão</label>
                    <select name="transmissao" class="filter-select">
                        <option value="">Todas</option>
                        <option value="manual" <?= $filters['transmissao'] === 'manual' ? 'selected' : '' ?>>Manual</option>
                        <option value="automática" <?= $filters['transmissao'] === 'automática' ? 'selected' : '' ?>>Automática</option>
                        <option value="cvt" <?= $filters['transmissao'] === 'cvt' ? 'selected' : '' ?>>CVT</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Cor</label>
                    <select name="cor" class="filter-select">
                        <option value="">Todas</option>
                        <option value="preto" <?= $filters['cor'] === 'preto' ? 'selected' : '' ?>>Preto</option>
                        <option value="branco" <?= $filters['cor'] === 'branco' ? 'selected' : '' ?>>Branco</option>
                        <option value="prata" <?= $filters['cor'] === 'prata' ? 'selected' : '' ?>>Prata</option>
                        <option value="cinza" <?= $filters['cor'] === 'cinza' ? 'selected' : '' ?>>Cinza</option>
                        <option value="azul" <?= $filters['cor'] === 'azul' ? 'selected' : '' ?>>Azul</option>
                        <option value="vermelho" <?= $filters['cor'] === 'vermelho' ? 'selected' : '' ?>>Vermelho</option>
                        <option value="verde" <?= $filters['cor'] === 'verde' ? 'selected' : '' ?>>Verde</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Portas</label>
                    <select name="portas" class="filter-select">
                        <option value="">Todas</option>
                        <option value="2" <?= $filters['portas'] == 2 ? 'selected' : '' ?>>2</option>
                        <option value="4" <?= $filters['portas'] == 4 ? 'selected' : '' ?>>4</option>
                    </select>
                </div>
            </div>            <div class="filters-actions">
                <button type="submit" class="modern-btn modern-btn-primary">Filtrar</button>
                <a href="<?= BASE_URL ?>car" class="modern-btn modern-btn-outline">Limpar Filtros</a>
            </div>
            </form>
        </div>
    </div><!-- Ações do usuário -->
    <?php if ($is_logged_in): ?>        <div class="catalog-user-actions">
            <a href="<?= ($_SESSION['user_type'] ?? '') === 'admin' ? BASE_URL . 'dashboard' : BASE_URL . 'dashboard/user' ?>" class="modern-btn modern-btn-info">
                📋 Meu Dashboard
            </a>
            <a href="<?= BASE_URL ?>favorite" class="modern-btn modern-btn-success">
                ❤️ Meus Favoritos
            </a>        </div>
    <?php else: ?>        
        <div class="catalog-guest-actions">
            <h3 class="guest-actions-title">💡 Dica</h3>
            <p class="guest-actions-text">Faça login para favoritar carros e ter acesso a funcionalidades exclusivas!</p>
            <a href="<?= BASE_URL ?>auth" class="modern-btn modern-btn-primary">
                👤 Fazer Login / Criar Conta
            </a>
        </div>
    <?php endif; ?>

    <!-- Grid de Carros -->
    <div class="cars-grid">
        <?php if (empty($cars)): ?>
            <div class="no-cars-found">
                <h3 class="no-cars-title">🔍 Nenhum carro encontrado</h3>
                <p class="no-cars-message">Nenhum veículo corresponde aos filtros selecionados.</p>
            </div>        <?php else: ?>
            <?php foreach ($cars as $car): ?>
                <div class="car-card">
                    <div class="car-card-image">
                        <img src="<?= $car['imagem'] ? BASE_URL . 'uploads/' . htmlspecialchars($car['imagem']) : 'https://via.placeholder.com/320x220?text=Sem+Imagem' ?>"
                             alt="<?= htmlspecialchars($car['modelo']) ?>"
                             class="car-image"
                             onerror="this.src='https://via.placeholder.com/320x220?text=Sem+Imagem'">
                        <div class="car-year-badge">
                            <?= $car['ano'] ?>
                        </div>
                    </div>
                    <div class="car-card-info">
                        <h3 class="car-card-title">
                            <?= htmlspecialchars($car['marca'] . ' ' . $car['modelo']) ?>
                        </h3>                        <div class="car-card-details">
                            <span class="car-detail"><i class="fas fa-calendar"></i> <?= $car['ano'] ?></span>
                            <span class="car-detail"><i class="fas fa-road"></i> <?= number_format($car['km'], 0, ',', '.') ?> km</span>
                            <span class="car-detail"><i class="fas fa-gas-pump"></i> <?= $car['combustivel'] ? ucfirst($car['combustivel']) : 'N/A' ?></span>
                            <span class="car-detail"><i class="fas fa-cogs"></i> <?= $car['transmissao'] ? ucfirst($car['transmissao']) : 'N/A' ?></span>
                            <span class="car-detail"><i class="fas fa-door-open"></i> <?= $car['portas'] ?? 'N/A' ?> portas</span>
                            <span class="car-detail"><i class="fas fa-palette"></i> <?= $car['cor'] ? ucfirst($car['cor']) : 'N/A' ?></span>
                        </div>
                        <div class="car-card-specs">
                            <span class="spec-item"><i class="fas fa-tachometer-alt"></i> <?= $car['cv'] ?? 'N/A' ?> cv</span>
                            <span class="spec-item"><i class="fas fa-leaf"></i> <?= $car['consumo_medio'] ?? 'N/A' ?> km/l</span>
                        </div>
                        <div class="car-card-price">R$ <?= number_format($car['preco'], 2, ',', '.') ?></div>
                        <div class="car-card-actions">
                            <a href="<?= BASE_URL ?>car/details/<?= $car['id'] ?>" class="modern-btn modern-btn-primary"><i class="fas fa-eye"></i> Ver Detalhes</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button class="modern-btn modern-btn-secondary <?= $car['is_favorite'] ? 'favorited' : '' ?>" onclick="toggleFavorite(<?= $car['id'] ?>, this)" data-car-id="<?= $car['id'] ?>" title="<?= $car['is_favorite'] ? 'Remover dos favoritos' : 'Adicionar aos favoritos' ?>">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="modern-btn modern-btn-info <?= $car['in_comparison'] ? 'in-comparison' : '' ?>" onclick="toggleComparison(<?= $car['id'] ?>, this)" data-car-id="<?= $car['id'] ?>" title="<?= $car['in_comparison'] ? 'Remover da comparação' : 'Adicionar à comparação' ?>">
                                    <i class="fas fa-balance-scale"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <!-- Paginação -->
    <?php if ($total_pages > 1): ?>
        <?php
        // Criar query string preservando filtros
        $filterParams = [];
        foreach ($filters as $key => $value) {
            if (!empty($value) && $key !== 'page') {
                $filterParams[] = urlencode($key) . '=' . urlencode($value);
            }
        }
        $filterQuery = !empty($filterParams) ? '&' . implode('&', $filterParams) : '';
        ?>
        <div class="pagination-wrapper">
            <div class="pagination">                
                <!-- Primeira página -->
                <?php if ($current_page > 1): ?>
                    <a href="<?= BASE_URL ?>car?page=1<?= $filterQuery ?>" 
                       class="pagination-btn pagination-btn-nav" 
                       title="Primeira página">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                <?php endif; ?>
                
                <!-- Página anterior -->
                <?php if ($current_page > 1): ?>
                    <a href="<?= BASE_URL ?>car?page=<?= ($current_page - 1) ?><?= $filterQuery ?>" 
                       class="pagination-btn pagination-btn-nav" 
                       title="Página anterior">
                        <i class="fas fa-angle-left"></i> Anterior
                    </a>
                <?php endif; ?>
                
                <!-- Páginas numéricas -->
                <?php
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                
                // Ajustar para sempre mostrar 5 páginas quando possível
                if ($end_page - $start_page < 4) {
                    if ($start_page == 1) {
                        $end_page = min($total_pages, $start_page + 4);
                    } else {
                        $start_page = max(1, $end_page - 4);
                    }
                }
                  for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <?php if ($i == $current_page): ?>
                        <span class="pagination-current">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>car?page=<?= $i ?><?= $filterQuery ?>" 
                           class="pagination-btn pagination-btn-number">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <!-- Próxima página -->
                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= BASE_URL ?>car?page=<?= ($current_page + 1) ?><?= $filterQuery ?>" 
                       class="pagination-btn pagination-btn-nav"                       title="Próxima página">
                        Próxima <i class="fas fa-angle-right"></i>
                    </a>
                <?php endif; ?>
                
                <!-- Última página -->
                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= BASE_URL ?>car?page=<?= $total_pages ?><?= $filterQuery ?>" 
                       class="pagination-btn pagination-btn-nav" 
                       title="Última página">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Info da paginação -->
            <div class="pagination-info">
                Mostrando 
                <strong><?= min(($current_page - 1) * $per_page + 1, $total_cars) ?></strong>
                até 
                <strong><?= min($current_page * $per_page, $total_cars) ?></strong>
                de 
                <strong><?= $total_cars ?></strong>
                veículo(s)
            </div>
        </div>
    <?php endif; ?>

    <!-- Call to Action -->
    <?php if (!$is_logged_in && !empty($cars)): ?>
        <div class="catalog-cta">
            <h3 class="cta-title">✨ Gostou de algum veículo?</h3>
            <p class="cta-text">
                Crie sua conta gratuita para favoritar carros e ter acesso a informações exclusivas!
            </p>
            <a href="<?= BASE_URL ?>auth" class="cta-btn">
                🚀 Criar Conta Grátis
            </a>
        </div>
    <?php endif; ?>

</main>

<script src="<?= BASE_URL ?>js/script.js"></script>

<script>
// Função para toggle de favoritos
function toggleFavorite(carId, button) {
    if (!button || button.disabled) return;
    
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
            const icon = button.querySelector('i');
            if (data.action === 'added') {
                button.classList.add('favorited');
                button.title = 'Remover dos favoritos';
                showToast('Veículo adicionado aos favoritos!', 'success');
            } else {
                button.classList.remove('favorited');
                button.title = 'Adicionar aos favoritos';
                showToast('Veículo removido dos favoritos!', 'info');
            }
        } else {
            showToast(data.message || 'Erro ao processar favorito', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao processar favorito', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}

// Função para toggle de comparação
function toggleComparison(carId, button) {
    if (!button || button.disabled) return;
    
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch('<?= BASE_URL ?>comparison/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'car_id=' + carId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.action === 'added') {
                button.classList.add('in-comparison');
                button.title = 'Remover da comparação';
                showToast('Veículo adicionado à comparação!', 'success');
            } else {
                button.classList.remove('in-comparison');
                button.title = 'Adicionar à comparação';
                showToast('Veículo removido da comparação!', 'info');
            }
        } else {
            showToast(data.message || 'Erro ao processar comparação', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao processar comparação', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}

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
    const colors = {
        success: '#27ae60',
        error: '#e74c3c',
        info: '#3498db',
        warning: '#f39c12'
    };
    
    toast.style.cssText = `
        background: ${colors[type] || colors.info};
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-size: 0.9rem;
        font-weight: 500;
    `;
    toast.textContent = message;
    
    toastContainer.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Remover após 4 segundos
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 4000);
}

// Efeitos visuais nos cards
document.addEventListener('DOMContentLoaded', function() {
    // Animações suaves nos cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.car-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
    
    // Efeitos de hover na paginação
    document.querySelectorAll('.pagination-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            if (!this.classList.contains('pagination_current')) {
                this.style.transform = 'translateY(-2px)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            if (!this.classList.contains('pagination_current')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
});

<?php if (!$is_logged_in): ?>
// Para usuários não logados
function showLoginAlert(action) {
    if (confirm(`Para ${action} carros, você precisa fazer login. Deseja ir para a página de login?`)) {
        window.location.href = '<?= BASE_URL ?>auth';
    }
}

// Sobrescrever funções para usuários não logados
function toggleFavorite(carId, button) {
    showLoginAlert('favoritar');
}

function toggleComparison(carId, button) {
    showLoginAlert('comparar');
}
<?php endif; ?>
</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
