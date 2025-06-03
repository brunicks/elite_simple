<?php
// app/Controllers/FavoriteController.php

require_once APP . '/Core/Controller.php';
require_once APP . '/Models/Favorite.php';

class FavoriteController extends Controller {
    
    private $favoriteModel;    public function __construct() {
        $this->favoriteModel = $this->model('Favorite');
        
        // Verificar se usuário está logado para operações de favoritos
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Você precisa estar logado para gerenciar favoritos.';
            $this->redirect(BASE_URL . 'auth');
        }
    }
    
    /**
     * Adicionar/remover favorito via AJAX
     */
    public function toggle() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $carId = $_POST['car_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$carId || !$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }
        
        try {
            $isFavorite = $this->favoriteModel->isFavorite($userId, $carId);
            
            if ($isFavorite) {
                // Remover dos favoritos
                $result = $this->favoriteModel->removeFromFavorites($userId, $carId);
                $message = 'Carro removido dos favoritos';
                $action = 'removed';
            } else {
                // Adicionar aos favoritos
                $result = $this->favoriteModel->addToFavorites($userId, $carId);
                $message = 'Carro adicionado aos favoritos';
                $action = 'added';
            }
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => $message,
                    'action' => $action,
                    'is_favorite' => !$isFavorite
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao processar favorito']);
            }
            
        } catch (Exception $e) {
            error_log("Erro ao toggle favorite: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
        }
    }
    
    /**
     * Adicionar aos favoritos via AJAX
     */
    public function add() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $carId = $_POST['car_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$carId || !$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }
        
        try {
            // Verificar se já está nos favoritos
            $isFavorite = $this->favoriteModel->isFavorite($userId, $carId);
            
            if ($isFavorite) {
                echo json_encode(['success' => false, 'message' => 'Veículo já está nos favoritos']);
                return;
            }
            
            $result = $this->favoriteModel->addToFavorites($userId, $carId);
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Veículo adicionado aos favoritos',
                    'action' => 'added'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao adicionar aos favoritos']);
            }
            
        } catch (Exception $e) {
            error_log("Erro ao adicionar favorito: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
        }
    }
    
    /**
     * Remover dos favoritos via AJAX
     */
    public function remove() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        $carId = $_POST['car_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$carId || !$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }
        
        try {
            // Verificar se está nos favoritos
            $isFavorite = $this->favoriteModel->isFavorite($userId, $carId);
            
            if (!$isFavorite) {
                echo json_encode(['success' => false, 'message' => 'Veículo não está nos favoritos']);
                return;
            }
            
            $result = $this->favoriteModel->removeFromFavorites($userId, $carId);
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Veículo removido dos favoritos',
                    'action' => 'removed'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao remover dos favoritos']);
            }
            
        } catch (Exception $e) {
            error_log("Erro ao remover favorito: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
        }
    }
    
    /**
     * Listar favoritos do usuário
     */
    public function index() {
        // Bloquear admins
        if (($_SESSION['user_type'] ?? '') === 'admin') {
            $_SESSION['error'] = 'Administradores não possuem página de favoritos.';
            $this->redirect(BASE_URL . 'dashboard');
        }
        
        $userId = $_SESSION['user_id'];
        $favorites = $this->favoriteModel->getUserFavorites($userId);
        $favoritesCount = $this->favoriteModel->countUserFavorites($userId);
        
        $this->view('favorites/index', [
            'favorites' => $favorites,
            'favoritesCount' => $favoritesCount,
            'title' => 'Meus Favoritos'
        ]);
    }
    
    /**
     * Verificar status de favorito (para AJAX)
     */
    public function status($carId) {
        header('Content-Type: application/json');
        
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId || !$carId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }
        
        try {
            $isFavorite = $this->favoriteModel->isFavorite($userId, $carId);
            echo json_encode([
                'success' => true,
                'is_favorite' => $isFavorite
            ]);
        } catch (Exception $e) {
            error_log("Erro ao verificar status favorito: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
        }
    }
}
?>
