<?php
// app/Models/Favorite.php

require_once APP . '/Core/Model.php';

class Favorite extends Model {
    
    /**
     * Adicionar carro aos favoritos
     */
    public function addToFavorites($userId, $carId) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO favoritos (usuario_id, carro_id) 
                VALUES (?, ?)
            ");
            return $stmt->execute([$userId, $carId]);
        } catch (PDOException $e) {
            // Se já existe (UNIQUE constraint), não é erro
            if ($e->getCode() == 23000) {
                return false; // Já está nos favoritos
            }
            throw $e;
        }
    }
    
    /**
     * Remover carro dos favoritos
     */
    public function removeFromFavorites($userId, $carId) {
        $stmt = $this->db->prepare("
            DELETE FROM favoritos 
            WHERE usuario_id = ? AND carro_id = ?
        ");
        return $stmt->execute([$userId, $carId]);
    }
    
    /**
     * Verificar se carro está nos favoritos
     */
    public function isFavorite($userId, $carId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM favoritos 
            WHERE usuario_id = ? AND carro_id = ?
        ");
        $stmt->execute([$userId, $carId]);
        return $stmt->fetchColumn() > 0;
    }
      /**
     * Buscar favoritos de um usuário (apenas carros ativos)
     */
    public function getUserFavorites($userId) {
        $stmt = $this->db->prepare("
            SELECT c.*, f.created_at as favorited_at
            FROM favoritos f
            INNER JOIN carros c ON f.carro_id = c.id
            WHERE f.usuario_id = ? AND c.ativo = 1
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      /**
     * Contar favoritos de um usuário (apenas carros ativos)
     */
    public function countUserFavorites($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM favoritos f
            INNER JOIN carros c ON f.carro_id = c.id
            WHERE f.usuario_id = ? AND c.ativo = 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Alias para countUserFavorites (compatibilidade)
     */
    public function getUserFavoritesCount($userId) {
        return $this->countUserFavorites($userId);
    }
      /**
     * Buscar carros com informação de favorito para um usuário (apenas carros ativos)
     */
    public function getCarsWithFavoriteStatus($userId = null, $search = null) {
        $sql = "
            SELECT c.*, 
                   " . ($userId ? "CASE WHEN f.id IS NOT NULL THEN 1 ELSE 0 END as is_favorite" : "0 as is_favorite") . "
            FROM carros c
            " . ($userId ? "LEFT JOIN favoritos f ON c.id = f.carro_id AND f.usuario_id = ?" : "") . "
            WHERE c.ativo = 1
        ";
        
        $params = [];
        if ($userId) {
            $params[] = $userId;
        }
        
        if ($search) {
            $sql .= " AND (c.modelo LIKE ? OR c.marca LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
