<?php
// app/Core/Model.php - Modelo base

class Model {
    protected $db;
    
    public function __construct() {
        global $pdo;
        $this->db = $pdo;
    }
    
    // Buscar todos os registros
    public function findAll($table, $orderBy = 'id DESC') {
        $stmt = $this->db->prepare("SELECT * FROM {$table} ORDER BY {$orderBy}");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Buscar por ID
    public function findById($table, $id) {
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Buscar com condições
    public function findWhere($table, $conditions, $params = []) {
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE {$conditions}");
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Inserir registro
    public function insert($table, $data) {
        $fields = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $stmt = $this->db->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
    
    // Atualizar registro
    public function update($table, $data, $id) {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }
        $fields = implode(', ', $fields);
        
        $data['id'] = $id;
        $stmt = $this->db->prepare("UPDATE {$table} SET {$fields} WHERE id = :id");
        return $stmt->execute($data);
    }
    
    // Deletar registro
    public function delete($table, $id) {
        $stmt = $this->db->prepare("DELETE FROM {$table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Executar query personalizada
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    // Contar registros
    public function count($table, $conditions = '', $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$table}";
        if ($conditions) {
            $sql .= " WHERE {$conditions}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'];
    }
}
?>
