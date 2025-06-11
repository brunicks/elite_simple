<?php
// app/Models/FinancingSimulation.php - Modelo para simulações de financiamento

require_once APP . '/Core/Model.php';

class FinancingSimulation extends Model {
    
    protected $table = 'financing_simulations';
    
    public function __construct() {
        parent::__construct();
    }
      // Criar nova simulação de financiamento
    public function createSimulation($data) {
        try {
            $sql = "INSERT INTO {$this->table} (user_id, car_id, car_name, car_price, down_payment, interest_rate, term_months, monthly_payment, total_amount, total_interest, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['user_id'],
                $data['car_id'],
                $data['car_name'],
                $data['car_price'],
                $data['down_payment'],
                $data['interest_rate'],
                $data['term_months'],
                $data['monthly_payment'],
                $data['total_amount'],
                $data['total_interest']
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao criar simulação: " . $e->getMessage());
            return false;
        }
    }
    
    // Buscar simulações do usuário
    public function getUserSimulations($userId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar simulações: " . $e->getMessage());
            return [];
        }
    }
    
    // Buscar simulação por ID
    public function getSimulationById($id, $userId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id, $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar simulação: " . $e->getMessage());
            return false;
        }
    }
      // Atualizar simulação
    public function updateSimulation($id, $data, $userId) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET car_id = ?, car_name = ?, car_price = ?, down_payment = ?, 
                        interest_rate = ?, term_months = ?, monthly_payment = ?, total_amount = ?, 
                        total_interest = ?, updated_at = NOW() 
                    WHERE id = ? AND user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['car_id'],
                $data['car_name'],
                $data['car_price'],
                $data['down_payment'],
                $data['interest_rate'],
                $data['term_months'],
                $data['monthly_payment'],
                $data['total_amount'],
                $data['total_interest'],
                $id,
                $userId
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar simulação: " . $e->getMessage());
            return false;
        }
    }
    
    // Deletar simulação
    public function deleteSimulation($id, $userId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id, $userId]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar simulação: " . $e->getMessage());
            return false;
        }
    }
    
    // Contar simulações do usuário
    public function getUserSimulationsCount($userId) {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar simulações: " . $e->getMessage());
            return 0;
        }
    }
    
    // Calcular financiamento
    public static function calculateFinancing($carPrice, $downPayment, $interestRate, $termMonths) {
        $loanAmount = $carPrice - $downPayment;
        
        if ($loanAmount <= 0) {
            return [
                'monthly_payment' => 0,
                'total_amount' => $downPayment,
                'total_interest' => 0
            ];
        }
        
        $monthlyRate = ($interestRate / 100) / 12;
        
        if ($monthlyRate == 0) {
            $monthlyPayment = $loanAmount / $termMonths;
        } else {
            $monthlyPayment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $termMonths)) / (pow(1 + $monthlyRate, $termMonths) - 1);
        }
        
        $totalAmount = ($monthlyPayment * $termMonths) + $downPayment;
        $totalInterest = $totalAmount - $carPrice;
        
        return [
            'monthly_payment' => round($monthlyPayment, 2),
            'total_amount' => round($totalAmount, 2),
            'total_interest' => round($totalInterest, 2)
        ];
    }
}
?>
