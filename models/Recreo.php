<?php
require_once 'Database.php';

class Recreo {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todos los recreos
    public function getAll() {
        $query = "SELECT * FROM recreos ORDER BY fecha_creacion DESC";
        return $this->db->select($query);
    }

    // Obtener recreos activos
    public function getActive() {
        $query = "SELECT id, nombre, direccion, referencia, telefono, ubicacion, servicio, precio, horario, url_maps 
                 FROM recreos 
                 WHERE estado = 'activo' 
                 ORDER BY nombre";
        return $this->db->select($query);
    }

    // Obtener recreo por ID
    public function getById($id) {
        $query = "SELECT * FROM recreos WHERE id = ?";
        return $this->db->selectOne($query, [$id]);
    }
    // ========== MÉTODOS DE BÚSQUEDA PARA LA API ==========
    
    // BUSCAR POR NOMBRE
    public function buscarPorNombre($termino) {
        $searchTerm = "%{$termino}%";
        $query = "SELECT id, nombre, direccion, telefono, ubicacion, servicio, precio, horario, url_maps 
                 FROM recreos 
                 WHERE nombre LIKE ? AND estado = 'activo' 
                 ORDER BY nombre";
        return $this->db->select($query, [$searchTerm]);
    }

    // BUSCAR POR SERVICIO
    public function buscarPorServicio($termino) {
        $searchTerm = "%{$termino}%";
        $query = "SELECT id, nombre, direccion, telefono, ubicacion, servicio, precio, horario, url_maps 
                 FROM recreos 
                 WHERE servicio LIKE ? AND estado = 'activo' 
                 ORDER BY nombre";
        return $this->db->select($query, [$searchTerm]);
    }

    // BUSCAR POR DIRECCIÓN
    public function buscarPorDireccion($termino) {
        $searchTerm = "%{$termino}%";
        $query = "SELECT id, nombre, direccion, telefono, ubicacion, servicio, precio, horario, url_maps 
                 FROM recreos 
                 WHERE direccion LIKE ? AND estado = 'activo' 
                 ORDER BY nombre";
        return $this->db->select($query, [$searchTerm]);
    }

    // BUSCAR EN TODOS LOS CAMPOS
    public function buscarEnTodo($termino) {
        $searchTerm = "%{$termino}%";
        $query = "SELECT id, nombre, direccion, telefono, ubicacion, servicio, precio, horario, url_maps 
                 FROM recreos 
                 WHERE (nombre LIKE ? OR servicio LIKE ? OR direccion LIKE ? OR ubicacion LIKE ?) 
                 AND estado = 'activo' 
                 ORDER BY nombre";
        return $this->db->select($query, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }

    // Crear nuevo recreo
    public function create($data) {
        $query = "INSERT INTO recreos (nombre, direccion, referencia, telefono, ubicacion, servicio, precio, horario, url_maps, estado) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['nombre'],
            $data['direccion'],
            $data['referencia'] ?? null,
            $data['telefono'] ?? null,
            $data['ubicacion'],
            $data['servicio'] ?? null,
            $data['precio'] ?? 0,
            $data['horario'] ?? null,
            $data['url_maps'] ?? null,
            $data['estado'] ?? 'activo'
        ];
        
        if ($this->db->execute($query, $params)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Actualizar recreo
    public function update($id, $data) {
        $query = "UPDATE recreos 
                 SET nombre = ?, direccion = ?, referencia = ?, telefono = ?, ubicacion = ?, 
                     servicio = ?, precio = ?, horario = ?, url_maps = ?, estado = ? 
                 WHERE id = ?";
        
        $params = [
            $data['nombre'],
            $data['direccion'],
            $data['referencia'] ?? null,
            $data['telefono'] ?? null,
            $data['ubicacion'],
            $data['servicio'] ?? null,
            $data['precio'] ?? 0,
            $data['horario'] ?? null,
            $data['url_maps'] ?? null,
            $data['estado'] ?? 'activo',
            $id
        ];
        
        return $this->db->execute($query, $params);
    }

    // Cambiar estado del recreo
    public function changeStatus($id, $estado) {
        $query = "UPDATE recreos SET estado = ? WHERE id = ?";
        return $this->db->execute($query, [$estado, $id]);
    }

    // Eliminar recreo (soft delete)
    public function delete($id) {
        return $this->changeStatus($id, 'inactivo');
    }

    // Buscar recreos por término
    public function search($term) {
        $searchTerm = "%{$term}%";
        $query = "SELECT id, nombre, direccion, telefono, ubicacion, servicio, precio, horario, url_maps 
                 FROM recreos 
                 WHERE (nombre LIKE ? OR servicio LIKE ? OR ubicacion LIKE ?) 
                 AND estado = 'activo' 
                 ORDER BY nombre";
        return $this->db->select($query, [$searchTerm, $searchTerm, $searchTerm]);
    }

    // Obtener recreos por ubicación
    public function getByUbicacion($ubicacion) {
        $query = "SELECT id, nombre, direccion, telefono, servicio, precio, horario, url_maps 
                 FROM recreos 
                 WHERE ubicacion = ? AND estado = 'activo' 
                 ORDER BY nombre";
        return $this->db->select($query, [$ubicacion]);
    }

    // Obtener recreos por rango de precios
    public function getByPriceRange($min, $max) {
        $query = "SELECT id, nombre, direccion, precio, servicio, horario, url_maps 
                 FROM recreos 
                 WHERE precio BETWEEN ? AND ? AND estado = 'activo' 
                 ORDER BY precio";
        return $this->db->select($query, [$min, $max]);
    }

    // Obtener estadísticas de recreos
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_recreos,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as recreos_activos,
                    SUM(CASE WHEN estado = 'inactivo' THEN 1 ELSE 0 END) as recreos_inactivos,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as recreos_pendientes,
                    COUNT(DISTINCT ubicacion) as ubicaciones_unicas,
                    ROUND(AVG(precio), 2) as precio_promedio,
                    MIN(precio) as precio_minimo,
                    MAX(precio) as precio_maximo
                 FROM recreos";
        return $this->db->selectOne($query);
    }

    // Estadísticas por ubicación
    public function getStatsByUbicacion() {
        $query = "SELECT 
                    ubicacion,
                    COUNT(*) as total_recreos,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as activos
                 FROM recreos 
                 GROUP BY ubicacion 
                 ORDER BY total_recreos DESC";
        return $this->db->select($query);
    }

    // Recreos recientes
    public function getRecent($limit = 5) {
        $query = "SELECT id, nombre, ubicacion, precio, estado, fecha_creacion 
                 FROM recreos 
                 ORDER BY fecha_creacion DESC 
                 LIMIT ?";
        return $this->db->select($query, [$limit]);
    }

    // Obtener ubicaciones únicas
    public function getUbicaciones() {
        $query = "SELECT DISTINCT ubicacion FROM recreos WHERE estado != 'inactivo' ORDER BY ubicacion";
        $result = $this->db->select($query);
        return array_column($result, 'ubicacion');
    }
}
?>