<?php
// app/Controllers/DashboardController.php - Controlador do dashboard

require_once APP . '/Core/Controller.php';

class DashboardController extends Controller {
    
    private $carModel;
    private $favoriteModel;
    private $financingModel;
    private $recentlyViewedModel;
    
    public function __construct() {
        $this->carModel = $this->model('Car');
        $this->favoriteModel = $this->model('Favorite');
        $this->financingModel = $this->model('FinancingSimulation');
        $this->recentlyViewedModel = $this->model('RecentlyViewed');
    }// Dashboard principal - ADMIN ONLY (CRUD Panel)
    public function index() {
        $this->requireAdmin(); // Only admins can access dashboard now
        
        $search = $_GET['search'] ?? '';
        $filter = $_GET['filter'] ?? 'all'; // all, active, inactive
        
        // Admin CRUD panel - manage all cars with advanced filters
        if (!empty($search)) {
            $cars = $this->carModel->searchCarsWithInactive($search);
        } else {
            $cars = $this->carModel->getAllCarsWithInactive();
        }
        
        // Apply status filter
        if ($filter === 'active') {
            $cars = array_filter($cars, function($car) { return $car['ativo'] == 1; });
        } elseif ($filter === 'inactive') {
            $cars = array_filter($cars, function($car) { return $car['ativo'] == 0; });
        }
        
        $data = [
            'title' => 'Painel Admin - CRUD Carros',
            'cars' => $cars,
            'search' => $search,
            'filter' => $filter,
            'user_name' => $_SESSION['user_name'],
            'total_cars' => count($this->carModel->getAllCarsWithInactive()),
            'active_cars' => count($this->carModel->getAllActiveCars()),
            'inactive_cars' => count($this->carModel->getAllCarsWithInactive()) - count($this->carModel->getAllActiveCars())
        ];
        
        $this->view('dashboard/admin', $data);
    }
      // User personalized area
    public function user() {
        $this->requireAuth();
        
        // Redirect admin to main dashboard
        if (($_SESSION['user_type'] ?? '') === 'admin') {
            $this->redirect(BASE_URL . 'dashboard');
        }
          $userId = $_SESSION['user_id'];
        
        // Get user statistics
        $favoritesCount = $this->favoriteModel->getUserFavoritesCount($userId);
        $simulationsCount = $this->financingModel->getUserSimulationsCount($userId);
        $recentlyViewedCount = $this->recentlyViewedModel->getRecentlyViewedCount($userId);
        
        // Get recent data
        $recentSimulations = $this->financingModel->getUserSimulations($userId);
        $recentlyViewed = $this->recentlyViewedModel->getRecentlyViewedCars($userId, 6);
        
        // Get all active cars for calculator dropdown
        $allCars = $this->carModel->getAllActiveCars();
        
        $data = [
            'title' => 'Minha Área - ' . $_SESSION['user_name'],
            'user_name' => $_SESSION['user_name'],
            'favorites_count' => $favoritesCount,
            'simulations_count' => $simulationsCount,
            'recently_viewed_count' => $recentlyViewedCount,
            'recent_simulations' => array_slice($recentSimulations, 0, 5),
            'recently_viewed' => $recentlyViewed,
            'all_cars' => $allCars
        ];
        
        $this->view('dashboard/user', $data);
    }
    
    // Estatísticas (para admin)
    public function stats() {
        $this->requireAdmin();
        
        $stats = $this->carModel->getStats();
        
        $data = [
            'title' => 'Estatísticas - Concessionária',
            'stats' => $stats
        ];
        
        $this->view('dashboard/stats', $data);
    }
    
    // Calcular financiamento via AJAX
    public function calculateFinancing() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $carPrice = floatval($_POST['car_price'] ?? 0);
        $downPayment = floatval($_POST['down_payment'] ?? 0);
        $interestRate = floatval($_POST['interest_rate'] ?? 0);
        $termMonths = intval($_POST['term_months'] ?? 0);
        
        if ($carPrice <= 0 || $termMonths <= 0) {
            echo json_encode(['success' => false, 'message' => 'Valores inválidos']);
            return;
        }
        
        $calculation = FinancingSimulation::calculateFinancing($carPrice, $downPayment, $interestRate, $termMonths);
        echo json_encode(['success' => true, 'calculation' => $calculation]);
    }
    
    // Salvar simulação de financiamento
    public function saveSimulation() {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
          $data = [
            'user_id' => $_SESSION['user_id'],
            'car_id' => $_POST['car_id'] ?? null,
            'car_name' => $_POST['car_name'] ?? 'Simulação Personalizada',
            'car_price' => floatval($_POST['car_price'] ?? 0),
            'down_payment' => floatval($_POST['down_payment'] ?? 0),
            'interest_rate' => floatval($_POST['interest_rate'] ?? 0),
            'term_months' => intval($_POST['term_months'] ?? 0),
            'monthly_payment' => floatval($_POST['monthly_payment'] ?? 0),
            'total_amount' => floatval($_POST['total_amount'] ?? 0),
            'total_interest' => floatval($_POST['total_interest'] ?? 0)
        ];
        
        $result = $this->financingModel->createSimulation($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Simulação salva com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar simulação']);
        }
    }
    
    // Deletar simulação
    public function deleteSimulation() {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $simulationId = intval($_POST['simulation_id'] ?? 0);
        $userId = $_SESSION['user_id'];
        
        $result = $this->financingModel->deleteSimulation($simulationId, $userId);
          if ($result) {
            echo json_encode(['success' => true, 'message' => 'Simulação excluída com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir simulação']);
        }
    }
}
?>
