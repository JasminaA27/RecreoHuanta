<?php
require_once 'Database.php';

class ClienteApi {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los clientes API
    public function getAll() {
        $query = "SELECT * FROM client_api ORDER BY fecha_registro DESC";
        return $this->db->select($query);
    }

    // Obtener cliente API por ID
    public function getById($id) {
        $query = "SELECT * FROM client_api WHERE id = ?";
        return $this->db->selectOne($query, [$id]);
    }

    // Obtener cliente API por DNI
    public function getByDni($dni) {
        $query = "SELECT * FROM client_api WHERE dni = ?";
        return $this->db->selectOne($query, [$dni]);
    }

    // Obtener cliente API por correo
    public function getByCorreo($correo) {
        $query = "SELECT * FROM client_api WHERE correo = ?";
        return $this->db->selectOne($query, [$correo]);
    }

    // Crear nuevo cliente API
    public function create($data) {
        $query = "INSERT INTO client_api (dni, nombre, apellido, telefono, correo, estado) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['dni'],
            $data['nombre'],
            $data['apellido'],
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['estado'] ?? 1
        ];
        
        if ($this->db->execute($query, $params)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Actualizar cliente API
    public function update($id, $data) {
        $query = "UPDATE client_api 
                 SET dni = ?, nombre = ?, apellido = ?, telefono = ?, correo = ?, estado = ? 
                 WHERE id = ?";
        
        $params = [
            $data['dni'],
            $data['nombre'],
            $data['apellido'],
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['estado'] ?? 1,
            $id
        ];
        
        return $this->db->execute($query, $params);
    }

    // Cambiar estado del cliente API
    public function changeStatus($id, $estado) {
        $query = "UPDATE client_api SET estado = ? WHERE id = ?";
        return $this->db->execute($query, [$estado, $id]);
    }

    // Eliminar cliente API
    public function delete($id) {
        $query = "DELETE FROM client_api WHERE id = ?";
        return $this->db->execute($query, [$id]);
    }

    // Obtener estadísticas de clientes API
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_clientes,
                    SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) as clientes_activos,
                    SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) as clientes_inactivos,
                    COUNT(DISTINCT correo) as correos_unicos
                 FROM client_api";
        return $this->db->selectOne($query);
    }

    // Verificar si DNI existe
    public function dniExists($dni, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM client_api WHERE dni = ?";
        $params = [$dni];
        
        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->selectOne($query, $params);
        return $result['count'] > 0;
    }

    // Verificar si correo existe
    public function correoExists($correo, $excludeId = null) {
        if (empty($correo)) return false;
        
        $query = "SELECT COUNT(*) as count FROM client_api WHERE correo = ?";
        $params = [$correo];
        
        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->selectOne($query, $params);
        return $result['count'] > 0;
    }
}
?>