<?php
// app/Controllers/CarController.php - Controlador de carros

require_once APP . '/Core/Controller.php';

class CarController extends Controller {
    
    private $carModel;
    
    public function __construct() {
        $this->carModel = $this->model('Car');
    }
    
    // Helper method for role-based dashboard redirects
    private function redirectToDashboard() {
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
            $this->redirect(BASE_URL . 'dashboard');
        } else {
            $this->redirect(BASE_URL . 'dashboard/user');
        }
    }
    
    // Exibir formulário de adicionar carro
    public function add() {
        $this->requireAdmin();
        
        $data = [
            'title' => 'Adicionar Carro - Concessionária'
        ];
        
        $this->view('cars/add', $data);
    }
    
    // Processar criação de carro
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . 'car/add');
        }
        
        // Campos básicos obrigatórios
        $modelo = trim($_POST['modelo'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $ano = (int)($_POST['ano'] ?? 0);
        $preco = (float)str_replace(',', '.', str_replace('.', '', $_POST['preco'] ?? '0'));
        $km = (int)($_POST['km'] ?? 0);
        
        // Novos campos técnicos opcionais
        $cv = !empty($_POST['cv']) ? (int)($_POST['cv']) : null;
        $motor = trim($_POST['motor'] ?? '') ?: null;
        $torque = trim($_POST['torque'] ?? '') ?: null;
        $combustivel = trim($_POST['combustivel'] ?? '') ?: null;
        $transmissao = trim($_POST['transmissao'] ?? '') ?: null;
        $portas = !empty($_POST['portas']) ? (int)($_POST['portas']) : null;
        $cor = trim($_POST['cor'] ?? '') ?: null;
        $consumo_medio = !empty($_POST['consumo_medio']) ? (float)str_replace(',', '.', $_POST['consumo_medio']) : null;
        $descricao = trim($_POST['descricao'] ?? '') ?: null;
        
        // Validações básicas obrigatórias
        if (empty($modelo) || empty($marca) || empty($ano) || empty($preco) || empty($km)) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos obrigatórios (marca, modelo, ano, preço e quilometragem).';
            $this->redirect(BASE_URL . 'car/add');
        }
        
        if ($ano < 1900 || $ano > date('Y') + 1) {
            $_SESSION['error'] = 'Ano inválido.';
            $this->redirect(BASE_URL . 'car/add');
        }
        
        if ($preco <= 0) {
            $_SESSION['error'] = 'Preço deve ser maior que zero.';
            $this->redirect(BASE_URL . 'car/add');
        }
        
        if ($km < 0) {
            $_SESSION['error'] = 'Quilometragem não pode ser negativa.';
            $this->redirect(BASE_URL . 'car/add');
        }
        
        // Validações adicionais para campos técnicos
        if ($cv !== null && ($cv < 50 || $cv > 2000)) {
            $_SESSION['error'] = 'Potência (CV) deve estar entre 50 e 2000.';
            $this->redirect(BASE_URL . 'car/add');
        }
        
        if ($portas !== null && ($portas < 2 || $portas > 6)) {
            $_SESSION['error'] = 'Número de portas deve estar entre 2 e 6.';
            $this->redirect(BASE_URL . 'car/add');
        }
        
        if ($consumo_medio !== null && ($consumo_medio < 3 || $consumo_medio > 30)) {
            $_SESSION['error'] = 'Consumo médio deve estar entre 3 e 30 km/l.';
            $this->redirect(BASE_URL . 'car/add');
        }
        
        // Upload de imagem
        $imagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $imagem = $this->uploadImage($_FILES['imagem']);
            
            if (!$imagem) {
                $_SESSION['error'] = 'Erro no upload da imagem.';
                $this->redirect(BASE_URL . 'car/add');
            }
        }
          // Criar carro com novos campos técnicos
        $carData = [
            'modelo' => $modelo,
            'marca' => $marca,
            'ano' => $ano,
            'preco' => $preco,
            'km' => $km,
            'imagem' => $imagem,
            'cv' => $cv,
            'motor' => $motor,
            'torque' => $torque,
            'combustivel' => $combustivel,
            'transmissao' => $transmissao,
            'portas' => $portas,
            'cor' => $cor,
            'consumo_medio' => $consumo_medio,
            'descricao' => $descricao
        ];
          if ($this->carModel->createCar($carData)) {
            $_SESSION['success'] = 'Carro adicionado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao adicionar carro.';
        }
        
        $this->redirectToDashboard();
    }
      // Exibir formulário de editar carro
    public function edit($id) {
        $this->requireAdmin();
          // Usar método que pode buscar carros inativos (para admin poder editar)
        $car = $this->carModel->getCarByIdWithInactive($id);
        
        if (!$car) {
            $_SESSION['error'] = 'Carro não encontrado.';
            $this->redirectToDashboard();
        }
        
        $data = [
            'title' => 'Editar Carro - Concessionária',
            'car' => $car
        ];
        
        $this->view('cars/edit', $data);
    }
      // Processar atualização de carro
    public function update($id) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
        
        $car = $this->carModel->getCarByIdWithInactive($id);
        
        if (!$car) {
            $_SESSION['error'] = 'Carro não encontrado.';
            $this->redirectToDashboard();
        }
          $modelo = trim($_POST['modelo'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $ano = (int)($_POST['ano'] ?? 0);
        $preco = (float)str_replace(',', '.', str_replace('.', '', $_POST['preco'] ?? '0'));
        $km = (int)($_POST['km'] ?? 0);
        
        // Novos campos técnicos opcionais
        $cv = !empty($_POST['cv']) ? (int)($_POST['cv']) : null;
        $motor = trim($_POST['motor'] ?? '') ?: null;
        $torque = trim($_POST['torque'] ?? '') ?: null;
        $combustivel = trim($_POST['combustivel'] ?? '') ?: null;
        $transmissao = trim($_POST['transmissao'] ?? '') ?: null;
        $portas = !empty($_POST['portas']) ? (int)($_POST['portas']) : null;
        $cor = trim($_POST['cor'] ?? '') ?: null;
        $consumo_medio = !empty($_POST['consumo_medio']) ? (float)str_replace(',', '.', $_POST['consumo_medio']) : null;
        $descricao = trim($_POST['descricao'] ?? '') ?: null;
        
        // Validações básicas obrigatórias
        if (empty($modelo) || empty($marca) || empty($ano) || empty($preco) || empty($km)) {
            $_SESSION['error'] = 'Por favor, preencha todos os campos obrigatórios (marca, modelo, ano, preço e quilometragem).';
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
        
        if ($ano < 1900 || $ano > date('Y') + 1) {
            $_SESSION['error'] = 'Ano inválido.';
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
        
        if ($preco <= 0) {
            $_SESSION['error'] = 'Preço deve ser maior que zero.';
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
          if ($km < 0) {
            $_SESSION['error'] = 'Quilometragem não pode ser negativa.';
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
        
        // Validações adicionais para campos técnicos
        if ($cv !== null && ($cv < 50 || $cv > 2000)) {
            $_SESSION['error'] = 'Potência (CV) deve estar entre 50 e 2000.';
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
        
        if ($portas !== null && ($portas < 2 || $portas > 6)) {
            $_SESSION['error'] = 'Número de portas deve estar entre 2 e 6.';
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
        
        if ($consumo_medio !== null && ($consumo_medio < 3 || $consumo_medio > 30)) {
            $_SESSION['error'] = 'Consumo médio deve estar entre 3 e 30 km/l.';
            $this->redirect(BASE_URL . 'car/edit/' . $id);
        }
        
        // Upload de nova imagem (opcional)
        $imagem = $car['imagem']; // Manter a imagem atual por padrão
        
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $newImagem = $this->uploadImage($_FILES['imagem']);
            
            if ($newImagem) {                // Deletar imagem antiga
                if ($car['imagem']) {
                    $oldImagePath = PUBLIC_PATH . '/uploads/' . $car['imagem'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $imagem = $newImagem;
            }
        }
        
        // Atualizar carro com novos campos técnicos
        $carData = [
            'modelo' => $modelo,
            'marca' => $marca,
            'ano' => $ano,
            'preco' => $preco,
            'km' => $km,
            'imagem' => $imagem,
            'cv' => $cv,
            'motor' => $motor,
            'torque' => $torque,
            'combustivel' => $combustivel,
            'transmissao' => $transmissao,
            'portas' => $portas,
            'cor' => $cor,
            'consumo_medio' => $consumo_medio,
            'descricao' => $descricao,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->carModel->updateCar($id, $carData)) {
            $_SESSION['success'] = 'Carro atualizado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao atualizar carro.';
        }

        $this->redirectToDashboard();
    }
      // Soft Delete - Desativar carro (AJAX)
    public function delete() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido']);
        }
        
        $car = $this->carModel->getCarByIdWithInactive($id);
        
        if (!$car) {
            $this->json(['success' => false, 'message' => 'Carro não encontrado']);
        }
        
        if ($this->carModel->deleteCar($id)) {
            $this->json(['success' => true, 'message' => 'Carro desativado com sucesso (soft delete)']);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao desativar carro']);
        }
    }
    
    // Hard Delete - Deletar permanentemente (apenas para casos especiais)
    public function hardDelete() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido']);
        }
        
        $car = $this->carModel->getCarByIdWithInactive($id);
        
        if (!$car) {
            $this->json(['success' => false, 'message' => 'Carro não encontrado']);
        }
        
        if ($this->carModel->hardDeleteCar($id)) {
            $this->json(['success' => true, 'message' => 'Carro deletado permanentemente']);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao deletar carro permanentemente']);
        }
    }
    
    // Reativar carro (desfazer soft delete)
    public function reactivate() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método inválido']);
        }
        
        $id = (int)($_POST['id'] ?? 0);
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID inválido']);
        }
        
        if ($this->carModel->reactivateCar($id)) {
            $this->json(['success' => true, 'message' => 'Carro reativado com sucesso']);
        } else {
            $this->json(['success' => false, 'message' => 'Erro ao reativar carro']);
        }
    }
    
    // Exibir detalhes de um carro
    public function details($id) {
        if (!$id) {
            $this->redirect(BASE_URL);
        }
        
        $car = $this->carModel->getCarById($id);
        
        if (!$car) {
            $_SESSION['error'] = 'Carro não encontrado.';
            $this->redirect(BASE_URL);
        }
          $data = [
            'title' => $car['marca'] . ' ' . $car['modelo'] . ' - Detalhes',
            'car' => $car
        ];
        
        // Se usuário logado, verificar se é favorito e adicionar aos visitados recentemente
        if (isset($_SESSION['user_id'])) {
            $favoriteModel = $this->model('Favorite');
            $recentlyViewedModel = $this->model('RecentlyViewed');
            
            $userId = $_SESSION['user_id'];
            $data['is_favorite'] = $favoriteModel->isFavorite($userId, $id);
            $data['user_logged'] = true;
            
            // Adicionar aos carros visitados recentemente
            $recentlyViewedModel->addView($userId, $id);
        } else {
            $data['is_favorite'] = false;
            $data['user_logged'] = false;
        }
          $this->view('cars/details', $data);
    }
    
    // Listar carros com paginação - Catálogo completo com filtros avançados
    public function index() {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 12; // 12 carros por página conforme especificado
        
        // Garantir que página seja válida
        if ($page < 1) {
            $page = 1;
        }
          // Coletar filtros da URL (incluindo novos campos técnicos)
        $filters = [
            'search' => $_GET['search'] ?? '',
            'marca' => $_GET['marca'] ?? '',
            'ano_min' => $_GET['ano_min'] ?? '',
            'ano_max' => $_GET['ano_max'] ?? '',
            'preco_min' => $_GET['preco_min'] ?? '',
            'preco_max' => $_GET['preco_max'] ?? '',
            'km_max' => $_GET['km_max'] ?? '',
            'combustivel' => $_GET['combustivel'] ?? '',
            'transmissao' => $_GET['transmissao'] ?? '',
            'cor' => $_GET['cor'] ?? '',
            'portas' => $_GET['portas'] ?? '',
            'cv_min' => $_GET['cv_min'] ?? '',
            'cv_max' => $_GET['cv_max'] ?? '',
            'consumo_min' => $_GET['consumo_min'] ?? '',
            'consumo_max' => $_GET['consumo_max'] ?? '',
            'sort' => $_GET['sort'] ?? 'recente'
        ];
        
        // Buscar carros com filtros avançados
        $cars = $this->carModel->getCarsPaginatedAdvanced($page, $perPage, $filters);
        $totalCars = $this->carModel->getCarsPaginatedAdvancedCount($filters);        $totalPages = ceil($totalCars / $perPage);
        
        // Obter dados para os filtros (incluindo novos campos técnicos)
        $allBrands = $this->carModel->getAllBrands();
        $priceRanges = $this->carModel->getPriceRanges();
        $yearRanges = $this->carModel->getYearRanges();
        $allFuelTypes = $this->carModel->getAllFuelTypes();
        $allTransmissionTypes = $this->carModel->getAllTransmissionTypes();
        $allColors = $this->carModel->getAllColors();        $powerRanges = $this->carModel->getPowerRanges();
        $consumptionRanges = $this->carModel->getConsumptionRanges();
        
        // Se usuário logado, adicionar status de favoritos e comparação
        if (isset($_SESSION['user_id'])) {
            $favoriteModel = $this->model('Favorite');
            $userId = $_SESSION['user_id'];
            
            // Para cada carro, verificar se é favorito
            foreach ($cars as &$car) {
                $car['is_favorite'] = $favoriteModel->isFavorite($userId, $car['id']);            }
        } else {
            // Para usuários não logados, marcar todos como não favoritos
            foreach ($cars as &$car) {
                $car['is_favorite'] = false;
            }
        }
        
        $data = [
            'title' => 'Catálogo Completo - EliteMotors',
            'cars' => $cars,
            'filters' => $filters,
            'all_brands' => $allBrands,
            'price_ranges' => $priceRanges,
            'year_ranges' => $yearRanges,
            'all_fuel_types' => $allFuelTypes,
            'all_transmission_types' => $allTransmissionTypes,
            'all_colors' => $allColors,
            'power_ranges' => $powerRanges,
            'consumption_ranges' => $consumptionRanges,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_cars' => $totalCars,
            'per_page' => $perPage,
            'is_logged_in' => isset($_SESSION['user_id']),
            'user_logged' => isset($_SESSION['user_id'])
        ];
        
        $this->view('cars/index', $data);
    }

    // Upload de imagem
    private function uploadImage($file) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $file['name'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Validar extensão
        if (!in_array($extension, $allowed)) {
            return false;
        }
        
        // Validar tamanho (5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }
          // Gerar nome único
        $newFilename = uniqid() . '.' . $extension;
        $uploadPath = PUBLIC_PATH . '/uploads/' . $newFilename;
        
        // Criar diretório se não existir
        if (!is_dir(PUBLIC_PATH . '/uploads/')) {
            mkdir(PUBLIC_PATH . '/uploads/', 0777, true);
        }
        
        // Mover arquivo
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $newFilename;
        }
        
        return false;
    }
}
?>
