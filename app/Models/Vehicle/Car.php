<?php
// app/Models/Car.php - Modelo do carro

require_once APP . '/Core/Model.php';

class Car extends Model {    // Buscar todos os carros ativos (soft delete)
    public function getAllCars() {
        $sql = "SELECT * FROM carros WHERE ativo = 1 ORDER BY created_at DESC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar todos os carros ativos (alias for consistency) - Método faltante
    public function getAllActiveCars() {
        return $this->getAllCars(); // Reusa a lógica existente
    }
      // Buscar carro por ID (apenas ativos)
    public function getCarById($id) {
        $sql = "SELECT * FROM carros WHERE id = ? AND ativo = 1";
        $stmt = $this->query($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Buscar carro por ID (incluindo inativos - para admin)
    public function getCarByIdWithInactive($id) {
        return $this->findById('carros', $id);
    }
      // Buscar carros com filtro (apenas ativos)
    public function searchCars($search) {
        $sql = "SELECT * FROM carros WHERE (modelo LIKE ? OR marca LIKE ?) AND ativo = 1 ORDER BY created_at DESC";
        $stmt = $this->query($sql, ["%$search%", "%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar os 5 carros mais caros para o carousel da homepage
    public function getTopExpensiveCars($limit = 5) {
        $sql = "SELECT * FROM carros WHERE ativo = 1 ORDER BY preco DESC LIMIT ?";
        $stmt = $this->query($sql, [$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Criar novo carro (sempre ativo por padrão)
    public function createCar($data) {
        $data['ativo'] = 1; // Garantir que novo carro seja criado como ativo
        return $this->insert('carros', $data);
    }
    
    // Atualizar carro
    public function updateCar($id, $data) {
        return $this->update('carros', $data, $id);
    }
    
    // Soft Delete - Marcar carro como inativo (preserva dados)
    public function deleteCar($id) {
        $updateData = [
            'ativo' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->update('carros', $updateData, $id);
    }
    
    // Hard Delete - Deletar permanentemente (apenas para admin em casos especiais)
    public function hardDeleteCar($id) {
        // Buscar carro para pegar imagem
        $car = $this->getCarByIdWithInactive($id);
        
        // Deletar do banco
        $result = $this->delete('carros', $id);
          // Deletar imagem se existir
        if ($result && $car && $car['imagem']) {
            $imagePath = PUBLIC_PATH . '/uploads/cars/' . $car['imagem'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        return $result;
    }
    
    // Reativar carro (desfazer soft delete)
    public function reactivateCar($id) {
        $updateData = [
            'ativo' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->update('carros', $updateData, $id);
    }
      // Contar total de carros ativos
    public function getTotalCars() {
        $sql = "SELECT COUNT(*) FROM carros WHERE ativo = 1";
        $stmt = $this->query($sql);
        return $stmt->fetchColumn();
    }
    
    // Contar total de carros (incluindo inativos - para admin)
    public function getTotalCarsWithInactive() {
        return $this->count('carros');
    }    // Buscar todos os carros incluindo inativos (para admin)
    public function getAllCarsWithInactive() {
        $sql = "SELECT * FROM carros ORDER BY ativo DESC, created_at DESC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar carros com filtro incluindo inativos (para admin)
    public function searchCarsWithInactive($search) {
        $sql = "SELECT * FROM carros WHERE (modelo LIKE ? OR marca LIKE ?) ORDER BY ativo DESC, created_at DESC";
        $stmt = $this->query($sql, ["%$search%", "%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
      // Buscar carros por marca (apenas ativos)
    public function getCarsByBrand($marca) {
        $sql = "SELECT * FROM carros WHERE marca = ? AND ativo = 1 ORDER BY created_at DESC";
        $stmt = $this->query($sql, [$marca]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar carros por faixa de preço (apenas ativos)
    public function getCarsByPriceRange($minPrice, $maxPrice) {
        $sql = "SELECT * FROM carros WHERE preco BETWEEN ? AND ? AND ativo = 1 ORDER BY preco ASC";
        $stmt = $this->query($sql, [$minPrice, $maxPrice]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Buscar carros por ano (apenas ativos)
    public function getCarsByYear($year) {
        $sql = "SELECT * FROM carros WHERE ano = ? AND ativo = 1 ORDER BY created_at DESC";
        $stmt = $this->query($sql, [$year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Obter estatísticas (apenas carros ativos)
    public function getStats() {
        $stats = [];
        
        // Total de carros ativos
        $stats['total'] = $this->getTotalCars();
          // Preço médio (apenas ativos)
        $stmt = $this->query("SELECT AVG(preco) as avg_price FROM carros WHERE ativo = 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['avg_price'] = $result['avg_price'] ?? 0;
        
        // Carro mais caro (apenas ativos)
        $stmt = $this->query("SELECT MAX(preco) as max_price FROM carros WHERE ativo = 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['max_price'] = $result['max_price'] ?? 0;
        
        // Carro mais barato (apenas ativos)
        $stmt = $this->query("SELECT MIN(preco) as min_price FROM carros WHERE ativo = 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['min_price'] = $result['min_price'] ?? 0;
          // Marcas mais populares (apenas ativos)
        $stmt = $this->query("SELECT marca, COUNT(*) as count FROM carros WHERE ativo = 1 GROUP BY marca ORDER BY count DESC LIMIT 5");
        $stats['popular_brands'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    // Buscar carros com paginação (apenas ativos)
    public function getCarsPaginated($page = 1, $perPage = 12, $search = '') {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = "WHERE ativo = 1";
        $params = [];
        
        if (!empty($search)) {
            $whereClause .= " AND (modelo LIKE ? OR marca LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
          $sql = "SELECT * FROM carros {$whereClause} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Contar carros para paginação (apenas ativos)
    public function getCarsPaginatedCount($search = '') {
        $whereClause = "WHERE ativo = 1";
        $params = [];
        
        if (!empty($search)) {
            $whereClause .= " AND (modelo LIKE ? OR marca LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $sql = "SELECT COUNT(*) FROM carros {$whereClause}";
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }
      // Buscar carros com paginação avançada (com filtros e ordenação)
    public function getCarsPaginatedAdvanced($page = 1, $perPage = 12, $filters = []) {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = "WHERE ativo = 1";
        $params = [];
        
        // Filtro de busca por texto
        if (!empty($filters['search'])) {
            $whereClause .= " AND (modelo LIKE ? OR marca LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        
        // Filtro por marca
        if (!empty($filters['marca'])) {
            $whereClause .= " AND marca = ?";
            $params[] = $filters['marca'];
        }
        
        // Filtro por faixa de ano
        if (!empty($filters['ano_min'])) {
            $whereClause .= " AND ano >= ?";
            $params[] = (int)$filters['ano_min'];
        }
        if (!empty($filters['ano_max'])) {
            $whereClause .= " AND ano <= ?";
            $params[] = (int)$filters['ano_max'];
        }
        
        // Filtro por faixa de preço
        if (!empty($filters['preco_min'])) {
            $whereClause .= " AND preco >= ?";
            $params[] = (float)$filters['preco_min'];
        }
        if (!empty($filters['preco_max'])) {
            $whereClause .= " AND preco <= ?";
            $params[] = (float)$filters['preco_max'];
        }
          // Filtro por quilometragem máxima
        if (!empty($filters['km_max'])) {
            $whereClause .= " AND km <= ?";
            $params[] = (int)$filters['km_max'];
        }
        
        // NOVOS FILTROS TÉCNICOS
        // Filtro por combustível
        if (!empty($filters['combustivel'])) {
            $whereClause .= " AND combustivel = ?";
            $params[] = $filters['combustivel'];
        }
        
        // Filtro por transmissão
        if (!empty($filters['transmissao'])) {
            $whereClause .= " AND transmissao = ?";
            $params[] = $filters['transmissao'];
        }
        
        // Filtro por cor
        if (!empty($filters['cor'])) {
            $whereClause .= " AND cor = ?";
            $params[] = $filters['cor'];
        }
        
        // Filtro por número de portas
        if (!empty($filters['portas'])) {
            $whereClause .= " AND portas = ?";
            $params[] = (int)$filters['portas'];
        }
        
        // Filtro por potência (CV)
        if (!empty($filters['cv_min'])) {
            $whereClause .= " AND cv >= ?";
            $params[] = (int)$filters['cv_min'];
        }
        if (!empty($filters['cv_max'])) {
            $whereClause .= " AND cv <= ?";
            $params[] = (int)$filters['cv_max'];
        }
        
        // Filtro por consumo médio
        if (!empty($filters['consumo_min'])) {
            $whereClause .= " AND consumo_medio >= ?";
            $params[] = (float)$filters['consumo_min'];
        }
        if (!empty($filters['consumo_max'])) {
            $whereClause .= " AND consumo_medio <= ?";
            $params[] = (float)$filters['consumo_max'];
        }
        
        // Ordenação
        $orderBy = "ORDER BY created_at DESC"; // Padrão
        
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'preco_asc':
                    $orderBy = "ORDER BY preco ASC";
                    break;
                case 'preco_desc':
                    $orderBy = "ORDER BY preco DESC";
                    break;
                case 'ano_asc':
                    $orderBy = "ORDER BY ano ASC";
                    break;
                case 'ano_desc':
                    $orderBy = "ORDER BY ano DESC";
                    break;
                case 'km_asc':
                    $orderBy = "ORDER BY km ASC";
                    break;
                case 'km_desc':
                    $orderBy = "ORDER BY km DESC";
                    break;
                case 'marca_asc':
                    $orderBy = "ORDER BY marca ASC, modelo ASC";
                    break;
                case 'modelo_asc':
                    $orderBy = "ORDER BY modelo ASC";
                    break;
                case 'recente':
                    $orderBy = "ORDER BY created_at DESC";
                    break;
                case 'antigo':
                    $orderBy = "ORDER BY created_at ASC";
                    break;
                default:
                    $orderBy = "ORDER BY created_at DESC";
                    break;
            }
        }
          $sql = "SELECT * FROM carros {$whereClause} {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Contar carros para paginação avançada (com filtros)
    public function getCarsPaginatedAdvancedCount($filters = []) {
        $whereClause = "WHERE ativo = 1";
        $params = [];
        
        // Aplicar os mesmos filtros da consulta principal
        if (!empty($filters['search'])) {
            $whereClause .= " AND (modelo LIKE ? OR marca LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        
        if (!empty($filters['marca'])) {
            $whereClause .= " AND marca = ?";
            $params[] = $filters['marca'];
        }
        
        if (!empty($filters['ano_min'])) {
            $whereClause .= " AND ano >= ?";
            $params[] = (int)$filters['ano_min'];
        }
        if (!empty($filters['ano_max'])) {
            $whereClause .= " AND ano <= ?";
            $params[] = (int)$filters['ano_max'];
        }
        
        if (!empty($filters['preco_min'])) {
            $whereClause .= " AND preco >= ?";
            $params[] = (float)$filters['preco_min'];
        }
        if (!empty($filters['preco_max'])) {
            $whereClause .= " AND preco <= ?";
            $params[] = (float)$filters['preco_max'];
        }
          if (!empty($filters['km_max'])) {
            $whereClause .= " AND km <= ?";
            $params[] = (int)$filters['km_max'];
        }
        
        // NOVOS FILTROS TÉCNICOS (mesmos da paginação)
        if (!empty($filters['combustivel'])) {
            $whereClause .= " AND combustivel = ?";
            $params[] = $filters['combustivel'];
        }
        
        if (!empty($filters['transmissao'])) {
            $whereClause .= " AND transmissao = ?";
            $params[] = $filters['transmissao'];
        }
        
        if (!empty($filters['cor'])) {
            $whereClause .= " AND cor = ?";
            $params[] = $filters['cor'];
        }
        
        if (!empty($filters['portas'])) {
            $whereClause .= " AND portas = ?";
            $params[] = (int)$filters['portas'];
        }
        
        if (!empty($filters['cv_min'])) {
            $whereClause .= " AND cv >= ?";
            $params[] = (int)$filters['cv_min'];
        }
        if (!empty($filters['cv_max'])) {
            $whereClause .= " AND cv <= ?";
            $params[] = (int)$filters['cv_max'];
        }
        
        if (!empty($filters['consumo_min'])) {
            $whereClause .= " AND consumo_medio >= ?";
            $params[] = (float)$filters['consumo_min'];
        }
        if (!empty($filters['consumo_max'])) {
            $whereClause .= " AND consumo_medio <= ?";
            $params[] = (float)$filters['consumo_max'];
        }
        
        $sql = "SELECT COUNT(*) FROM carros {$whereClause}";
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }
    
    // Obter todas as marcas disponíveis (apenas carros ativos)
    public function getAllBrands() {
        $sql = "SELECT DISTINCT marca FROM carros WHERE ativo = 1 ORDER BY marca ASC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
      // Obter faixas de preços para filtros
    public function getPriceRanges() {
        $sql = "SELECT MIN(preco) as min_price, MAX(preco) as max_price FROM carros WHERE ativo = 1";
        $stmt = $this->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obter faixas de anos para filtros
    public function getYearRanges() {
        $sql = "SELECT MIN(ano) as min_year, MAX(ano) as max_year FROM carros WHERE ativo = 1";
        $stmt = $this->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // ================================
    // NOVOS MÉTODOS PARA CAMPOS TÉCNICOS
    // ================================
    
    // Obter todos os tipos de combustível disponíveis
    public function getAllFuelTypes() {
        $sql = "SELECT DISTINCT combustivel FROM carros WHERE ativo = 1 AND combustivel IS NOT NULL ORDER BY combustivel ASC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Obter todos os tipos de transmissão disponíveis
    public function getAllTransmissionTypes() {
        $sql = "SELECT DISTINCT transmissao FROM carros WHERE ativo = 1 AND transmissao IS NOT NULL ORDER BY transmissao ASC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // Obter todas as cores disponíveis
    public function getAllColors() {
        $sql = "SELECT DISTINCT cor FROM carros WHERE ativo = 1 AND cor IS NOT NULL ORDER BY cor ASC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
      // Obter faixas de potência (CV)
    public function getPowerRanges() {
        $sql = "SELECT MIN(cv) as min_cv, MAX(cv) as max_cv FROM carros WHERE ativo = 1 AND cv IS NOT NULL";
        $stmt = $this->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Obter faixas de consumo
    public function getConsumptionRanges() {
        $sql = "SELECT MIN(consumo_medio) as min_consumption, MAX(consumo_medio) as max_consumption FROM carros WHERE ativo = 1 AND consumo_medio IS NOT NULL";
        $stmt = $this->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Buscar carros por especificações técnicas
    public function getCarsBySpecs($specs = []) {
        $whereClause = "WHERE ativo = 1";
        $params = [];
        
        if (!empty($specs['combustivel'])) {
            $whereClause .= " AND combustivel = ?";
            $params[] = $specs['combustivel'];
        }
        
        if (!empty($specs['transmissao'])) {
            $whereClause .= " AND transmissao = ?";
            $params[] = $specs['transmissao'];
        }
        
        if (!empty($specs['cor'])) {
            $whereClause .= " AND cor = ?";
            $params[] = $specs['cor'];
        }
        
        if (!empty($specs['portas'])) {
            $whereClause .= " AND portas = ?";
            $params[] = (int)$specs['portas'];
        }
        
        if (!empty($specs['cv_min'])) {
            $whereClause .= " AND cv >= ?";
            $params[] = (int)$specs['cv_min'];
        }
        
        if (!empty($specs['cv_max'])) {
            $whereClause .= " AND cv <= ?";
            $params[] = (int)$specs['cv_max'];
        }
        
        if (!empty($specs['consumo_min'])) {
            $whereClause .= " AND consumo_medio >= ?";
            $params[] = (float)$specs['consumo_min'];
        }
        
        if (!empty($specs['consumo_max'])) {
            $whereClause .= " AND consumo_medio <= ?";
            $params[] = (float)$specs['consumo_max'];
        }
          $sql = "SELECT * FROM carros {$whereClause} ORDER BY created_at DESC";
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>