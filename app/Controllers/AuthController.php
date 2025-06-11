<?php
// app/Controllers/AuthController.php - Controlador de autenticação

require_once APP . '/Core/Controller.php';
require_once APP . '/Services/EmailService.php';

class AuthController extends Controller {
    
    private $userModel;
    private $emailService;
    
    public function __construct() {
        $this->userModel = $this->model('User');
        $this->emailService = new EmailService();
    }// Exibir formulário de login/registro
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
    
    // ===== MÉTODOS PARA RESET DE SENHA =====
    
    // Exibir formulário de esqueci minha senha
    public function forgot_password() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . 'dashboard');
        }
        
        $data = [
            'title' => 'Esqueci Minha Senha - Elite Motors'
        ];
        
        $this->view('auth/forgot_password', $data);
    }
      // Processar solicitação de reset de senha
    public function request_reset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . 'auth/forgot_password');
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            $_SESSION['error'] = 'Por favor, digite seu email.';
            $this->redirect(BASE_URL . 'auth/forgot_password');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email inválido.';
            $this->redirect(BASE_URL . 'auth/forgot_password');
        }
        
        // Verificar se o email existe e obter dados do usuário
        $user = $this->userModel->getUserByEmail($email);
          if ($user) {
            // Gerar token de reset
            $token = $this->userModel->createPasswordResetToken($email);
            
            if ($token) {
                // Verificar se email está configurado
                if ($this->emailService->isConfigured()) {
                    // Tentar enviar email
                    $emailSent = $this->emailService->sendPasswordResetEmail($email, $user['nome'], $token);
                    
                    if ($emailSent) {
                        $_SESSION['success'] = "Email de redefinição enviado! Verifique sua caixa de entrada e spam.";
                    } else {
                        // Fallback: mostrar link para teste se o email falhar
                        $resetLink = BASE_URL . "auth/reset_password?token=" . $token;
                        $_SESSION['success'] = "Problema ao enviar email. <strong>Para teste:</strong> <a href='{$resetLink}' style='color: #d4af37;'>Clique aqui para resetar sua senha</a>";
                    }
                } else {
                    // Email não configurado: mostrar link direto
                    $resetLink = BASE_URL . "auth/reset_password?token=" . $token;
                    $_SESSION['success'] = "Email não configurado. <strong>Para teste:</strong> <a href='{$resetLink}' style='color: #d4af37;'>Clique aqui para resetar sua senha</a>
                                          <br><small style='color: #888;'>Configure o email em app/Config/EmailConfig.php para envio automático.</small>";
                }
            } else {
                $_SESSION['error'] = 'Erro ao processar solicitação. Tente novamente.';
            }
        } else {
            // Por segurança, sempre mostramos a mesma mensagem
            $_SESSION['success'] = "Se o email existir em nossa base, você receberá um link de redefinição.";
        }
        
        $this->redirect(BASE_URL . 'auth/forgot_password');
    }
    
    // Exibir formulário de nova senha
    public function reset_password() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['error'] = 'Token de reset inválido.';
            $this->redirect(BASE_URL . 'auth/forgot_password');
        }
        
        // Verificar se token é válido
        $user = $this->userModel->verifyResetToken($token);
        if (!$user) {
            $_SESSION['error'] = 'Token de reset inválido ou expirado.';
            $this->redirect(BASE_URL . 'auth/forgot_password');
        }
        
        $data = [
            'title' => 'Nova Senha - Elite Motors',
            'token' => $token,
            'user' => $user
        ];
        
        $this->view('auth/reset_password', $data);
    }
    
    // Processar nova senha
    public function update_password() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . 'auth/forgot_password');
        }
        
        $token = $_POST['token'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';

        if (empty($token) || empty($senha) || empty($confirmar_senha)) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos.';
            $this->redirect(BASE_URL . "auth/reset_password?token=" . $token);
        }

        if ($senha !== $confirmar_senha) {
            $_SESSION['error'] = 'As senhas não coincidem.';
            $this->redirect(BASE_URL . "auth/reset_password?token=" . $token);
        }

        if (strlen($senha) < 3) {
            $_SESSION['error'] = 'A senha deve ter pelo menos 3 caracteres.';
            $this->redirect(BASE_URL . "auth/reset_password?token=" . $token);
        }

        // Verificar se a nova senha é igual à anterior
        $user = $this->userModel->verifyResetToken($token);
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['error'] = 'A nova senha não pode ser igual à senha atual.';
            $this->redirect(BASE_URL . "auth/reset_password?token=" . $token);
        }

        // Resetar senha
        if ($this->userModel->resetPassword($token, $senha)) {
            $_SESSION['success'] = 'Senha alterada com sucesso! Faça login com sua nova senha.';
            $this->redirect(BASE_URL);
        } else {
            $_SESSION['error'] = 'Erro ao alterar senha. Token pode ter expirado.';
            $this->redirect(BASE_URL . 'auth/forgot_password');
        }
    }
}
?>
