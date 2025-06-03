<?php
// views/cars/add.php
$title = $title ?? 'Adicionar Carro';
include_once ROOT . '/views/layouts/header.php';
?>

<style>
body {
    background: #000000 !important;
    margin: 0;
    padding: 0;
}
</style>

<div class="user-form-container">
    <!-- Header da página -->    <div class="user-form-header">
        <div class="user-form-header-content">
            <div class="user-form-header-info">
                <h1>
                    <i class="fas fa-car"></i> Adicionar Carro
                </h1>
                <p>Cadastre um novo veículo no sistema</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>dashboard/admin" class="user-form-back-btn">
                    <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
                </a>            
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="modern-alert modern-alert-error">
            <span class="modern-alert-icon">⚠️</span>
            <span class="modern-alert-text"><?= htmlspecialchars($_SESSION['error']) ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="user-form-card">
        <div class="user-form-card-header">
            <h5>
                <i class="fas fa-car-side"></i> Dados do Veículo
            </h5>
        </div>
        <div class="user-form-card-body">
            <form method="POST" action="<?= BASE_URL ?>car/create" enctype="multipart/form-data" id="addCarForm">                <div class="user-form-row">
                    <div class="user-form-field">
                        <label for="marca" class="user-form-label">
                            Marca <span class="required">*</span>
                        </label>
                        <input type="text" id="marca" name="marca" required class="user-form-input"
                               value="<?= isset($_POST['marca']) ? htmlspecialchars($_POST['marca']) : '' ?>"
                               placeholder="Ex: Toyota, Honda, BMW">
                        <div class="user-form-help">Digite a marca do veículo</div>
                    </div>
                    
                    <div class="user-form-field">
                        <label for="modelo" class="user-form-label">
                            Modelo <span class="required">*</span>
                        </label>
                        <input type="text" id="modelo" name="modelo" required class="user-form-input"
                               value="<?= isset($_POST['modelo']) ? htmlspecialchars($_POST['modelo']) : '' ?>"
                               placeholder="Ex: Corolla, Civic, X3">
                        <div class="user-form-help">Digite o modelo do veículo</div>
                    </div>
                </div>
                
                <div class="user-form-row">
                    <div class="user-form-field">
                        <label for="ano" class="user-form-label">
                            Ano <span class="required">*</span>
                        </label>
                        <input type="number" id="ano" name="ano" min="1900" max="<?= date('Y') + 1 ?>" 
                               required class="user-form-input"
                               value="<?= isset($_POST['ano']) ? $_POST['ano'] : '' ?>"
                               placeholder="<?= date('Y') ?>">
                        <div class="user-form-help">Ano de fabricação do veículo</div>
                    </div>
                    
                    <div class="user-form-field">
                        <label for="preco" class="user-form-label">
                            Preço (R$) <span class="required">*</span>
                        </label>
                        <input type="text" id="preco" name="preco" required class="user-form-input"
                               value="<?= isset($_POST['preco']) ? htmlspecialchars($_POST['preco']) : '' ?>"
                               placeholder="Ex: 45.000,00">
                        <div class="user-form-help">Preço de venda do veículo</div>
                    </div>
                </div>
                  <div class="user-form-row">
                    <div class="user-form-field">
                        <label for="km" class="user-form-label">
                            Quilometragem <span class="required">*</span>
                        </label>
                        <input type="number" id="km" name="km" min="0" required class="user-form-input"
                               value="<?= isset($_POST['km']) ? $_POST['km'] : '' ?>"
                               placeholder="Ex: 45000">
                        <div class="user-form-help">Quilometragem atual do veículo</div>
                    </div>
                    
                    <div class="user-form-field">
                        <label for="imagem" class="user-form-label">Imagem do Veículo</label>
                        <div class="user-form-file">
                            <input type="file" id="imagem" name="imagem" accept="image/*">
                            <label for="imagem" class="user-form-file-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Clique para selecionar uma imagem</span>
                                <small>JPG, JPEG, PNG, GIF - Máx. 5MB</small>
                            </label>
                        </div>
                        <div class="user-form-help">Foto principal do veículo (opcional)</div>
                    </div>
                </div>

                <!-- Especificações Técnicas -->
                <div style="margin: 30px 0;">
                    <h3 style="color: #d4af37; font-size: 1.3rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-cog"></i> Especificações Técnicas (Opcionais)
                    </h3>
                    
                    <div class="user-form-row">
                        <div class="user-form-field">
                            <label for="cv" class="user-form-label">Potência (CV)</label>
                            <input type="number" id="cv" name="cv" min="50" max="2000" class="user-form-input"
                                   value="<?= isset($_POST['cv']) ? $_POST['cv'] : '' ?>"
                                   placeholder="Ex: 120">
                            <div class="user-form-help">Potência do motor em cavalos</div>
                        </div>
                        
                        <div class="user-form-field">
                            <label for="motor" class="user-form-label">Motor</label>
                            <input type="text" id="motor" name="motor" class="user-form-input"
                                   value="<?= isset($_POST['motor']) ? htmlspecialchars($_POST['motor']) : '' ?>"
                                   placeholder="Ex: 1.0 Turbo 3 cilindros">
                            <div class="user-form-help">Especificação do motor</div>
                        </div>                    </div>
                    
                    <div class="user-form-row">
                        <div class="user-form-field">
                            <label for="torque" class="user-form-label">Torque</label>
                            <input type="text" id="torque" name="torque" class="user-form-input"
                                   value="<?= isset($_POST['torque']) ? htmlspecialchars($_POST['torque']) : '' ?>"
                                   placeholder="Ex: 20,4 kgfm">
                            <div class="user-form-help">Torque do motor</div>
                        </div>
                        
                        <div class="user-form-field">
                            <label for="combustivel" class="user-form-label">Combustível</label>
                            <select id="combustivel" name="combustivel" class="user-form-select">
                                <option value="">Selecione</option>
                                <option value="Flex" <?= (isset($_POST['combustivel']) && $_POST['combustivel'] === 'Flex') ? 'selected' : '' ?>>Flex</option>
                                <option value="Gasolina" <?= (isset($_POST['combustivel']) && $_POST['combustivel'] === 'Gasolina') ? 'selected' : '' ?>>Gasolina</option>
                                <option value="Etanol" <?= (isset($_POST['combustivel']) && $_POST['combustivel'] === 'Etanol') ? 'selected' : '' ?>>Etanol</option>
                                <option value="Diesel" <?= (isset($_POST['combustivel']) && $_POST['combustivel'] === 'Diesel') ? 'selected' : '' ?>>Diesel</option>
                                <option value="Elétrico" <?= (isset($_POST['combustivel']) && $_POST['combustivel'] === 'Elétrico') ? 'selected' : '' ?>>Elétrico</option>
                                <option value="Híbrido" <?= (isset($_POST['combustivel']) && $_POST['combustivel'] === 'Híbrido') ? 'selected' : '' ?>>Híbrido</option>
                            </select>
                            <div class="user-form-help">Tipo de combustível</div>
                            </select>
                        </div>                    </div>
                    
                    <div class="user-form-row">
                        <div class="user-form-field">
                            <label for="transmissao" class="user-form-label">Transmissão</label>
                            <select id="transmissao" name="transmissao" class="user-form-select">
                                <option value="">Selecione</option>
                                <option value="Manual" <?= (isset($_POST['transmissao']) && $_POST['transmissao'] === 'Manual') ? 'selected' : '' ?>>Manual</option>
                                <option value="Automático" <?= (isset($_POST['transmissao']) && $_POST['transmissao'] === 'Automático') ? 'selected' : '' ?>>Automático</option>
                                <option value="CVT" <?= (isset($_POST['transmissao']) && $_POST['transmissao'] === 'CVT') ? 'selected' : '' ?>>CVT</option>
                                <option value="Automatizado" <?= (isset($_POST['transmissao']) && $_POST['transmissao'] === 'Automatizado') ? 'selected' : '' ?>>Automatizado</option>
                            </select>
                            <div class="user-form-help">Tipo de transmissão</div>
                        </div>
                        
                        <div class="user-form-field">
                            <label for="portas" class="user-form-label">Número de Portas</label>
                            <select id="portas" name="portas" class="user-form-select">
                                <option value="">Selecione</option>
                                <option value="2" <?= (isset($_POST['portas']) && $_POST['portas'] === '2') ? 'selected' : '' ?>>2 portas</option>
                                <option value="3" <?= (isset($_POST['portas']) && $_POST['portas'] === '3') ? 'selected' : '' ?>>3 portas</option>
                                <option value="4" <?= (isset($_POST['portas']) && $_POST['portas'] === '4') ? 'selected' : '' ?>>4 portas</option>
                                <option value="5" <?= (isset($_POST['portas']) && $_POST['portas'] === '5') ? 'selected' : '' ?>>5 portas</option>
                            </select>
                            <div class="user-form-help">Quantidade de portas</div>
                        </div>
                    </div>
                    
                    <div class="user-form-row">
                        <div class="user-form-field">
                            <label for="cor" class="user-form-label">Cor</label>
                            <input type="text" id="cor" name="cor" class="user-form-input"
                                   value="<?= isset($_POST['cor']) ? htmlspecialchars($_POST['cor']) : '' ?>"
                                   placeholder="Ex: Branco Perolizado">
                            <div class="user-form-help">Cor do veículo</div>
                        </div>
                        
                        <div class="user-form-field">
                            <label for="consumo_medio" class="user-form-label">Consumo Médio (km/l)</label>
                            <input type="number" id="consumo_medio" name="consumo_medio" step="0.1" min="3" max="30" 
                                   class="user-form-input"
                                   value="<?= isset($_POST['consumo_medio']) ? $_POST['consumo_medio'] : '' ?>"
                                   placeholder="Ex: 14.5">
                            <div class="user-form-help">Consumo médio de combustível</div>
                        </div>
                    </div>
                    
                    <div class="user-form-row">
                        <div class="user-form-field" style="grid-column: 1 / -1;">
                            <label for="descricao" class="user-form-label">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="4" class="user-form-textarea"
                                      placeholder="Descrição detalhada do veículo, opcionais, estado de conservação, etc."><?= isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : '' ?></textarea>
                            <div class="user-form-help">Informações adicionais sobre o veículo</div>
                        </div>
                    </div>
                </div>

                <div class="user-form-actions">
                    <a href="<?= BASE_URL ?>dashboard/admin" class="user-form-btn user-form-btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="user-form-btn user-form-btn-primary">
                        <i class="fas fa-save"></i> Salvar Carro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
