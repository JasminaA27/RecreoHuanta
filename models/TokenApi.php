<?php
require_once 'Database.php';

class TokenApi {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los tokens
    public function getAll() {
        $query = "SELECT t.*, c.dni, c.nombre, c.apellido 
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 ORDER BY t.fecha_reg DESC";
        return $this->db->select($query);
    }

    // Obtener token por ID
    public function getById($id) {
        $query = "SELECT t.*, c.dni, c.nombre, c.apellido, c.telefono, c.correo 
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id = ?";
        return $this->db->selectOne($query, [$id]);
    }

    // Obtener token por valor
    public function getByToken($token) {
        $query = "SELECT t.*, c.dni, c.nombre, c.apellido 
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.token = ?";
        return $this->db->selectOne($query, [$token]);
    }

    // Obtener tokens por cliente
    public function getByClient($clientId) {
        $query = "SELECT t.*, c.dni, c.nombre, c.apellido 
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.id_client_api = ? 
                 ORDER BY t.fecha_reg DESC";
        return $this->db->select($query, [$clientId]);
    }

    // Obtener tokens activos
    public function getActive() {
        $query = "SELECT t.*, c.dni, c.nombre, c.apellido 
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE t.estado = 1 
                 ORDER BY c.nombre, t.fecha_reg DESC";
        return $this->db->select($query);
    }

    // Crear nuevo token
    public function create($data) {
        $query = "INSERT INTO tokens_api (id_client_api, token, estado) 
                 VALUES (?, ?, ?)";
        
        $params = [
            $data['id_client_api'],
            $data['token'],
            $data['estado'] ?? 1
        ];
        
        if ($this->db->execute($query, $params)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Actualizar token
    public function update($id, $data) {
        $query = "UPDATE tokens_api 
                 SET id_client_api = ?, token = ?, estado = ? 
                 WHERE id = ?";
        
        $params = [
            $data['id_client_api'],
            $data['token'],
            $data['estado'] ?? 1,
            $id
        ];
        
        return $this->db->execute($query, $params);
    }

    // Cambiar estado del token
    public function changeStatus($id, $estado) {
        $query = "UPDATE tokens_api SET estado = ? WHERE id = ?";
        return $this->db->execute($query, [$estado, $id]);
    }

    // Eliminar token
    public function delete($id) {
        $query = "DELETE FROM tokens_api WHERE id = ?";
        return $this->db->execute($query, [$id]);
    }

    // Verificar si token existe
    public function tokenExists($token, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM tokens_api WHERE token = ?";
        $params = [$token];
        
        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->selectOne($query, $params);
        return $result['count'] > 0;
    }

   // Generar token único 
  public function generateToken() {
        do {
            $token = bin2hex(random_bytes(32));
        } while ($this->tokenExists($token));
        
        return $token;
    // Crear token final con id del cliente y fecha
    
}
    // Obtener estadísticas de tokens
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_tokens,
                    SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as tokens_activos,
                    SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as tokens_inactivos,
                    COUNT(DISTINCT id_client_api) as clientes_activos
                 FROM tokens_api";
        return $this->db->selectOne($query);
    }

    // Obtener tokens recientes
    public function getRecent($limit = 5) {
        $query = "SELECT t.*, c.nombre, c.apellido 
                 FROM tokens_api t 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 ORDER BY t.fecha_reg DESC 
                 LIMIT ?";
        return $this->db->select($query, [$limit]);
    }

}
?>