<?php
// views/user/edit.php - Formulário para editar usuário

// Verificação de autenticação será feita no controller
$pageTitle = "Editar Usuário";
include_once ROOT . '/views/layouts/header.php';
?>

<div class="user-form-container">
    <!-- Header da página -->
    <div class="user-form-header">
        <div class="header-content">
            <div class="header-info">
                <h1>
                    <i class="fas fa-user-edit"></i> Editar Usuário
                </h1>
                <p>
                    Editando: <strong><?= htmlspecialchars($user['name']) ?></strong>
                </p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>user" class="user-form-back-btn">
                    <i class="fas fa-arrow-left"></i> Voltar à Lista
                </a>
            </div>
        </div>
    </div>    <!-- Informações atuais -->
    <div class="user-info-card">
        <div class="user-info-card-header">
            <h6 class="text-white">
                <i class="fas fa-info-circle"></i> Informações Atuais
            </h6>
        </div>
        <div class="user-info-card-body">
            <div class="current-user-info">
                <div class="user-avatar-large">
                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                </div>
                <div class="user-details-grid">
                    <div class="user-detail-item">
                        <div class="user-detail-label">Email:</div>
                        <div class="user-detail-value"><?= htmlspecialchars($user['email']) ?></div>
                    </div>
                    <div class="user-detail-item">
                        <div class="user-detail-label">Tipo:</div>
                        <div class="user-detail-value">
                            <?php if ($user['is_admin']): ?>
                                <span class="user-badge admin">
                                    <i class="fas fa-shield-alt"></i> Administrador
                                </span>
                            <?php else: ?>
                                <span class="user-badge user">
                                    <i class="fas fa-user"></i> Usuário
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="user-detail-item">
                        <div class="user-detail-label">Status:</div>
                        <div class="user-detail-value">
                            <?php if ($user['active']): ?>
                                <span class="user-badge active">Ativo</span>
                            <?php else: ?>
                                <span class="user-badge inactive">Inativo</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="user-detail-item">
                        <div class="user-detail-label">Cadastrado em:</div>
                        <div class="user-detail-value"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Formulário de edição -->
    <div class="user-form-card">
        <div class="user-form-card-header">
            <h5>
                <i class="fas fa-edit"></i> Editar Dados
            </h5>
        </div>
        <div class="user-form-card-body">
            <form method="POST" action="<?= BASE_URL ?>user/update/<?= $user['id'] ?>" id="editUserForm">
                <div class="user-form-row">
                    <!-- Nome -->
                    <div class="user-form-field">
                        <label for="name" class="user-form-label">
                            Nome Completo <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="user-form-input" 
                               id="name" 
                               name="name" 
                               value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>"
                               required
                               placeholder="Digite o nome completo">
                    </div>                    <!-- Email -->
                    <div class="user-form-field">
                        <label for="email" class="user-form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" 
                               class="user-form-input" 
                               id="email" 
                               name="email" 
                               value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>"
                               required
                               placeholder="usuario@exemplo.com">
                        <div class="user-form-help">
                            Email deve ser único no sistema
                        </div>
                    </div>
                </div>

                <!-- Alterar senha (opcional) -->
                <div class="password-change-toggle">
                    <div class="password-change-checkbox">
                        <input type="checkbox" 
                               id="change_password" 
                               name="change_password">
                        <label for="change_password">
                            <i class="fas fa-key"></i> Alterar senha
                        </label>
                    </div>
                </div>

                <!-- Campos de senha (inicialmente ocultos) -->
                <div id="password_fields" class="password-fields">
                    <div class="user-form-row">
                        <div class="user-form-field">
                            <label for="password" class="user-form-label">
                                Nova Senha
                            </label>
                            <div class="user-form-input-group">
                                <input type="password" 
                                       class="user-form-input" 
                                       id="password" 
                                       name="password" 
                                       minlength="6"
                                       placeholder="Mínimo 6 caracteres">
                                <button class="toggle-password-btn" 
                                        type="button" 
                                        onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            <div class="user-form-help">
                                Mínimo de 6 caracteres
                            </div>
                        </div>

                        <div class="user-form-field">
                            <label for="password_confirm" class="user-form-label">
                                Confirmar Nova Senha
                            </label>
                            <div class="user-form-input-group">
                                <input type="password" 
                                       class="user-form-input" 
                                       id="password_confirm" 
                                       name="password_confirm" 
                                       minlength="6"
                                       placeholder="Confirme a senha">
                                <button class="toggle-password-btn" 
                                        type="button" 
                                        onclick="togglePassword('password_confirm')">
                                    <i class="fas fa-eye" id="password_confirm-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>                <!-- Tipo de usuário -->
                <?php if ($user['id'] != $_SESSION['user_id']): // Não pode alterar a si mesmo ?>
                <div class="user-form-group">
                    <label class="user-form-label">Tipo de Usuário</label>
                    <div class="user-type-selector">
                        <div class="user-radio-option" onclick="selectRadio('user_normal')">
                            <input type="radio" 
                                   name="is_admin" 
                                   id="user_normal" 
                                   value="0" 
                                   <?= !$user['is_admin'] ? 'checked' : '' ?>>
                            <div class="user-radio-content">
                                <div class="user-radio-title">
                                    <i class="fas fa-user text-info"></i>
                                    Usuário Normal
                                </div>
                                <p class="user-radio-description">
                                    Pode navegar pelo site, favoritar carros e visualizar informações
                                </p>
                            </div>
                        </div>
                        <div class="user-radio-option" onclick="selectRadio('user_admin')">
                            <input type="radio" 
                                   name="is_admin" 
                                   id="user_admin" 
                                   value="1"
                                   <?= $user['is_admin'] ? 'checked' : '' ?>>
                            <div class="user-radio-content">
                                <div class="user-radio-title">
                                    <i class="fas fa-shield-alt text-danger"></i>
                                    Administrador
                                </div>
                                <p class="user-radio-description">
                                    Acesso total: gerenciar carros, usuários e estatísticas
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="user-form-alert">
                    <i class="fas fa-info-circle"></i>
                    <div class="user-form-alert-content">
                        <strong>Nota:</strong> Você não pode alterar suas próprias permissões.
                    </div>
                </div>
                <input type="hidden" name="is_admin" value="<?= $user['is_admin'] ?>">
                <?php endif; ?>                <!-- Status -->
                <?php if ($user['id'] != $_SESSION['user_id']): // Não pode desativar a si mesmo ?>
                <div class="user-form-group">
                    <label class="user-form-label">Status</label>
                    <div class="user-status-selector">
                        <div class="user-radio-option" onclick="selectRadio('status_active')">
                            <input type="radio" 
                                   name="active" 
                                   id="status_active" 
                                   value="1" 
                                   <?= $user['active'] ? 'checked' : '' ?>>
                            <div class="user-radio-content">
                                <div class="user-radio-title">
                                    <i class="fas fa-check-circle text-success"></i>
                                    Ativo
                                </div>
                                <p class="user-radio-description">
                                    Usuário pode fazer login
                                </p>
                            </div>
                        </div>
                        <div class="user-radio-option" onclick="selectRadio('status_inactive')">
                            <input type="radio" 
                                   name="active" 
                                   id="status_inactive" 
                                   value="0"
                                   <?= !$user['active'] ? 'checked' : '' ?>>
                            <div class="user-radio-content">
                                <div class="user-radio-title">
                                    <i class="fas fa-ban text-warning"></i>
                                    Inativo
                                </div>
                                <p class="user-radio-description">
                                    Usuário não pode fazer login
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="user-form-alert" style="background: rgba(255, 193, 7, 0.1); border-color: rgba(255, 193, 7, 0.3);">
                    <i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i>
                    <div class="user-form-alert-content">
                        <strong style="color: #ffc107;">Nota:</strong> Você não pode desativar sua própria conta.
                    </div>
                </div>
                <input type="hidden" name="active" value="<?= $user['active'] ?>">
                <?php endif; ?>

                <!-- Botões -->
                <div class="user-form-actions">
                    <a href="<?= BASE_URL ?>user" class="user-form-btn user-form-btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="user-form-btn user-form-btn-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
