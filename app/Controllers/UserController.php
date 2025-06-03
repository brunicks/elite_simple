<?php
// app/Controllers/UserController.php - Controlador de gerenciamento de usuários

require_once APP . '/Core/Controller.php';

class UserController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }
      // Listar todos os usuários (apenas admin)
    public function index() {
        $this->requireAdmin();
        
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        
        // Usar filtros avançados se algum parâmetro foi fornecido
        if (!empty($search) || !empty($role) || !empty($status)) {
            $users = $this->userModel->searchUsersWithFilters($search, $role, $status);
        } else {
            $users = $this->userModel->getAllUsersWithInactive();
        }
        
        $data = [
            'title' => 'Gerenciar Usuários - Admin',
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status
        ];
        
        $this->view('user/index', $data);
    }
    
    // Exibir formulário de adicionar usuário (apenas admin)
    public function add() {
        $this->requireAdmin();
        
        $data = [
            'title' => 'Adicionar Usuário - Admin'
        ];
        
        $this->view('user/add', $data);
    }
    
    // Processar criação de usuário (apenas admin)
    public function create() {
        $this->requireAdmin();
          if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . 'user/add');
        }
        
        // Get form data using the names the form actually sends
        $nome = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['password'] ?? '');
        $is_admin = (int)($_POST['is_admin'] ?? 0);
        $active = (int)($_POST['active'] ?? 1);
        
        // Convert is_admin boolean to database tipo enum
        $tipo = $is_admin ? 'admin' : 'usuario';
        
        // Validações
        if (empty($nome) || empty($email) || empty($senha)) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos obrigatórios.';
            $this->redirect(BASE_URL . 'user/add');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email inválido.';
            $this->redirect(BASE_URL . 'user/add');
        }
        
        if (strlen($senha) < 6) {
            $_SESSION['error'] = 'A senha deve ter pelo menos 6 caracteres.';
            $this->redirect(BASE_URL . 'user/add');
        }
        
        // Verificar se email já existe (incluindo inativos)
        if ($this->userModel->emailExistsWithInactive($email)) {
            $_SESSION['error'] = 'Este email já está em uso.';
            $this->redirect(BASE_URL . 'user/add');
        }
          // Validar tipo de usuário
        if (!in_array($tipo, ['usuario', 'admin'])) {
            $tipo = 'usuario';
        }
          // Criar usuário
        $userData = [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo' => $tipo,
            'ativo' => $active,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->userModel->create($userData)) {
            $_SESSION['success'] = 'Usuário criado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao criar usuário.';
        }
        
        $this->redirect(BASE_URL . 'user');
    }
      // Exibir formulário de editar usuário (apenas admin)
    public function edit($id) {
        $this->requireAdmin();
        
        $user = $this->userModel->findByIdWithInactive($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado.';
            $this->redirect(BASE_URL . 'user');
        }
        
        // Transform database columns to view expected format
        $user['name'] = $user['nome'];
        $user['is_admin'] = ($user['tipo'] === 'admin') ? 1 : 0;
        $user['active'] = $user['ativo'];
        
        $data = [
            'title' => 'Editar Usuário - Admin',
            'user' => $user
        ];
        
        $this->view('user/edit', $data);
    }
    
    // Processar atualização de usuário (apenas admin)
    public function update($id) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . 'user/edit/' . $id);
        }
          $user = $this->userModel->findByIdWithInactive($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Usuário não encontrado.';
            $this->redirect(BASE_URL . 'user');
        }
        
        // Get form data using the names the form actually sends
        $nome = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['password'] ?? '');
        $is_admin = (int)($_POST['is_admin'] ?? 0);
        $active = (int)($_POST['active'] ?? 1);
        
        // Convert is_admin boolean to database tipo enum
        $tipo = $is_admin ? 'admin' : 'usuario';
        
        // Validações
        if (empty($nome) || empty($email)) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos obrigatórios.';
            $this->redirect(BASE_URL . 'user/edit/' . $id);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email inválido.';
            $this->redirect(BASE_URL . 'user/edit/' . $id);
        }
        
        // Verificar se email já existe em outro usuário
        $existingUser = $this->userModel->findByEmailWithInactive($email);
        if ($existingUser && $existingUser['id'] != $id) {
            $_SESSION['error'] = 'Este email já está em uso por outro usuário.';
            $this->redirect(BASE_URL . 'user/edit/' . $id);
        }
          // Validar tipo de usuário
        if (!in_array($tipo, ['usuario', 'admin'])) {
            $tipo = 'usuario';
        }        // Preparar dados para atualização
        $userData = [
            'nome' => $nome,
            'email' => $email,
            'tipo' => $tipo,
            'ativo' => $active
        ];
        
        // Adicionar senha apenas se foi fornecida
        if (!empty($senha)) {
            if (strlen($senha) < 6) {
                $_SESSION['error'] = 'A senha deve ter pelo menos 6 caracteres.';
                $this->redirect(BASE_URL . 'user/edit/' . $id);
            }
            $userData['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }
        
        // Atualizar usuário
        if ($this->userModel->update('usuarios', $userData, $id)) {
            $_SESSION['success'] = 'Usuário atualizado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao atualizar usuário.';
        }
        
        $this->redirect(BASE_URL . 'user');
    }    // Soft Delete - Desativar usuário (AJAX)
    public function delete() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }

        // Obter dados do POST (JSON)
        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);
        
        // Verificar se é hard delete (parâmetro adicional na URL)
        $segments = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $isHardDelete = end($segments) === 'hard';
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido']);
        }
        
        // Verificar se não está tentando deletar o próprio usuário
        if ($id == $_SESSION['user_id']) {
            $this->json(['success' => false, 'message' => 'Você não pode deletar sua própria conta']);
        }
        
        $user = $this->userModel->findByIdWithInactive($id);
        
        if (!$user) {
            $this->json(['success' => false, 'message' => 'Usuário não encontrado']);
        }
        
        if ($isHardDelete) {
            // Hard delete - deletar permanentemente (apenas usuários inativos)
            if ($user['ativo']) {
                $this->json(['success' => false, 'message' => 'Só é possível deletar permanentemente usuários inativos']);
            }
            
            if ($this->userModel->hardDeleteUser($id)) {
                $this->json(['success' => true, 'message' => 'Usuário deletado permanentemente']);
            } else {
                $this->json(['success' => false, 'message' => 'Erro ao deletar usuário permanentemente']);
            }
        } else {
            // Soft delete - desativar usuário
            if ($this->userModel->deleteUser($id)) {
                $this->json(['success' => true, 'message' => 'Usuário desativado com sucesso']);
            } else {
                $this->json(['success' => false, 'message' => 'Erro ao desativar usuário']);
            }
        }
    }    // Reativar usuário (AJAX)
    public function reactivate() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }
        
        // Obter dados do POST (JSON)
        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido']);
        }
        
        if ($this->userModel->reactivateUser($id)) {
            $this->json(['success' => true, 'message' => 'Usuário reativado com sucesso']);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao reativar usuário']);
        }
    }
      // Promover usuário para admin ou rebaixar admin para user (AJAX)
    public function toggleAdmin() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }
        
        // Obter dados do POST (JSON)
        $input = json_decode(file_get_contents('php://input'), true);
        $id = (int)($input['id'] ?? 0);
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido']);
        }
        
        // Verificar se não está tentando alterar o próprio tipo
        if ($id == $_SESSION['user_id']) {
            $this->json(['success' => false, 'message' => 'Você não pode alterar seu próprio tipo de usuário']);
        }
        
        $user = $this->userModel->findByIdWithInactive($id);
        
        if (!$user) {
            $this->json(['success' => false, 'message' => 'Usuário não encontrado']);
        }        // Alternar tipo de usuário
        $newType = ($user['tipo'] === 'admin') ? 'usuario' : 'admin';
        
        $userData = [
            'tipo' => $newType
        ];
        
        if ($this->userModel->update('usuarios', $userData, $id)) {
            $action = ($newType === 'admin') ? 'promovido a administrador' : 'rebaixado para usuário comum';
            $this->json(['success' => true, 'message' => "Usuário {$action} com sucesso", 'new_type' => $newType]);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao alterar tipo de usuário']);
        }
    }
}
?>
