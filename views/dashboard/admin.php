<?php
// views/dashboard/admin.php - Admin CRUD Panel
$title = $title ?? 'Painel Admin - EliteMotors';
include_once ROOT . '/views/layouts/header.php';
?>

<style>
/* Admin Panel Elite Motors Styling */
.admin-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    min-height: 100vh;
    padding: 20px;
    color: #ffffff;
}

.admin-panel-header {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 50%, #d4af37 100%);
    color: #1a1a1a;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(212, 175, 55, 0.3);
}

.admin-panel-header h1 {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 10px;
}

.admin-panel-header p {
    font-size: 1.1em;
    margin-bottom: 20px;
}

.admin-quick-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.stats-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: rgba(45, 45, 45, 0.9);
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.2);
    border-color: rgba(212, 175, 55, 0.6);
}

.stat-card h3 {
    font-size: 2.2em;
    font-weight: bold;
    margin-bottom: 10px;
    color: #d4af37;
}

.stat-card p {
    color: #cccccc;
    font-size: 0.9em;
}

.stat-success h3 { color: #28a745; }
.stat-warning h3 { color: #ffc107; }
.stat-info h3 { color: #17a2b8; }

.admin-controls {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.admin-controls-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.search-section, .filter-section {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.search-section label, .filter-section label {
    font-weight: 600;
    color: #ffffff;
    font-size: 1.1em;
}

.search-form {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.search-form input[type="text"] {
    padding: 12px;
    border: 1px solid rgba(212, 175, 55, 0.5);
    border-radius: 6px;
    font-size: 14px;
    background: rgba(26, 26, 26, 0.8);
    color: #ffffff;
    flex: 1;
    min-width: 200px;
}

.search-form input[type="text"]:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.3);
}

.filter-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.modern-btn {
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.modern-btn-primary {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 50%, #d4af37 100%);
    color: #1a1a1a;
}

.modern-btn-primary:hover {
    background: linear-gradient(135deg, #c49931 0%, #e6d482 50%, #c49931 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
}

.modern-btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.modern-btn-success:hover {
    background: linear-gradient(135deg, #218838, #1fa07d);
    transform: translateY(-2px);
}

.modern-btn-warning {
    background: linear-gradient(135deg, #ffc107, #ffeb3b);
    color: #1a1a1a;
}

.modern-btn-warning:hover {
    background: linear-gradient(135deg, #e0a800, #f57f17);
    transform: translateY(-2px);
}

.modern-btn-danger {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    color: white;
}

.modern-btn-danger:hover {
    background: linear-gradient(135deg, #c82333, #c0392b);
    transform: translateY(-2px);
}

.modern-btn-secondary {
    background: rgba(108, 117, 125, 0.8);
    color: white;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.modern-btn-secondary:hover {
    background: rgba(212, 175, 55, 0.2);
    border-color: rgba(212, 175, 55, 0.6);
}

.modern-btn-outline {
    background: transparent;
    color: #d4af37;
    border: 2px solid rgba(212, 175, 55, 0.5);
}

.modern-btn-outline:hover {
    background: rgba(212, 175, 55, 0.1);
    border-color: #d4af37;
}

.modern-btn-info {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    color: white;
}

.modern-btn-info:hover {
    background: linear-gradient(135deg, #138496, #1fa07d);
    transform: translateY(-2px);
}

.cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    grid-auto-rows: minmax(520px, auto);
    gap: 25px;
    margin-bottom: 30px;
    align-items: start;
}

.car-card {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.2);
    border-color: rgba(212, 175, 55, 0.6);
}

.car-card.car-inactive {
    opacity: 0.7;
    border-color: rgba(220, 53, 69, 0.5);
}

.inactive-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: bold;
    z-index: 10;
}

.car-card-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.car-card-image img {
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
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9em;
}

.car-card-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.car-card-title {
    font-size: 1.3em;
    color: #d4af37;
    margin: 0 0 15px 0;
    font-weight: 600;
}

.car-card-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 15px;
}

.car-detail {
    font-size: 0.9em;
    color: #cccccc;
    display: flex;
    align-items: center;
    gap: 6px;
}

.car-detail i {
    color: #d4af37;
    width: 16px;
}

.car-card-price {
    font-size: 1.6em;
    font-weight: bold;
    color: #28a745;
    margin-bottom: 20px;
    text-align: center;
    padding: 15px;
    background: rgba(26, 26, 26, 0.8);
    border-radius: 8px;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.car-card-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: auto;
}

.car-card-actions .modern-btn {
    flex: 1;
    justify-content: center;
    padding: 8px 12px;
    font-size: 0.85em;
}

.no-cars {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 40px;
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.no-cars h3 {
    font-size: 2em;
    color: #ffffff;
    margin-bottom: 15px;
}

.no-cars p {
    color: #cccccc;
    font-size: 1.1em;
    margin-bottom: 25px;
}

@media (max-width: 768px) {
    .admin-controls-content {
        grid-template-columns: 1fr;
    }
    
    .stats-overview {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .cars-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-quick-actions {
        justify-content: center;
    }
    
    .search-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-buttons {
        justify-content: center;
    }
}
</style>

<main class="admin-container">    <?php if (isset($_SESSION['error'])): ?>
        <div class="modern-alert modern-alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="modern-alert modern-alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?><!-- Admin Panel Header -->
    <div class="admin-panel-header">
        <h1> Painel Administrativo</h1>
        <p>Bem-vindo, <strong><?= htmlspecialchars($user_name) ?></strong>! Gerencie o estoque completo de veículos da concessionária.</p>
        
        <!-- Quick Actions -->        <div class="admin-quick-actions">
            <a href="<?= BASE_URL ?>car/add" class="modern-btn modern-btn-success">
                ➕ Adicionar Novo Carro
            </a>
            <a href="<?= BASE_URL ?>user" class="modern-btn modern-btn-warning">
                👥 Gerenciar Usuários
            </a>
            <a href="<?= BASE_URL ?>dashboard/stats" class="modern-btn modern-btn-info">
                📊 Ver Estatísticas
            </a>
        </div>
    </div>    <!-- Stats Overview -->
    <div class="stats-overview">
        <div class="stat-card stat-success">
            <h3><?= $active_cars ?></h3>
            <p>Carros Ativos</p>
        </div>
        <div class="stat-card stat-warning">
            <h3><?= $inactive_cars ?></h3>
            <p>Carros Desativados</p>
        </div>
        <div class="stat-card stat-info">
            <h3><?= $total_cars ?></h3>
            <p>Total de Carros</p>
        </div>
    </div>    <!-- Search and Filter Controls -->
    <div class="admin-controls">
        <div class="admin-controls-content">
            <!-- Search Form -->
            <div class="search-section">
                <label for="search"> Buscar Carros:</label>
                <form method="GET" class="search-form">
                    <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                    <input type="text" id="search" name="search" placeholder="Buscar por modelo, marca..." 
                           value="<?= htmlspecialchars($search) ?>">                    <button type="submit" class="modern-btn modern-btn-primary">Buscar</button>
                    <?php if (!empty($search)): ?>
                        <a href="<?= BASE_URL ?>dashboard?filter=<?= htmlspecialchars($filter) ?>" class="modern-btn modern-btn-secondary">Limpar</a>
                    <?php endif; ?>
                </form>
            </div>            <!-- Filter Controls -->
            <div class="filter-section">
                <label> Filtrar por Status:</label>
                <div class="filter-buttons">
                    <a href="<?= BASE_URL ?>dashboard?search=<?= urlencode($search) ?>&filter=all" 
                       class="modern-btn <?= $filter === 'all' ? 'modern-btn-primary' : 'modern-btn-outline' ?>">Todos</a>
                    <a href="<?= BASE_URL ?>dashboard?search=<?= urlencode($search) ?>&filter=active" 
                       class="modern-btn <?= $filter === 'active' ? 'modern-btn-success' : 'modern-btn-outline' ?>">Ativos</a>
                    <a href="<?= BASE_URL ?>dashboard?search=<?= urlencode($search) ?>&filter=inactive" 
                       class="modern-btn <?= $filter === 'inactive' ? 'modern-btn-danger' : 'modern-btn-outline' ?>">Inativos</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cars Grid -->
    <div class="cars-grid">
        <?php if (empty($cars)): ?>        <div class="no-cars">
            <h3>Nenhum carro encontrado</h3>
            <p>
                <?php if (!empty($search)): ?>
                    Tente alterar os termos de busca ou filtros.
                <?php else: ?>
                    O estoque está vazio. Adicione o primeiro veículo.
                <?php endif; ?>
            </p>
            <a href="<?= BASE_URL ?>car/add" class="modern-btn modern-btn-primary" style="margin-top: 15px;">➕ Adicionar Primeiro Carro</a>
        </div>        <?php else: ?>
            <?php foreach ($cars as $car): ?>
                <div class="car-card <?= $car['ativo'] == 0 ? 'car-inactive' : '' ?>">
                    <?php if ($car['ativo'] == 0): ?>
                        <div class="inactive-badge">
                            <span>🚫 DESATIVADO</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="car-card-image">                        <img src="<?= $car['imagem'] ? BASE_URL . 'uploads/cars/' . htmlspecialchars($car['imagem']) : BASE_URL . 'assets/images/car-placeholder.jpg' ?>"
                             alt="<?= htmlspecialchars($car['modelo']) ?>"
                             class="car-image"
                             onerror="this.src='<?= BASE_URL ?>assets/images/car-placeholder.jpg'">
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
                            <span class="car-detail"><i class="fas fa-gas-pump"></i> <?= isset($car['combustivel']) && $car['combustivel'] ? ucfirst($car['combustivel']) : 'N/A' ?></span>
                            <span class="car-detail"><i class="fas fa-cogs"></i> <?= isset($car['transmissao']) && $car['transmissao'] ? ucfirst($car['transmissao']) : 'N/A' ?></span>
                        </div>
                        <div class="car-card-price">R$ <?= number_format($car['preco'], 2, ',', '.') ?></div>
                        
                        <!-- Admin Actions -->
                        <div class="car-card-actions admin-actions">
                            <?php if ($car['ativo'] == 1): ?>
                                <!-- Active car actions -->
                                <a href="<?= BASE_URL ?>car/edit/<?= $car['id'] ?>" class="modern-btn modern-btn-warning"><i class="fas fa-edit"></i> Editar</a>
                                <button onclick="deleteCar(<?= $car['id'] ?>)" class="modern-btn modern-btn-secondary"><i class="fas fa-eye-slash"></i> Desativar</button>
                            <?php else: ?>
                                <!-- Inactive car actions -->
                                <button onclick="reactivateCar(<?= $car['id'] ?>)" class="modern-btn modern-btn-success"><i class="fas fa-eye"></i> Reativar</button>
                                <button onclick="hardDeleteCar(<?= $car['id'] ?>)" class="modern-btn modern-btn-danger"><i class="fas fa-trash"></i> Deletar</button>
                                <a href="<?= BASE_URL ?>car/edit/<?= $car['id'] ?>" class="modern-btn modern-btn-warning"><i class="fas fa-edit"></i> Editar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script src="<?= BASE_URL ?>js/script.js"></script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
