<?php
// app/Core/Controller.php - Controlador base

class Controller {    // Carregar modelo
    public function model($model) {        // Mapeamento dos modelos organizados por categoria
        $modelPaths = [
            'User' => 'Auth/User.php',
            'Car' => 'Vehicle/Car.php',
            'Favorite' => 'User/Favorite.php',
            'RecentlyViewed' => 'User/RecentlyViewed.php',
            'FinancingSimulation' => 'User/FinancingSimulation.php',
        ];
        
        // Verificar se o modelo está no mapeamento
        if (isset($modelPaths[$model])) {
            $modelFile = APP . '/Models/' . $modelPaths[$model];
        } else {
            // Fallback para o caminho antigo (para compatibilidade)
            $modelFile = APP . '/Models/' . $model . '.php';
        }
        
        if (!file_exists($modelFile)) {
            throw new Exception("Model não encontrado: " . $modelFile);
        }
        
        require_once $modelFile;
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
