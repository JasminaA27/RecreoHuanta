<?php
require_once 'Database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Login de usuario
    public function login($username, $password) {
        $query = "SELECT id, nombre, username, password_hash, estado 
                 FROM usuarios 
                 WHERE username = ? AND estado = 'activo'";
        
        $user = $this->db->selectOne($query, [$username]);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    // Obtener todos los usuarios
    public function getAll() {
        $query = "SELECT id, nombre, username, estado, fecha_creacion, fecha_actualizacion 
                 FROM usuarios 
                 ORDER BY fecha_creacion DESC";
        return $this->db->select($query);
    }

    // Obtener usuario por ID
    public function getById($id) {
        $query = "SELECT id, nombre, username, estado, fecha_creacion, fecha_actualizacion 
                 FROM usuarios 
                 WHERE id = ?";
        return $this->db->selectOne($query, [$id]);
    }

    // Crear nuevo usuario
    public function create($nombre, $username, $password, $estado = 'activo') {
        // Verificar si el username ya existe
        if ($this->usernameExists($username)) {
            return false;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (nombre, username, password_hash, estado) 
                 VALUES (?, ?, ?, ?)";
        
        if ($this->db->execute($query, [$nombre, $username, $password_hash, $estado])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Actualizar usuario
    public function update($id, $nombre, $username, $estado, $password = null) {
        // Verificar si el username ya existe (excluyendo el usuario actual)
        if ($this->usernameExists($username, $id)) {
            return false;
        }

        if ($password) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE usuarios 
                     SET nombre = ?, username = ?, password_hash = ?, estado = ? 
                     WHERE id = ?";
            return $this->db->execute($query, [$nombre, $username, $password_hash, $estado, $id]);
        } else {
            $query = "UPDATE usuarios 
                     SET nombre = ?, username = ?, estado = ? 
                     WHERE id = ?";
            return $this->db->execute($query, [$nombre, $username, $estado, $id]);
        }
    }

    // Cambiar estado de usuario
    public function changeStatus($id, $estado) {
        $query = "UPDATE usuarios SET estado = ? WHERE id = ?";
        return $this->db->execute($query, [$estado, $id]);
    }

    // Eliminar usuario (soft delete)
    public function delete($id) {
        return $this->changeStatus($id, 'inactivo');
    }

    // Verificar si existe un username
    public function usernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $query = "SELECT COUNT(*) FROM usuarios WHERE username = ? AND id != ?";
            return $this->db->count($query, [$username, $excludeId]) > 0;
        } else {
            $query = "SELECT COUNT(*) FROM usuarios WHERE username = ?";
            return $this->db->count($query, [$username]) > 0;
        }
    }

    // Obtener estadísticas de usuarios
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as activos,
                    SUM(CASE WHEN estado = 'inactivo' THEN 1 ELSE 0 END) as inactivos
                 FROM usuarios";
        return $this->db->selectOne($query);
    }

    // Buscar usuarios
    public function search($term) {
        $searchTerm = "%{$term}%";
        $query = "SELECT id, nombre, username, estado, fecha_creacion 
                 FROM usuarios 
                 WHERE (nombre LIKE ? OR username LIKE ?) 
                 ORDER BY nombre";
        return $this->db->select($query, [$searchTerm, $searchTerm]);
    }
}
?>