<?php
// views/cars/edit.php - Formulário para editar carro
$title = $title ?? 'Editar Carro';
// Inclui o novo caminho do CSS principal
include_once ROOT . '/views/layouts/header.php';
?>
<style>
    body {
        background: rgb(0, 0, 0) !important;
        margin: 0;
        padding: 0;
    }
</style>
<div class="user-form-container">
    <!-- Cabeçalho da página -->
    <div class="user-form-header">
        <div class="user-form-header-content">
            <div class="user-form-header-info">
                <h1>
                    <i class="fas fa-car"></i> Editar Carro
                </h1>
                <p>Editando: <strong><?= htmlspecialchars($car['marca'] . ' ' . $car['modelo']) ?></strong></p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>dashboard/admin" class="user-form-back-btn">
                    <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
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
        <div class="user-form-card">
            <div class="user-form-card-header">
                <h5>
                    <i class="fas fa-car-side"></i> Dados do Veículo
                </h5>
            </div>
            <div class="user-form-card-body">
                <form method="POST" action="<?= BASE_URL ?>car/update/<?= $car['id'] ?>" enctype="multipart/form-data"
                    id="editCarForm">
                    <div class="user-form-row">
                        <!-- Marca -->
                        <div class="user-form-field">
                            <label for="marca" class="user-form-label">
                                Marca <span class="required">*</span>
                            </label>
                            <input type="text" id="marca" name="marca" required class="user-form-input"
                                value="<?= htmlspecialchars($car['marca']) ?>" placeholder="Ex: Toyota, Honda, BMW">
                            <div class="user-form-help">Digite a marca do veículo</div>
                        </div>

                        <!-- Modelo -->
                        <div class="user-form-field">
                            <label for="modelo" class="user-form-label">
                                Modelo <span class="required">*</span>
                            </label>
                            <input type="text" id="modelo" name="modelo" required class="user-form-input"
                                value="<?= htmlspecialchars($car['modelo']) ?>" placeholder="Ex: Civic, Corolla, X1">
                            <div class="user-form-help">Digite o modelo do veículo</div>
                        </div>
                    </div>

                    <div class="user-form-row">
                        <!-- Ano -->
                        <div class="user-form-field">
                            <label for="ano" class="user-form-label">
                                Ano <span class="required">*</span>
                            </label>
                            <input type="number" id="ano" name="ano" min="1900" max="<?= date('Y') + 1 ?>" required
                                class="user-form-input" value="<?= $car['ano'] ?>" placeholder="Ex: 2022">
                            <div class="user-form-help">Ano de fabricação</div>
                        </div>

                        <!-- Preço -->
                        <div class="user-form-field">
                            <label for="preco" class="user-form-label">
                                Preço (R$) <span class="required">*</span>
                            </label>
                            <input type="text" id="preco" name="preco" required class="user-form-input"
                                value="<?= number_format($car['preco'], 2, ',', '.') ?>" placeholder="Ex: 99.999,99">
                            <div class="user-form-help">Formato: 99.999,99</div>
                        </div>


                        <!-- Quilometragem -->
                        <div class="user-form-field">
                            <label for="km" class="user-form-label">
                                Quilometragem <span class="required">*</span>
                            </label>
                            <input type="number" id="km" name="km" min="0" required class="user-form-input"
                                value="<?= $car['km'] ?>" placeholder="Ex: 50000">
                            <div class="user-form-help">Em quilômetros</div>
                        </div>

                        <!-- Imagem -->
                        <div class="user-form-field">
                            <label for="imagem" class="user-form-label">Nova Imagem (Opcional) </label>
                            <div class="user-form-file">
                                <input type="file" id="imagem" name="imagem" accept="image/*">
                                <label for="imagem" class="user-form-file-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Clique para selecionar uma imagem</span>
                                </label>
                            </div>
                            <div class="user-form-help">Formatos aceitos: JPG, JPEG, PNG, GIF (máx. 5MB)</div>
                        </div>
                    </div>


                    <!-- Imagem atual -->
                    <?php if ($car['imagem']): ?>
                        <div class="user-form-current-image">
                            <label class="user-form-label">Imagem Atual:</label>
                            <div class="user-form-image-preview">
                                <img src="<?= BASE_URL ?>uploads/cars/<?= htmlspecialchars($car['imagem']) ?>"
                                    alt="Imagem atual" class="user-form-preview-img">
                            </div>
                        </div>
                    <?php endif; ?>
            </div>
        </div>

        <!-- Especificações Técnicas -->
        <div class="user-form-section">
            <h3 class="user-form-section-title">🔧 Especificações Técnicas (Opcionais)</h3>

            <div class="user-form-row">
                <!-- Potência -->
                <div class="user-form-field">
                    <label for="cv" class="user-form-label">Potência (CV)</label>
                    <input type="number" id="cv" name="cv" min="50" max="2000" class="user-form-input"
                        value="<?= $car['cv'] ?? '' ?>" placeholder="Ex: 120">
                    <div class="user-form-help">Potência em cavalos (50-2000 CV)</div>
                </div>

                <!-- Motor -->
                <div class="user-form-field">
                    <label for="motor" class="user-form-label">Motor</label>
                    <input type="text" id="motor" name="motor" class="user-form-input"
                        value="<?= htmlspecialchars($car['motor'] ?? '') ?>" placeholder="Ex: 1.0 Turbo 3 cilindros">
                    <div class="user-form-help">Especificação do motor</div>
                </div>
            </div>

            <div class="user-form-row">
                <!-- Torque -->
                <div class="user-form-field">
                    <label for="torque" class="user-form-label">Torque</label>
                    <input type="text" id="torque" name="torque" class="user-form-input"
                        value="<?= htmlspecialchars($car['torque'] ?? '') ?>" placeholder="Ex: 20,4 kgfm">
                    <div class="user-form-help">Torque máximo</div>
                </div>

                <!-- Combustível -->
                <div class="user-form-field">
                    <label for="combustivel" class="user-form-label">Combustível</label>
                    <select class="user-form-select" id="combustivel" name="combustivel">
                        <option value="">Selecione</option>
                        <option value="Flex" <?= ($car['combustivel'] ?? '') === 'Flex' ? 'selected' : '' ?>>Flex</option>
                        <option value="Gasolina" <?= ($car['combustivel'] ?? '') === 'Gasolina' ? 'selected' : '' ?>>
                            Gasolina</option>
                        <option value="Etanol" <?= ($car['combustivel'] ?? '') === 'Etanol' ? 'selected' : '' ?>>Etanol
                        </option>
                        <option value="Diesel" <?= ($car['combustivel'] ?? '') === 'Diesel' ? 'selected' : '' ?>>Diesel
                        </option>
                        <option value="Elétrico" <?= ($car['combustivel'] ?? '') === 'Elétrico' ? 'selected' : '' ?>>
                            Elétrico</option>
                        <option value="Híbrido" <?= ($car['combustivel'] ?? '') === 'Híbrido' ? 'selected' : '' ?>>Híbrido
                        </option>
                    </select>
                    <div class="user-form-help">Tipo de combustível</div>
                </div>
            </div>

            <div class="user-form-row">
                <!-- Transmissão -->
                <div class="user-form-field">
                    <label for="transmissao" class="user-form-label">Transmissão</label>
                    <select class="user-form-select" id="transmissao" name="transmissao">
                        <option value="">Selecione</option>
                        <option value="Manual" <?= ($car['transmissao'] ?? '') === 'Manual' ? 'selected' : '' ?>>Manual
                        </option>
                        <option value="Automático" <?= ($car['transmissao'] ?? '') === 'Automático' ? 'selected' : '' ?>>
                            Automático</option>
                        <option value="CVT" <?= ($car['transmissao'] ?? '') === 'CVT' ? 'selected' : '' ?>>CVT</option>
                        <option value="Automatizado" <?= ($car['transmissao'] ?? '') === 'Automatizado' ? 'selected' : '' ?>>Automatizado</option>
                    </select>
                    <div class="user-form-help">Tipo de transmissão</div>
                </div>

                <!-- Número de Portas -->
                <div class="user-form-field">
                    <label for="portas" class="user-form-label">Número de Portas</label>
                    <select class="user-form-select" id="portas" name="portas">
                        <option value="">Selecione</option>
                        <option value="2" <?= ($car['portas'] ?? '') == '2' ? 'selected' : '' ?>>2 portas</option>
                        <option value="3" <?= ($car['portas'] ?? '') == '3' ? 'selected' : '' ?>>3 portas</option>
                        <option value="4" <?= ($car['portas'] ?? '') == '4' ? 'selected' : '' ?>>4 portas</option>
                        <option value="5" <?= ($car['portas'] ?? '') == '5' ? 'selected' : '' ?>>5 portas</option>
                    </select>
                    <div class="user-form-help">Quantidade de portas (2-5)</div>
                </div>
            </div>

            <div class="user-form-row">
                <!-- Cor -->
                <div class="user-form-field">
                    <label for="cor" class="user-form-label">Cor</label>
                    <input type="text" id="cor" name="cor" class="user-form-input"
                        value="<?= htmlspecialchars($car['cor'] ?? '') ?>" placeholder="Ex: Branco Perolizado">
                    <div class="user-form-help">Cor do veículo</div>
                </div>

                <!-- Consumo Médio -->
                <div class="user-form-field">
                    <label for="consumo_medio" class="user-form-label">Consumo Médio (km/l)</label>
                    <input type="number" id="consumo_medio" name="consumo_medio" step="0.1" min="3" max="30"
                        class="user-form-input" value="<?= $car['consumo_medio'] ?? '' ?>" placeholder="Ex: 14.5">
                    <div class="user-form-help">Consumo em km/l (3-30)</div>
                </div>
            </div>

            <div class="user-form-row">
                <!-- Descrição -->
                    <div class="user-form-field">
                    <label for="descricao" class="user-form-label">Descrição</label>
                    <textarea class="user-form-textarea" id="descricao" name="descricao" rows="4"
                        placeholder="Descrição detalhada do veículo, opcionais, estado de conservação, etc."><?= htmlspecialchars($car['descricao'] ?? '') ?></textarea>
                    <div class="user-form-help">Descrição detalhada e informações adicionais</div>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="user-form-actions">
            <a href="<?= BASE_URL ?>dashboard/admin" class="user-form-btn user-form-btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="user-form-btn user-form-btn-primary">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
        </div>
        </form>

        <!-- Informações adicionais -->
        <div class="user-form-info-section">
            <h3 class="user-form-section-title">
                ℹ️ Informações do Veículo
            </h3>
            <div class="user-form-row">
                <div class="user-form-info-item">
                    <label class="user-form-info-label">Cadastrado em:</label>
                    <p class="user-form-info-value"><?= date('d/m/Y H:i', strtotime($car['created_at'])) ?></p>
                </div>
                <div class="user-form-info-item">
                    <label class="user-form-info-label">Última atualização:</label>
                    <p class="user-form-info-value"><?= date('d/m/Y H:i', strtotime($car['updated_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Scripts -->
<script>
    // Máscara para preço
    document.getElementById('preco').addEventListener('input', function (e) {
        let value = e.target.value;
        value = value.replace(/\D/g, '');
        value = (parseFloat(value) / 100).toFixed(2) + '';
        value = value.replace(".", ",");
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        e.target.value = value;
    });

    // Validação do formulário
    document.getElementById('editCarForm').addEventListener('submit', function (e) {
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
    document.getElementById('imagem').addEventListener('change', function (e) {
        const fileDisplay = document.querySelector('.car-form-file-text');
        if (e.target.files.length > 0) {
            fileDisplay.textContent = e.target.files[0].name;
        } else {
            fileDisplay.textContent = 'Escolher nova imagem';
        }
    });
</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>