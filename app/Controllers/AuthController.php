<?php
// app/Controllers/AuthController.php - Controlador de autenticação

require_once APP . '/Core/Controller.php';

class AuthController extends Controller {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = $this->model('User');
    }    // Exibir formulário de login/registro
    public function index() {
        // Se já estiver logado, redirecionar
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_type'] === 'admin') {
                $this->redirect(BASE_URL . 'dashboard');
            } else {
                $this->redirect(BASE_URL . 'dashboard/user');
            }
        }
        
        $data = [
            'title' => 'Login - Concessionária',
            'error' => '',
            'success' => ''
        ];
        
        $this->view('auth/index', $data);
    }
      // Processar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL);
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['senha'] ?? '';
        
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos.';
            $this->redirect(BASE_URL);
        }
        
        // Tentar autenticar
        $user = $this->userModel->authenticate($email, $password);
          if ($user) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['tipo'];
            
            // Redirect based on user type
            if ($user['tipo'] === 'admin') {
                $this->redirect(BASE_URL . 'dashboard');
            } else {
                $this->redirect(BASE_URL . 'dashboard/user');
            }
        } else {
            $_SESSION['error'] = 'Email ou senha incorretos.';
            $this->redirect(BASE_URL);
        }
    }
      // Processar registro
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL);
        }
        
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';
        
        // Validações
        if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos.';
            $this->redirect(BASE_URL);
        }
        
        if ($senha !== $confirmar_senha) {
            $_SESSION['error'] = 'As senhas não coincidem.';
            $this->redirect(BASE_URL);
        }
        
        if (strlen($senha) < 3) {
            $_SESSION['error'] = 'A senha deve ter pelo menos 3 caracteres.';
            $this->redirect(BASE_URL);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email inválido.';
            $this->redirect(BASE_URL);
        }
        
        // Verificar se email já existe
        if ($this->userModel->emailExists($email)) {
            $_SESSION['error'] = 'Este email já está cadastrado.';
            $this->redirect(BASE_URL);
        }
          // Criar usuário
        $userData = [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT), // Hash da senha
            'tipo' => 'usuario'
        ];
        
        if ($this->userModel->create($userData)) {
            $_SESSION['success'] = 'Usuário cadastrado com sucesso! Faça login para continuar.';
        } else {
            $_SESSION['error'] = 'Erro ao cadastrar usuário.';
        }
        
        $this->redirect(BASE_URL);
    }    // Logout
    public function logout() {
        session_destroy();
        // Iniciar nova sessão apenas para a mensagem de logout
        session_start();
        $_SESSION['success'] = 'Logout realizado com sucesso! Você pode continuar navegando pelos nossos carros.';
        $this->redirect(BASE_URL);
    }
}
?>
