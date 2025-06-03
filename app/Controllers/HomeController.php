<?php
// app/Controllers/HomeController.php - Controlador da página inicial

require_once APP . '/Core/Controller.php';

class HomeController extends Controller {
    
    private $carModel;
    private $favoriteModel;
    
    public function __construct() {
        $this->carModel = $this->model('Car');
        
        // Só carregar modelo de favoritos se o usuário estiver logado
        if (isset($_SESSION['user_id'])) {
            $this->favoriteModel = $this->model('Favorite');
        }
    }      // Página inicial - Apenas carousel com 5 carros mais caros
    public function index() {
        $search = $_GET['search'] ?? '';
        $isLoggedIn = isset($_SESSION['user_id']);
        
        // Para homepage: sempre mostrar apenas os 5 carros mais caros no carousel
        // Se há busca, redirecionar para catálogo completo
        if (!empty($search)) {
            $this->redirect(BASE_URL . 'car?search=' . urlencode($search));
        }
        
        // Buscar os 5 carros mais caros para o carousel
        $cars = $this->carModel->getTopExpensiveCars(5);
        
        if ($isLoggedIn) {
            // Para usuários logados, adicionar status de favoritos apenas nos carros do carousel
            $userId = $_SESSION['user_id'];
            if ($this->favoriteModel) {
                foreach ($cars as &$car) {
                    $car['is_favorite'] = $this->favoriteModel->isFavorite($userId, $car['id']);
                }
            } else {
                // Fallback caso não tenha favoriteModel
                foreach ($cars as &$car) {
                    $car['is_favorite'] = 0;
                }
            }
            
            $data = [
                'title' => 'Concessionária EliteMotors - Veículos Premium',
                'cars' => $cars,
                'search' => '',
                'is_public' => false,
                'user_logged' => true
            ];
        } else {
            // Usuário não logado - exibição pública apenas do carousel
            foreach ($cars as &$car) {
                $car['is_favorite'] = 0;
            }
            
            $data = [
                'title' => 'Concessionária EliteMotors - Veículos Premium',
                'cars' => $cars,
                'search' => '',
                'is_public' => true,
                'user_logged' => false
            ];
        }
        
        $this->view('home/index', $data);
    }
    
    /**
     * Página inicial para usuários logados (com favoritos)
     */
    public function logged() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect(BASE_URL);
        }
        
        $userId = $_SESSION['user_id'];
        $search = $_GET['search'] ?? '';
        
        // Buscar carros com status de favorito
        if ($this->favoriteModel) {
            $cars = $this->favoriteModel->getCarsWithFavoriteStatus($userId, $search);
        } else {
            // Fallback caso não tenha favoriteModel
            if (!empty($search)) {
                $cars = $this->carModel->searchCars($search);
            } else {
                $cars = $this->carModel->getAllCars();
            }
            // Adicionar campo is_favorite manualmente
            foreach ($cars as &$car) {
                $car['is_favorite'] = 0;
            }
        }
        
        $data = [
            'title' => 'Concessionária EliteMotors - Nossos Veículos',
            'cars' => $cars,
            'search' => $search,
            'is_public' => false,
            'user_logged' => true
        ];
        
        $this->view('home/index', $data);
    }
}
?>
