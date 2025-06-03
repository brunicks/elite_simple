<?php
// views/dashboard/user.php - User Personal Dashboard with Financial Features
$title = $title ?? 'Minha Área - EliteMotors';
include_once ROOT . '/views/layouts/header.php';
?>

<style>
.user-dashboard {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    min-height: 100vh;
    padding: 20px 0;
    color: #ffffff;
}

.dashboard-header {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 50%, #d4af37 100%);
    color: #1a1a1a;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 8px 32px rgba(212, 175, 55, 0.3);
}

.stats-grid {
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

.stat-number {
    font-size: 2.5em;
    font-weight: bold;
    margin-bottom: 10px;
    color: #d4af37;
}

.stat-label {
    color: #cccccc;
    font-size: 0.9em;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
}

.feature-card {
    background: rgba(45, 45, 45, 0.9);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(10px);
}

.feature-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    font-size: 1.2em;
    font-weight: 600;
    color: #ffffff;
}

.calculator-form {
    display: grid;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 5px;
    font-weight: 500;
    color: #cccccc;
}

.form-group input, .form-group select {
    padding: 12px;
    border: 1px solid rgba(212, 175, 55, 0.5);
    border-radius: 6px;
    font-size: 14px;
    background: rgba(26, 26, 26, 0.8);
    color: #ffffff;
}

.form-group input:focus, .form-group select:focus {
    outline: none;
    border-color: #d4af37;
    box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.3);
}

.calculator-result {
    background: rgba(26, 26, 26, 0.8);
    padding: 20px;
    border-radius: 8px;
    margin-top: 15px;
    display: none;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.result-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.result-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.result-label {
    font-weight: 500;
}

.result-value {
    font-weight: 600;
    color: #007bff;
}

.simulations-list {
    max-height: 400px;
    overflow-y: auto;
}

.simulation-item {
    background: rgba(26, 26, 26, 0.8);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 4px solid #d4af37;
    border: 1px solid rgba(212, 175, 55, 0.3);
}

.simulation-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 10px;
}

.simulation-title {
    font-weight: 600;
    color: #ffffff;
}

.simulation-date {
    font-size: 0.85em;
    color: #cccccc;
}

.simulation-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 10px;
}

.simulation-detail {
    font-size: 0.9em;
}

.cars-carousel {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding: 10px 0;
}

