<?php
// views/user/index.php - Lista de usuários para administradores

// Verificação de autenticação será feita no controller
$pageTitle = "Gerenciamento de Usuários";
include_once ROOT . '/views/layouts/header.php';
?>

<div class="container">
    <!-- User Management Header -->
    <div class="user-management-header">
        <h1><i class="fas fa-users"></i> Gerenciamento de Usuários</h1>
        <p>Gerencie usuários do sistema com controle total sobre permissões e status</p>
          <div class="user-management-actions">
            <a href="<?= BASE_URL ?>dashboard" class="btn">
                <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
            </a>
            <a href="<?= BASE_URL ?>user/add" class="modern-btn modern-btn-primary">
                <i class="fas fa-plus"></i> Adicionar Usuário
            </a>
        </div>    </div>
    
    <!-- Filtros e busca -->
    <div class="user-filters-panel">
        <h6><i class="fas fa-filter"></i> Filtros de Busca</h6>
        
        <form method="GET" action="<?= BASE_URL ?>user" class="user-filters-form">
            <div class="user-filter-group">
                <label for="search">🔍 Buscar por nome ou email:</label>
                <input type="text" 
                       class="user-filter-input" 
                       id="search" 
                       name="search" 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                       placeholder="Digite nome ou email...">
            </div>
            
            <div class="user-filter-group">
                <label for="role">👤 Tipo de usuário:</label>
                <select class="user-filter-select" id="role" name="role">
                    <option value="">Todos</option>
                    <option value="admin" <?= ($_GET['role'] ?? '') === 'admin' ? 'selected' : '' ?>>🛡️ Administradores</option>
                    <option value="user" <?= ($_GET['role'] ?? '') === 'user' ? 'selected' : '' ?>>👥 Usuários</option>
                </select>
            </div>
            
            <div class="user-filter-group">
                <label for="status">📊 Status:</label>
                <select class="user-filter-select" id="status" name="status">
                    <option value="">Todos</option>
                    <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>✅ Ativos</option>
                    <option value="inactive" <?= ($_GET['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>❌ Inativos</option>
                </select>
            </div>
              <div class="user-filter-actions">
                <button type="submit" class="user-filter-btn modern-btn-search">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="<?= BASE_URL ?>user" class="user-filter-btn modern-btn-clear">
                    <i class="fas fa-times"></i> Limpar
                </a>
            </div>        </form>
    </div>
    
    <!-- Tabela de usuários -->
    <div class="users-table-container">
        <div class="users-table-header">
            <h5 class="users-table-title">
                <i class="fas fa-users"></i> Usuários do Sistema
            </h5>
            <?php if (!empty($users)): ?>
                <div class="users-count-badge"><?= count($users) ?> encontrados</div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($users)): ?>
            <div class="table-responsive-wrapper">
                <table class="modern-users-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="<?= $user['active'] ? '' : 'user-inactive' ?>">
                                <td>
                                    <div class="user-table-info">
                                        <div class="user-table-avatar">
                                            <?= strtoupper(substr($user['name'], 0, 2)) ?>
                                        </div>
                                        <div class="user-table-details">
                                            <h6><?= htmlspecialchars($user['name']) ?></h6>
                                            <?php if (!$user['active']): ?>
                                                <div class="user-inactive-indicator">
                                                    <i class="fas fa-ban"></i> Conta inativa
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <?php if ($user['is_admin']): ?>
                                        <span class="user-role-badge admin">
                                            <i class="fas fa-shield-alt"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="user-role-badge user">
                                            <i class="fas fa-user"></i> Usuário
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['active']): ?>
                                        <span class="user-status-badge active">Ativo</span>
                                    <?php else: ?>
                                        <span class="user-status-badge inactive">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></small>
                                </td>
                                <td>
                                    <div class="user-actions">
                                        <!-- Editar -->
                                        <a href="<?= BASE_URL ?>user/edit/<?= $user['id'] ?>" 
                                           class="user-action-btn edit" 
                                           title="Editar usuário">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Toggle Admin -->
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <button onclick="toggleAdmin(<?= $user['id'] ?>, <?= $user['is_admin'] ? 'false' : 'true' ?>)" 
                                                    class="user-action-btn toggle-admin <?= $user['is_admin'] ? 'remove' : '' ?>" 
                                                    title="<?= $user['is_admin'] ? 'Remover admin' : 'Tornar admin' ?>">
                                                <i class="fas fa-<?= $user['is_admin'] ? 'user-minus' : 'user-plus' ?>"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <!-- Ativar/Desativar -->
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <?php if ($user['active']): ?>
                                                <button onclick="deactivateUser(<?= $user['id'] ?>)" 
                                                        class="user-action-btn deactivate" 
                                                        title="Desativar usuário">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            <?php else: ?>
                                                <button onclick="reactivateUser(<?= $user['id'] ?>)" 
                                                        class="user-action-btn activate" 
                                                        title="Reativar usuário">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <!-- Deletar permanentemente (apenas inativos) -->
                                            <?php if (!$user['active']): ?>
                                                <button onclick="hardDeleteUser(<?= $user['id'] ?>)" 
                                                        class="user-action-btn delete" 
                                                        title="Deletar permanentemente">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-users-state">
                <i class="fas fa-users"></i>
                <h5>Nenhum usuário encontrado</h5>
                <p>Não há usuários cadastrados no sistema ou que correspondam aos filtros aplicados.</p>
                <a href="<?= BASE_URL ?>user/add" class="btn">
                    <i class="fas fa-plus"></i> Adicionar Primeiro Usuário
                </a>
            </div>
        <?php endif; ?>
    </div>    <!-- Paginação -->
    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div class="modern-pagination">
            <!-- Página anterior -->
            <?php if ($pagination['current_page'] > 1): ?>
                <a href="?page=<?= $pagination['current_page'] - 1 ?><?= $searchQuery ?>" 
                   class="pagination-btn">
                    <i class="fas fa-chevron-left"></i> Anterior
                </a>
            <?php endif; ?>
            
            <!-- Páginas numeradas -->
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="?page=<?= $i ?><?= $searchQuery ?>" 
                   class="pagination-btn <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <!-- Próxima página -->
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="?page=<?= $pagination['current_page'] + 1 ?><?= $searchQuery ?>" 
                   class="pagination-btn">
                    Próxima <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Scripts para ações AJAX -->
