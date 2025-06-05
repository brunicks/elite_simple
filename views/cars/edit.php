<?php
// views/cars/edit.php - Formulário para editar carro
$title = $title ?? 'Editar Carro';
include_once ROOT . '/views/layouts/header.php';
?>

<main class="car-form-container">
    <!-- Cabeçalho da página -->
    <div class="car-form-header-section">
        <div class="car-form-header">
            <div class="car-form-title-group">
                <h1 class="car-form-main-title">
                     Editar Carro
                </h1>
                <p class="car-form-subtitle">
                    Editando: <strong><?= htmlspecialchars($car['marca'] . ' ' . $car['modelo']) ?></strong>
                </p>
            </div>
            <div class="car-form-nav-actions">
                <a href="<?= BASE_URL ?>dashboard" class="car-form-btn car-form-btn-secondary">
                    ← Voltar ao Dashboard
                </a>
            </div>
        </div>

        <!-- Alertas de erro/sucesso -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="modern-alert modern-alert-error">
                <span class="modern-alert-icon">⚠️</span>
                <span class="modern-alert-text"><?= htmlspecialchars($_SESSION['error']) ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Formulário de edição -->
        <div class="car-form-card">
            <div class="car-form-header">
                <h2 class="car-form-title"> Dados do Veículo</h2>
            </div>
            
            <form method="POST" action="<?= BASE_URL ?>car/update/<?= $car['id'] ?>" enctype="multipart/form-data" id="editCarForm" class="car-form">
                <div class="car-form-row">
                    <!-- Marca -->
                    <div class="car-form-field">
                        <label for="marca" class="car-form-label">
                            Marca <span class="car-form-required">*</span>
                        </label>
                        <input type="text" 
                               class="car-form-input" 
                               id="marca" 
                               name="marca" 
                               value="<?= htmlspecialchars($car['marca']) ?>"
                               required>
                        <div class="car-form-help-text">
                            Digite a marca do veículo
                        </div>
                    </div>

                    <!-- Modelo -->
                    <div class="car-form-field">
                        <label for="modelo" class="car-form-label">
                            Modelo <span class="car-form-required">*</span>
                        </label>
                        <input type="text" 
                               class="car-form-input" 
                               id="modelo" 
                               name="modelo" 
                               value="<?= htmlspecialchars($car['modelo']) ?>"
                               required>
                        <div class="car-form-help-text">
                            Digite o modelo do veículo
                        </div>
                    </div>
                </div>

                <div class="car-form-row">
                    <!-- Ano -->
                    <div class="car-form-field">
                        <label for="ano" class="car-form-label">
                            Ano <span class="car-form-required">*</span>
                        </label>
                        <input type="number" 
                               class="car-form-input" 
                               id="ano" 
                               name="ano" 
                               min="1900" 
                               max="<?= date('Y') + 1 ?>" 
                               value="<?= $car['ano'] ?>"
                               required>
                        <div class="car-form-help-text">
                            Ano de fabricação
                        </div>
                    </div>

                    <!-- Preço -->
                    <div class="car-form-field">
                        <label for="preco" class="car-form-label">
                            Preço (R$) <span class="car-form-required">*</span>
                        </label>
                        <input type="text" 
                               class="car-form-input" 
                               id="preco" 
                               name="preco" 
                               value="<?= number_format($car['preco'], 2, ',', '.') ?>"
                               required>
                        <div class="car-form-help-text">
                            Formato: 99.999,99
                        </div>
                    </div>

                    <!-- Quilometragem -->
                    <div class="car-form-field">
                        <label for="km" class="car-form-label">
                            Quilometragem <span class="car-form-required">*</span>
                        </label>
                        <input type="number" 
                               class="car-form-input" 
                               id="km" 
                               name="km" 
                               min="0" 
                               value="<?= $car['km'] ?>"
                               required>
                        <div class="car-form-help-text">
                            Em quilômetros
                        </div>
                    </div>
                </div>

                <!-- Imagem -->
                <div class="car-form-row">
                    <div class="car-form-field car-form-field-full">
                        <label for="imagem" class="car-form-label">
                            Nova Imagem (opcional)
                        </label>
                        <div class="car-form-file-input-wrapper">
                            <input type="file" 
                                   class="car-form-file-input"
                                   id="imagem" 
                                   name="imagem" 
                                   accept="image/*">
                            <div class="car-form-file-display">
                                <span class="car-form-file-icon">📁</span>
                                <span class="car-form-file-text">Escolher nova imagem</span>
                            </div>
                        </div>
                        <div class="car-form-help-text">
                            Formatos aceitos: JPG, JPEG, PNG, GIF (máx. 5MB)
                        </div>

                        <!-- Imagem atual -->
                        <?php if ($car['imagem']): ?>
                            <div class="car-form-current-image">
                                <label class="car-form-label">Imagem Atual:</label>
                                <div class="car-form-image-preview">
                                    <img src="<?= BASE_URL ?>uploads/cars/<?= htmlspecialchars($car['imagem']) ?>" 
                                         alt="Imagem atual" 
                                         class="car-form-preview-img">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Especificações Técnicas -->
                <div class="car-form-section">
                    <h3 class="car-form-section-title">🔧 Especificações Técnicas (Opcionais)</h3>
                    
                    <div class="car-form-row">
                        <!-- Potência -->
                        <div class="car-form-field">
                            <label for="cv" class="car-form-label">Potência (CV)</label>
                            <input type="number" 
                                   class="car-form-input" 
                                   id="cv" 
                                   name="cv" 
                                   min="50" 
                                   max="2000" 
                                   value="<?= $car['cv'] ?? '' ?>"
                                   placeholder="Ex: 120">
                            <div class="car-form-help-text">Potência em cavalos (50-2000 CV)</div>
                        </div>

                        <!-- Motor -->
                        <div class="car-form-field">
                            <label for="motor" class="car-form-label">Motor</label>
                            <input type="text" 
                                   class="car-form-input" 
                                   id="motor" 
                                   name="motor" 
                                   value="<?= htmlspecialchars($car['motor'] ?? '') ?>"
                                   placeholder="Ex: 1.0 Turbo 3 cilindros">
                            <div class="car-form-help-text">Especificação do motor</div>
                        </div>
                    </div>

                    <div class="car-form-row">
                        <!-- Torque -->
                        <div class="car-form-field">
                            <label for="torque" class="car-form-label">Torque</label>
                            <input type="text" 
                                   class="car-form-input" 
                                   id="torque" 
                                   name="torque" 
                                   value="<?= htmlspecialchars($car['torque'] ?? '') ?>"
                                   placeholder="Ex: 20,4 kgfm">
                            <div class="car-form-help-text">Torque máximo</div>
                        </div>

                        <!-- Combustível -->
                        <div class="car-form-field">
                            <label for="combustivel" class="car-form-label">Combustível</label>
                            <select class="car-form-select" id="combustivel" name="combustivel">
                                <option value="">Selecione</option>
                                <option value="Flex" <?= ($car['combustivel'] ?? '') === 'Flex' ? 'selected' : '' ?>>Flex</option>
                                <option value="Gasolina" <?= ($car['combustivel'] ?? '') === 'Gasolina' ? 'selected' : '' ?>>Gasolina</option>
                                <option value="Etanol" <?= ($car['combustivel'] ?? '') === 'Etanol' ? 'selected' : '' ?>>Etanol</option>
                                <option value="Diesel" <?= ($car['combustivel'] ?? '') === 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                                <option value="Elétrico" <?= ($car['combustivel'] ?? '') === 'Elétrico' ? 'selected' : '' ?>>Elétrico</option>
                                <option value="Híbrido" <?= ($car['combustivel'] ?? '') === 'Híbrido' ? 'selected' : '' ?>>Híbrido</option>
                            </select>
                            <div class="car-form-help-text">Tipo de combustível</div>
                        </div>
                    </div>

                    <div class="car-form-row">
                        <!-- Transmissão -->
                        <div class="car-form-field">
                            <label for="transmissao" class="car-form-label">Transmissão</label>
                            <select class="car-form-select" id="transmissao" name="transmissao">
                                <option value="">Selecione</option>
                                <option value="Manual" <?= ($car['transmissao'] ?? '') === 'Manual' ? 'selected' : '' ?>>Manual</option>
                                <option value="Automático" <?= ($car['transmissao'] ?? '') === 'Automático' ? 'selected' : '' ?>>Automático</option>
                                <option value="CVT" <?= ($car['transmissao'] ?? '') === 'CVT' ? 'selected' : '' ?>>CVT</option>
                                <option value="Automatizado" <?= ($car['transmissao'] ?? '') === 'Automatizado' ? 'selected' : '' ?>>Automatizado</option>
                            </select>
                            <div class="car-form-help-text">Tipo de transmissão</div>
                        </div>

                        <!-- Número de Portas -->
                        <div class="car-form-field">
                            <label for="portas" class="car-form-label">Número de Portas</label>
                            <select class="car-form-select" id="portas" name="portas">
                                <option value="">Selecione</option>
                                <option value="2" <?= ($car['portas'] ?? '') == '2' ? 'selected' : '' ?>>2 portas</option>
                                <option value="3" <?= ($car['portas'] ?? '') == '3' ? 'selected' : '' ?>>3 portas</option>
                                <option value="4" <?= ($car['portas'] ?? '') == '4' ? 'selected' : '' ?>>4 portas</option>
                                <option value="5" <?= ($car['portas'] ?? '') == '5' ? 'selected' : '' ?>>5 portas</option>
                            </select>
                            <div class="car-form-help-text">Quantidade de portas (2-5)</div>
                        </div>
                    </div>

                    <div class="car-form-row">
                        <!-- Cor -->
                        <div class="car-form-field">
                            <label for="cor" class="car-form-label">Cor</label>
                            <input type="text" 
                                   class="car-form-input" 
                                   id="cor" 
                                   name="cor" 
                                   value="<?= htmlspecialchars($car['cor'] ?? '') ?>"
                                   placeholder="Ex: Branco Perolizado">
                            <div class="car-form-help-text">Cor do veículo</div>
                        </div>

                        <!-- Consumo Médio -->
                        <div class="car-form-field">
                            <label for="consumo_medio" class="car-form-label">Consumo Médio (km/l)</label>
                            <input type="number" 
                                   class="car-form-input" 
                                   id="consumo_medio" 
                                   name="consumo_medio" 
                                   step="0.1" 
                                   min="3" 
                                   max="30" 
                                   value="<?= $car['consumo_medio'] ?? '' ?>"
                                   placeholder="Ex: 14.5">
                            <div class="car-form-help-text">Consumo em km/l (3-30)</div>
                        </div>
                    </div>

                    <div class="car-form-row">
                        <!-- Descrição -->
                        <div class="car-form-field car-form-field-full">
                            <label for="descricao" class="car-form-label">Descrição</label>
                            <textarea class="car-form-textarea" 
                                      id="descricao" 
                                      name="descricao" 
                                      rows="4" 
                                      placeholder="Descrição detalhada do veículo, opcionais, estado de conservação, etc."><?= htmlspecialchars($car['descricao'] ?? '') ?></textarea>
                            <div class="car-form-help-text">Descrição detalhada e informações adicionais</div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="car-form-actions">
                    <a href="<?= BASE_URL ?>dashboard" class="car-form-btn car-form-btn-secondary">
                        ✖️ Cancelar
                    </a>
                    <button type="submit" class="car-form-btn car-form-btn-primary">
                        💾 Salvar Alterações
                    </button>
                </div>
            </form>

            <!-- Informações adicionais -->
            <div class="car-form-info-section">
                <h3 class="car-form-section-title">
                    ℹ️ Informações do Veículo
                </h3>
                <div class="car-form-row">
                    <div class="car-form-info-item">
                        <label class="car-form-info-label">Cadastrado em:</label>
                        <p class="car-form-info-value"><?= date('d/m/Y H:i', strtotime($car['created_at'])) ?></p>
                    </div>
                    <div class="car-form-info-item">
                        <label class="car-form-info-label">Última atualização:</label>
                        <p class="car-form-info-value"><?= date('d/m/Y H:i', strtotime($car['updated_at'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Scripts -->
<script>
// Máscara para preço
document.getElementById('preco').addEventListener('input', function(e) {
    let value = e.target.value;
    value = value.replace(/\D/g, '');
    value = (parseFloat(value) / 100).toFixed(2) + '';
    value = value.replace(".", ",");
    value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    e.target.value = value;
});

// Validação do formulário
document.getElementById('editCarForm').addEventListener('submit', function(e) {
    const marca = document.getElementById('marca').value.trim();
    const modelo = document.getElementById('modelo').value.trim();
    const ano = parseInt(document.getElementById('ano').value);
    const preco = document.getElementById('preco').value.trim();
    const km = parseInt(document.getElementById('km').value);
    
    if (!marca || !modelo || !ano || !preco || km < 0) {
        e.preventDefault();
        showModernAlert('error', 'Por favor, preencha todos os campos obrigatórios corretamente.');
        return false;
    }
    
    const currentYear = new Date().getFullYear();
    if (ano < 1900 || ano > currentYear + 1) {
        e.preventDefault();
        showModernAlert('error', `Ano deve estar entre 1900 e ${currentYear + 1}.`);
        return false;
    }
    
    // Validar arquivo de imagem se selecionado
    const imageFile = document.getElementById('imagem').files[0];
    if (imageFile) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(imageFile.type)) {
            e.preventDefault();
            showModernAlert('error', 'Formato de imagem não permitido. Use JPG, PNG ou GIF.');
            return false;
        }
        
        if (imageFile.size > 5 * 1024 * 1024) { // 5MB
            e.preventDefault();
            showModernAlert('error', 'Arquivo de imagem muito grande. Máximo 5MB.');
            return false;
        }
    }
    
    // Mostrar loading
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Salvando...';
});

function showModernAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `modern-alert modern-alert-${type}`;
    alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <span class="modern-alert-icon">${type === 'error' ? '⚠️' : '✅'}</span>
        <span class="modern-alert-text">${message}</span>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 300);
        }
    }, 5000);
}

// Upload de arquivo visual feedback
document.getElementById('imagem').addEventListener('change', function(e) {
    const fileDisplay = document.querySelector('.car-form-file-text');
    if (e.target.files.length > 0) {
        fileDisplay.textContent = e.target.files[0].name;
    } else {
        fileDisplay.textContent = 'Escolher nova imagem';
    }
});
</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
