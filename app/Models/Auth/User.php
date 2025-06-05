<?php
// app/Models/User.php - Modelo do usuário

require_once APP . '/Core/Model.php';

class User extends Model {    // Buscar usuário por email (apenas ativos)
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Buscar usuário por email (incluindo inativos - para admin)
    public function findByEmailWithInactive($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }      // Criar novo usuário (always active by default)
    public function create($data) {
        // Verificar se a senha precisa ser hasheada (segurança adicional)
        if (isset($data['senha']) && !$this->isPasswordHashed($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }
        
        $data['ativo'] = 1; // Garantir que novo usuário seja criado como ativo
        
        return $this->insert('usuarios', $data);
    }
    
    // Verificar se a senha já está hasheada
    private function isPasswordHashed($password) {
        // Senhas hasheadas com password_hash() começam com $2y$ (bcrypt)
        return preg_match('/^\$2[ayb]\$/', $password);
    }
    
    // Verificar credenciais
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['senha'])) {
            return $user;
        }
        
        return false;
    }    // Verificar se email já existe (apenas usuários ativos)
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM usuarios WHERE email = ? AND ativo = 1");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    // Verificar se email já existe (incluindo inativos - para admin)
    public function emailExistsWithInactive($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    
    // Atualizar perfil
    public function updateProfile($id, $data) {
        // Remover senha se estiver vazia
        if (isset($data['senha']) && empty($data['senha'])) {
            unset($data['senha']);
        } else if (isset($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }
        
        return $this->update('usuarios', $data, $id);
    }    // Buscar todos os usuários ativos (para admin)
    public function getAllUsers() {
        $sql = "SELECT * FROM usuarios WHERE ativo = 1 ORDER BY created_at DESC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar todos os usuários (incluindo inativos - para admin)
    public function getAllUsersWithInactive() {
        $sql = "SELECT *, 
                       CASE WHEN tipo = 'admin' THEN 1 ELSE 0 END as is_admin,
                       ativo as active,
                       nome as name                FROM usuarios
                ORDER BY created_at DESC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      // Buscar usuário por ID (apenas ativos)
    public function getUserById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = ? AND ativo = 1";
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Buscar usuário por ID (incluindo inativos - para admin)
    public function findByIdWithInactive($id) {
        return $this->findById('usuarios', $id);
    }
      // Buscar usuários com filtros e paginação
    public function searchUsersWithFilters($search = '', $role = '', $status = '', $page = 1, $limit = 1000) {
        $offset = ($page - 1) * $limit;
        $where = [];
        $params = [];
        
        if (!empty($search)) {
            $where[] = "(nome LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($role)) {
            if ($role === 'admin') {
                $where[] = "tipo = 'admin'";
            } else if ($role === 'user') {
                $where[] = "tipo = 'usuario'";
            }
        }
        
        if (!empty($status)) {
            if ($status === 'active') {
                $where[] = "ativo = 1";
            } else if ($status === 'inactive') {
                $where[] = "ativo = 0";
            }
        }
        
        $whereClause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);
          // Query principal
        $sql = "SELECT *, 
                       CASE WHEN tipo = 'admin' THEN 1 ELSE 0 END as is_admin,
                       ativo as active,
                       nome as name
                FROM usuarios 
                $whereClause                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      // Contar usuários com filtros
    public function countUsersWithFilters($search = '', $role = '', $status = '') {
        $where = [];
        $params = [];
        
        if (!empty($search)) {
            $where[] = "(nome LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($role)) {
            if ($role === 'admin') {
                $where[] = "tipo = 'admin'";
            } else if ($role === 'user') {
                $where[] = "tipo = 'usuario'";
            }
        }
        
        if (!empty($status)) {
            if ($status === 'active') {
                $where[] = "ativo = 1";
            } else if ($status === 'inactive') {
                $where[] = "ativo = 0";
            }
        }
          $whereClause = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);
        
        $sql = "SELECT COUNT(*) as total FROM usuarios $whereClause";
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Soft Delete - Desativar usuário (preserva dados)
    public function deleteUser($id) {
        $updateData = ['ativo' => 0];
        return $this->update('usuarios', $updateData, $id);
    }
    
    // Hard Delete - Deletar usuário permanentemente
    public function hardDeleteUser($id) {
        return $this->delete('usuarios', $id);
    }
    
    // Reativar usuário (desfazer soft delete)
    public function reactivateUser($id) {
        $updateData = ['ativo' => 1];
        return $this->update('usuarios', $updateData, $id);
    }
    
    // Verificar se é admin (apenas usuários ativos)
    public function isAdmin($userId) {
        $user = $this->getUserById($userId);
        return $user && $user['tipo'] === 'admin';
    }
    
    // Buscar usuários por nome/email (incluindo inativos - para admin)
    public function searchUsersWithInactive($search) {
        $sql = "SELECT *, 
                       CASE WHEN tipo = 'admin' THEN 1 ELSE 0 END as is_admin,
                       ativo as active,
                       nome as name
                FROM usuarios 
                WHERE (nome LIKE ? OR email LIKE ?)                ORDER BY created_at DESC";
        $stmt = $this->query($sql, ["%$search%", "%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
