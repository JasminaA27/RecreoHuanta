<?php
require_once 'Database.php';

class CountRequest {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Registrar una nueva solicitud
    public function create($data) {
        $query = "INSERT INTO count_request (id_tokens_api, tipo) 
                 VALUES (?, ?)";
        
        $params = [
            $data['id_tokens_api'],
            $data['tipo'] ?? null
        ];
        
        return $this->db->execute($query, $params);
    }

    // Obtener todas las solicitudes
    public function getAll($limit = 100) {
        $query = "SELECT cr.*, t.token, c.dni, c.nombre, c.apellido 
                 FROM count_request cr 
                 LEFT JOIN tokens_api t ON cr.id_tokens_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 ORDER BY cr.fecha DESC 
                 LIMIT ?";
        return $this->db->select($query, [$limit]);
    }

    // Obtener solicitud por ID
    public function getById($id) {
        $query = "SELECT cr.*, t.token, c.dni, c.nombre, c.apellido, c.telefono, c.correo 
                 FROM count_request cr 
                 LEFT JOIN tokens_api t ON cr.id_tokens_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE cr.id = ?";
        return $this->db->selectOne($query, [$id]);
    }

    // Obtener estadísticas de solicitudes
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_solicitudes,
                    COUNT(DISTINCT id_tokens_api) as tokens_utilizados,
                    COUNT(DISTINCT DATE(fecha)) as dias_activos,
                    COUNT(DISTINCT tipo) as tipos_solicitud,
                    DATE(fecha) as fecha_dia,
                    COUNT(*) as solicitudes_hoy
                 FROM count_request 
                 WHERE DATE(fecha) = CURDATE()";
        return $this->db->selectOne($query);
    }

    // Obtener solicitudes por token
    public function getByToken($tokenId, $limit = 50) {
        $query = "SELECT cr.*, t.token, c.dni, c.nombre, c.apellido 
                 FROM count_request cr 
                 LEFT JOIN tokens_api t ON cr.id_tokens_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE cr.id_tokens_api = ? 
                 ORDER BY cr.fecha DESC 
                 LIMIT ?";
        return $this->db->select($query, [$tokenId, $limit]);
    }

    // Obtener solicitudes por rango de fechas
    public function getByDateRange($startDate, $endDate) {
        $query = "SELECT cr.*, t.token, c.dni, c.nombre, c.apellido 
                 FROM count_request cr 
                 LEFT JOIN tokens_api t ON cr.id_tokens_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 WHERE DATE(cr.fecha) BETWEEN ? AND ? 
                 ORDER BY cr.fecha DESC";
        return $this->db->select($query, [$startDate, $endDate]);
    }

    // Obtener conteo de solicitudes por día
    public function getDailyCount($days = 7) {
        $query = "SELECT 
                    DATE(fecha) as fecha,
                    COUNT(*) as total_solicitudes,
                    COUNT(DISTINCT id_tokens_api) as tokens_activos
                 FROM count_request 
                 WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                 GROUP BY DATE(fecha)
                 ORDER BY fecha DESC";
        return $this->db->select($query, [$days]);
    }

    // Obtener top tokens más utilizados
    public function getTopTokens($limit = 10) {
        $query = "SELECT 
                    t.token,
                    c.nombre,
                    c.apellido,
                    COUNT(cr.id) as total_solicitudes,
                    MAX(cr.fecha) as ultima_solicitud
                 FROM count_request cr 
                 LEFT JOIN tokens_api t ON cr.id_tokens_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 GROUP BY cr.id_tokens_api 
                 ORDER BY total_solicitudes DESC 
                 LIMIT ?";
        return $this->db->select($query, [$limit]);
    }

    // Obtener tipos de solicitud
    public function getRequestTypes() {
        $query = "SELECT 
                    tipo,
                    COUNT(*) as total,
                    COUNT(DISTINCT id_tokens_api) as tokens_unicos
                 FROM count_request 
                 WHERE tipo IS NOT NULL 
                 GROUP BY tipo 
                 ORDER BY total DESC";
        return $this->db->select($query);
    }

    // Eliminar solicitud
    public function delete($id) {
        $query = "DELETE FROM count_request WHERE id = ?";
        return $this->db->execute($query, [$id]);
    }

    // Eliminar solicitudes antiguas
    public function deleteOldRequests($days = 30) {
        $query = "DELETE FROM count_request WHERE fecha < DATE_SUB(NOW(), INTERVAL ? DAY)";
        return $this->db->execute($query, [$days]);
    }

    // Obtener solicitudes recientes
    public function getRecent($limit = 10) {
        $query = "SELECT cr.*, t.token, c.nombre, c.apellido 
                 FROM count_request cr 
                 LEFT JOIN tokens_api t ON cr.id_tokens_api = t.id 
                 LEFT JOIN client_api c ON t.id_client_api = c.id 
                 ORDER BY cr.fecha DESC 
                 LIMIT ?";
        return $this->db->select($query, [$limit]);
    }
}
?>