// Toggle campos de senha
document.getElementById('change_password').addEventListener('change', function() {
    const passwordFields = document.getElementById('password_fields');
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('password_confirm');
    
    if (this.checked) {
        passwordFields.classList.add('show');
        passwordField.required = true;
        confirmField.required = true;
    } else {
        passwordFields.classList.remove('show');
        passwordField.required = false;
        confirmField.required = false;
        passwordField.value = '';
        confirmField.value = '';
    }
});

// Toggle visibility da senha
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Função para selecionar radio buttons
function selectRadio(radioId) {
    const radio = document.getElementById(radioId);
    radio.checked = true;
    updateRadioSelection();
}

// Atualizar seleção visual dos radio buttons
function updateRadioSelection() {
    // Reset all radio options
    document.querySelectorAll('.user-radio-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to checked options
    document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
        const option = radio.closest('.user-radio-option');
        if (option) {
            option.classList.add('selected');
        }
    });
}

// Initialize radio selection on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRadioSelection();
    
    // Add event listeners to radio buttons
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', updateRadioSelection);
    });
});

// Validação do formulário
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    const changePassword = document.getElementById('change_password').checked;
    
    if (changePassword) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;
        
        if (password !== passwordConfirm) {
            e.preventDefault();
            showAlert('danger', 'As senhas não coincidem!');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            showAlert('danger', 'A senha deve ter pelo menos 6 caracteres!');
            return false;
        }
    }
});

function showAlert(type, message) {
    // Create modern alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `modern-alert modern-alert-${type}`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.3);
        border-radius: 8px;
        padding: 15px;
        color: #dc3545;
        animation: slideInRight 0.3s ease;
    `;
    alertDiv.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-exclamation-triangle"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="
                background: none;
                border: none;
                color: #dc3545;
                cursor: pointer;
                margin-left: auto;
                font-size: 1.2rem;
            ">×</button>
        </div>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}
</script>

<style>
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
