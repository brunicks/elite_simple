<?php
// app/Core/Controller.php - Controlador base

class Controller {
    // Carregar modelo
    public function model($model) {
        require_once APP . '/Models/' . $model . '.php';
        return new $model();
    }
      // Carregar view
    public function view($view, $data = []) {
        // Tornar dados disponíveis na view
        extract($data);
        
        $viewPath = ROOT . '/views/' . $view . '.php';
        
        // Verificar se arquivo existe
        if (!file_exists($viewPath)) {
            throw new Exception("View não encontrada: " . $viewPath);
        }
        
        require_once $viewPath;
    }
    
    // Redirecionar
    public function redirect($url) {
        header("Location: " . $url);
        exit;    }
    
    // Verificar se usuário está logado
    public function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . 'auth');
        }    }
      // Verificar se é admin
    public function requireAdmin() {
        $this->requireAuth();
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            $this->redirect(BASE_URL . 'dashboard/user');
        }
    }
    
    // Retornar JSON
    public function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    // Mostrar mensagem de erro/sucesso
    public function showMessage($type, $message) {
        $class = $type === 'error' ? 'alert-error' : 'alert-success';
        return "<div class='alert $class'>$message</div>";
    }
}
?>
