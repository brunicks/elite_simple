<?php
// app/Models/RecentlyViewed.php - Modelo para carros visitados recentemente

require_once APP . '/Core/Model.php';

class RecentlyViewed extends Model {
    
    protected $table = 'recently_viewed';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Adicionar carro aos visitados recentemente
    public function addView($userId, $carId) {
        try {
            // Primeiro, remove se já existir
            $this->removeView($userId, $carId);
            
            // Adiciona nova visualização
            $sql = "INSERT INTO {$this->table} (user_id, car_id, viewed_at) VALUES (?, ?, NOW())";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$userId, $carId]);
            
            // Remove visualizações antigas (manter apenas as 10 mais recentes)
            $this->cleanOldViews($userId);
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao adicionar visualização: " . $e->getMessage());
            return false;
        }
    }
    
    // Remover visualização específica
    public function removeView($userId, $carId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND car_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId, $carId]);
        } catch (PDOException $e) {
            error_log("Erro ao remover visualização: " . $e->getMessage());
            return false;
        }
    }
    
    // Buscar carros visitados recentemente com detalhes
    public function getRecentlyViewedCars($userId, $limit = 10) {
        try {
            $sql = "SELECT rv.*, c.marca, c.modelo, c.ano, c.preco, c.km, c.imagem, c.ativo
                    FROM {$this->table} rv 
                    JOIN carros c ON rv.car_id = c.id 
                    WHERE rv.user_id = ? AND c.ativo = 1
                    ORDER BY rv.viewed_at DESC 
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar carros visitados: " . $e->getMessage());
            return [];
        }
    }
    
    // Limpar visualizações antigas (manter apenas as N mais recentes)
    private function cleanOldViews($userId, $keepCount = 10) {
        try {
            $sql = "DELETE FROM {$this->table} 
                    WHERE user_id = ? 
                    AND id NOT IN (
                        SELECT id FROM (
                            SELECT id FROM {$this->table} 
                            WHERE user_id = ? 
                            ORDER BY viewed_at DESC 
                            LIMIT ?
                        ) AS recent
                    )";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId, $userId, $keepCount]);
        } catch (PDOException $e) {
            error_log("Erro ao limpar visualizações antigas: " . $e->getMessage());
            return false;
        }
    }
    
    // Contar carros visitados
    public function getRecentlyViewedCount($userId) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} rv 
                    JOIN carros c ON rv.car_id = c.id 
                    WHERE rv.user_id = ? AND c.ativo = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar visualizações: " . $e->getMessage());
            return 0;
        }
    }
}
?>