<script>
function toggleAdmin(userId, makeAdmin) {
    const action = makeAdmin ? 'tornar administrador' : 'remover privilégios de administrador';
    
    if (confirm(`Tem certeza que deseja ${action} este usuário?`)) {
        const baseUrl = window.BASE_URL || '/';
        fetch(baseUrl + 'user/toggleAdmin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message || 'Erro ao alterar privilégios do usuário');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Erro de conexão');
        });
    }
}

function deactivateUser(userId) {
    if (confirm('Tem certeza que deseja desativar este usuário?')) {
        const baseUrl = window.BASE_URL || '/';
        fetch(baseUrl + 'user/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message || 'Erro ao desativar usuário');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Erro de conexão');
        });
    }
}

function reactivateUser(userId) {
    if (confirm('Tem certeza que deseja reativar este usuário?')) {
        const baseUrl = window.BASE_URL || '/';
        fetch(baseUrl + 'user/reactivate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message || 'Erro ao reativar usuário');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Erro de conexão');
        });
    }
}

function hardDeleteUser(userId) {
    if (confirm('ATENÇÃO: Esta ação irá deletar permanentemente o usuário e todos os seus dados. Esta ação não pode ser desfeita. Tem certeza?')) {
        const baseUrl = window.BASE_URL || '/';
        fetch(baseUrl + 'user/delete/hard', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('danger', data.message || 'Erro ao deletar usuário');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Erro de conexão');
        });
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `modern-alert ${type}`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}
</script>



<?php include_once ROOT . '/views/layouts/footer.php'; ?>