.car-mini-card {
    min-width: 200px;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.car-mini-image {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 10px;
}

.car-mini-title {
    font-size: 0.9em;
    font-weight: 500;
    margin-bottom: 5px;
}

.car-mini-price {
    font-size: 0.85em;
    color: #007bff;
    font-weight: 600;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, #d4af37 0%, #f4e18f 50%, #d4af37 100%);
    color: #1a1a1a;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #c49931 0%, #e6d482 50%, #c49931 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

@media (max-width: 768px) {
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .simulation-details {
        grid-template-columns: 1fr;
    }
}
</style>

<main class="user-dashboard">    <div class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="modern-alert modern-alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="modern-alert modern-alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>🎯 Minha Área Personalizada</h1>
            <p>Olá, <strong><?= htmlspecialchars($user_name) ?></strong>! Gerencie suas simulações e favoritos.</p>
              <div style="display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap;">
                <a href="<?= BASE_URL ?>favorite" class="modern-btn modern-btn-secondary">
                    ❤️ Meus Favoritos
                </a>
                <a href="<?= BASE_URL ?>car" class="modern-btn modern-btn-secondary">
                    🚗 Catálogo Completo
                </a>
                <a href="<?= BASE_URL ?>" class="modern-btn modern-btn-secondary">
                    🏠 Página Inicial
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" style="color: #dc3545;"><?= $favorites_count ?></div>
                <div class="stat-label">Carros Favoritados</div>
            </div>            <div class="stat-card">
                <div class="stat-number" style="color: #28a745;"><?= $simulations_count ?></div>
                <div class="stat-label">Simulações Salvas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #007bff;"><?= $recently_viewed_count ?></div>
                <div class="stat-label">Carros Visitados</div>
            </div>
        </div>

        <!-- Main Features Grid -->
        <div class="features-grid">
            <!-- Financing Calculator -->
            <div class="feature-card">
                <div class="feature-header">
                    🧮 Calculadora de Financiamento
                </div>
                
                <form class="calculator-form" id="financingForm">
                    <div class="form-group">
                        <label>Carro (opcional)</label>
                        <select name="car_id" id="carSelect">
                            <option value="">Simulação personalizada</option>
                            <?php foreach ($all_cars as $car): ?>
                                <option value="<?= $car['id'] ?>" data-price="<?= $car['preco'] ?>">
                                    <?= htmlspecialchars($car['marca'] . ' ' . $car['modelo'] . ' - R$ ' . number_format($car['preco'], 2, ',', '.')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Valor do Veículo (R$)</label>
                        <input type="number" name="car_price" id="carPrice" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Entrada (R$)</label>
                        <input type="number" name="down_payment" id="downPayment" step="0.01" min="0" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Taxa de Juros (% ao ano)</label>
                        <input type="number" name="interest_rate" id="interestRate" step="0.01" min="0" value="12" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Prazo (meses)</label>
                        <select name="term_months" id="termMonths" required>
                            <option value="12">12 meses</option>
                            <option value="24">24 meses</option>
                            <option value="36" selected>36 meses</option>
                            <option value="48">48 meses</option>
                            <option value="60">60 meses</option>
                            <option value="72">72 meses</option>
                        </select>
                    </div>
                      <button type="button" class="modern-btn modern-btn-primary" onclick="calculateFinancing()">
                        🧮 Calcular
                    </button>
                    
                    <div class="calculator-result" id="calculatorResult">
                        <h4>Resultado da Simulação</h4>
                        <div class="result-item">
                            <span class="result-label">Parcela Mensal:</span>
                            <span class="result-value" id="monthlyPayment">R$ 0,00</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Valor Total:</span>
                            <span class="result-value" id="totalAmount">R$ 0,00</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Total de Juros:</span>
                            <span class="result-value" id="totalInterest">R$ 0,00</span>
                        </div>
                          <button type="button" class="modern-btn modern-btn-success" onclick="saveSimulation()">
                            💾 Salvar Simulação
                        </button>
                    </div>
                </form>
            </div>

            <!-- Saved Simulations -->
            <div class="feature-card">
                <div class="feature-header">
                    📊 Simulações Salvas
                    <span style="margin-left: auto; font-size: 0.8em; color: #666;">
                        Últimas <?= count($recent_simulations) ?>
                    </span>
                </div>
                
                <div class="simulations-list">
                    <?php if (empty($recent_simulations)): ?>
                        <div style="text-align: center; padding: 40px 20px; color: #666;">
                            <p>Nenhuma simulação salva ainda.</p>
                            <p style="font-size: 0.9em;">Use a calculadora ao lado para criar sua primeira simulação!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_simulations as $simulation): ?>
                            <div class="simulation-item">
                                <div class="simulation-header">
                                    <div class="simulation-title"><?= htmlspecialchars($simulation['car_name']) ?></div>                                    <button class="modern-btn modern-btn-danger" style="padding: 5px 10px; font-size: 12px;" 
                                            onclick="deleteSimulation(<?= $simulation['id'] ?>)">
                                        🗑️
                                    </button>
                                </div>
                                
                                <div class="simulation-details">
                                    <div class="simulation-detail">
                                        <strong>Valor:</strong> R$ <?= number_format($simulation['car_price'], 2, ',', '.') ?>
                                    </div>
                                    <div class="simulation-detail">
                                        <strong>Entrada:</strong> R$ <?= number_format($simulation['down_payment'], 2, ',', '.') ?>
                                    </div>
                                    <div class="simulation-detail">
                                        <strong>Parcela:</strong> R$ <?= number_format($simulation['monthly_payment'], 2, ',', '.') ?>
                                    </div>
                                    <div class="simulation-detail">
                                        <strong>Prazo:</strong> <?= $simulation['term_months'] ?> meses
                                    </div>
                                </div>
                                
                                <div class="simulation-date">
                                    Criada em <?= date('d/m/Y H:i', strtotime($simulation['created_at'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recently Viewed Cars -->
        <?php if (!empty($recently_viewed)): ?>
        <div class="feature-card">
            <div class="feature-header">
                👁️ Carros Visitados Recentemente
            </div>
            
            <div class="cars-carousel">
                <?php foreach ($recently_viewed as $car): ?>
                    <div class="car-mini-card">
                        <?php if ($car['imagem']): ?>
                            <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($car['imagem']) ?>" 
                                 alt="<?= htmlspecialchars($car['modelo']) ?>"
                                 class="car-mini-image"
                                 onerror="this.src='https://via.placeholder.com/200x100?text=Sem+Imagem'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/200x100?text=Sem+Imagem" 
                                 alt="Sem imagem" class="car-mini-image">
                        <?php endif; ?>
                        
                        <div class="car-mini-title">
                            <?= htmlspecialchars($car['marca'] . ' ' . $car['modelo']) ?>
                        </div>
                        <div class="car-mini-price">
                            R$ <?= number_format($car['preco'], 2, ',', '.') ?>
                        </div>
                          <a href="<?= BASE_URL ?>car/details/<?= $car['car_id'] ?>" 
                           class="modern-btn modern-btn-primary" style="margin-top: 10px; padding: 5px 10px; font-size: 12px;">
                            Ver Detalhes
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>    </div>
</main>

<script>
// Update car price when selecting from dropdown
document.getElementById('carSelect').addEventListener('change', function() {
    const price = this.selectedOptions[0]?.dataset.price || '';
    document.getElementById('carPrice').value = price;
});

// Calculate financing
function calculateFinancing() {
    const carPrice = parseFloat(document.getElementById('carPrice').value) || 0;
    const downPayment = parseFloat(document.getElementById('downPayment').value) || 0;
    const interestRate = parseFloat(document.getElementById('interestRate').value) || 0;
    const termMonths = parseInt(document.getElementById('termMonths').value) || 0;
    
    if (carPrice <= 0 || termMonths <= 0) {
        showModal('Por favor, preencha todos os campos obrigatórios', 'error');
        return;
    }
    
    fetch('<?= BASE_URL ?>dashboard/calculateFinancing', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `car_price=${carPrice}&down_payment=${downPayment}&interest_rate=${interestRate}&term_months=${termMonths}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('monthlyPayment').textContent = 
                'R$ ' + data.calculation.monthly_payment.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('totalAmount').textContent = 
                'R$ ' + data.calculation.total_amount.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            document.getElementById('totalInterest').textContent = 
                'R$ ' + data.calculation.total_interest.toLocaleString('pt-BR', {minimumFractionDigits: 2});
            
            document.getElementById('calculatorResult').style.display = 'block';
        } else {
            showModal(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModal('Erro ao calcular financiamento', 'error');
    });
}

// Save simulation
function saveSimulation() {
    const formData = new FormData(document.getElementById('financingForm'));
    formData.append('monthly_payment', document.getElementById('monthlyPayment').textContent.replace(/[^\d,]/g, '').replace(',', '.'));
    formData.append('total_amount', document.getElementById('totalAmount').textContent.replace(/[^\d,]/g, '').replace(',', '.'));
    
    const carSelect = document.getElementById('carSelect');
    const carName = carSelect.value ? 
        carSelect.selectedOptions[0].textContent.split(' - ')[0] : 
        'Simulação Personalizada';
    formData.append('car_name', carName);
    
    fetch('<?= BASE_URL ?>dashboard/saveSimulation', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showModal(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showModal('Erro ao salvar simulação', 'error');
    });
}

// Delete simulation
function deleteSimulation(simulationId) {
    if (!confirm('Tem certeza que deseja excluir esta simulação?')) return;
    
    fetch('<?= BASE_URL ?>dashboard/deleteSimulation', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `simulation_id=${simulationId}`
    })
    .then(response => response.json())
    .then(data => {
        showModal(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            setTimeout(() => location.reload(), 1500);
        }    })
    .catch(error => {
        console.error('Error:', error);
        showModal('Erro ao excluir simulação', 'error');
    });
}
</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>