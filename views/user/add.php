<?php
// views/user/add.php - Formulário para adicionar usuário

// Verificação de autenticação será feita no controller
$pageTitle = "Adicionar Usuário";
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
                    <i class="fas fa-user-plus"></i> Adicionar Usuário
                </h1>
                <p>Cadastre um novo usuário no sistema</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>user" class="user-form-back-btn">
                    <i class="fas fa-arrow-left"></i> Voltar à Lista
                </a>            </div>
        </div>
    </div>
    
    <!-- Formulário -->
    <div class="user-form-card">
        <div class="user-form-card-header">
            <h5>
                <i class="fas fa-user-edit"></i> Dados do Usuário
            </h5>
        </div>
        <div class="user-form-card-body">
            <form method="POST" action="<?= BASE_URL ?>user/create" id="addUserForm">
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
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                               required
                               placeholder="Digite o nome completo">
                        <div class="user-form-help">
                            Digite o nome completo do usuário
                        </div>
                    </div>

                                                <!-- Email -->
                    <div class="user-form-field">
                        <label for="email" class="user-form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" 
                               class="user-form-input" 
                               id="email" 
                               name="email" 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               required
                               placeholder="usuario@exemplo.com">
                        <div class="user-form-help">
                            Email deve ser único no sistema
                        </div>
                    </div>
                </div>

                <div class="user-form-row">
                    <!-- Senha -->
                    <div class="user-form-field">
                        <label for="password" class="user-form-label">
                            Senha <span class="required">*</span>
                        </label>
                        <div class="user-form-input-group">
                            <input type="password" 
                                   class="user-form-input" 
                                   id="password" 
                                   name="password" 
                                   required
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
                    </div>                    <!-- Confirmar Senha -->
                    <div class="user-form-field">
                        <label for="password_confirm" class="user-form-label">
                            Confirmar Senha <span class="required">*</span>
                        </label>
                        <div class="user-form-input-group">
                            <input type="password" 
                                   class="user-form-input" 
                                   id="password_confirm" 
                                   name="password_confirm" 
                                   required
                                   minlength="6"
                                   placeholder="Confirme a senha">
                            <button class="toggle-password-btn" 
                                    type="button" 
                                    onclick="togglePassword('password_confirm')">
                                <i class="fas fa-eye" id="password_confirm-icon"></i>
                            </button>
                        </div>
                        <div class="user-form-help">
                            Confirme a senha digitada acima
                        </div>
                    </div>
                </div>

                <!-- Tipo de usuário -->
                <div class="user-form-group">
                    <label class="user-form-label">Tipo de Usuário</label>
                    <div class="user-type-selector">
                        <div class="user-radio-option" onclick="selectRadio('user_normal')">
                            <input type="radio" 
                                   name="is_admin" 
                                   id="user_normal" 
                                   value="0" 
                                   checked>
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
                                   value="1">
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
                <!-- Status -->
                <div class="user-form-group">
                    <label class="user-form-label">Status Inicial</label>
                    <div class="user-status-selector">
                        <div class="user-radio-option" onclick="selectRadio('status_active')">
                            <input type="radio" 
                                   name="active" 
                                   id="status_active" 
                                   value="1" 
                                   checked>
                            <div class="user-radio-content">
                                <div class="user-radio-title">
                                    <i class="fas fa-check-circle text-success"></i>
                                    Ativo
                                </div>
                                <p class="user-radio-description">
                                    Usuário poderá fazer login imediatamente
                                </p>
                            </div>
                        </div>
                        <div class="user-radio-option" onclick="selectRadio('status_inactive')">
                            <input type="radio" 
                                   name="active" 
                                   id="status_inactive" 
                                   value="0">
                            <div class="user-radio-content">
                                <div class="user-radio-title">
                                    <i class="fas fa-ban text-warning"></i>
                                    Inativo
                                </div>
                                <p class="user-radio-description">
                                    Usuário não poderá fazer login até ser ativado
                                </p>
                            </div>
                        </div>
                    </div>
                </div>                <!-- Botões -->
                <div class="user-form-actions">
                    <a href="<?= BASE_URL ?>user" class="user-form-btn user-form-btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="user-form-btn user-form-btn-primary">
                        <i class="fas fa-save"></i> Cadastrar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>    <!-- Informações adicionais -->
    <div class="user-tips-card">
        <div class="user-tips-card-header">
            <h6>
                <i class="fas fa-info-circle"></i> Informações Importantes
            </h6>
        </div>
        <div class="user-tips-card-body">
            <ul class="user-tips-list">
                <li><i class="fas fa-check"></i> O email deve ser único no sistema</li>
                <li><i class="fas fa-check"></i> A senha deve ter pelo menos 6 caracteres</li>
                <li><i class="fas fa-check"></i> Administradores têm acesso total ao sistema</li>
                <li><i class="fas fa-check"></i> Usuários inativos não podem fazer login</li>
                <li><i class="fas fa-check"></i> As permissões podem ser alteradas posteriormente</li>
            </ul>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
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
document.getElementById('addUserForm').addEventListener('submit', function(e) {
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
