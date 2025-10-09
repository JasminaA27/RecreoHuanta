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

   // Generar token único con ID del cliente y fecha
public function generateToken($clientId = null) {
    // Generar parte aleatoria del token (32 caracteres hexadecimales)
    $randomPart = bin2hex(random_bytes(16));
    
    // Obtener fecha actual en formato YYYYMMDD
    $fecha = date('Ymd');
    
    // Si se proporciona un clientId, crear token con formato completo
    if ($clientId) {
        // Crear token final con formato: random_fecha_clientId
        $token = $randomPart . '_' . $fecha . '_' . $clientId;
    } else {
        // Si no hay clientId, usar secuencia por defecto
        $token = $randomPart . '_' . $fecha . '_1';
    }
    
    // Verificar que el token no exista
    $attempts = 0;
    while ($this->tokenExists($token) && $attempts < 10) {
        $randomPart = bin2hex(random_bytes(16));
        if ($clientId) {
            $token = $randomPart . '_' . $fecha . '_' . $clientId;
        } else {
            $token = $randomPart . '_' . $fecha . '_1';
        }
        $attempts++;
    }
    
    return $token;
}

// Función específica para generar token como tu ejemplo
public function generateTokenLikeExample($clientId = 1) {
    // Parte fija como en tu ejemplo (opcional)
    $fixedPart = "4f8e2a9c1b6d3f7e8a2b4c6d8e0f2a4";
    
    // Fecha actual en formato YYYYMMDD
    $fecha = date('Ymd');
    
    // Crear token con formato exacto
    $token = $fixedPart . '_' . $fecha . '_' . $clientId;
    
    return $token;
}

    // Generar token para un cliente específico
    public function generateTokenForClient($clientId) {
        return $this->generateToken($clientId);
    }

    // Regenerar token existente
    public function regenerate($id) {
        // Obtener información del token actual
        $tokenData = $this->getById($id);
        if (!$tokenData) {
            return false;
        }
        
        $clientId = $tokenData['id_client_api'];
        
        // Generar nuevo token con el mismo formato
        $newToken = $this->generateToken($clientId);
        
        // Actualizar el token en la base de datos
        $query = "UPDATE tokens_api SET token = ?, fecha_reg = ? WHERE id = ?";
        $params = [$newToken, date('Y-m-d H:i:s'), $id];
        
        return $this->db->execute($query, $params) ? $newToken : false;
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

    // Parsear información del token
    public function parseToken($token) {
        $parts = explode('_', $token);
        
        if (count($parts) === 3) {
            return [
                'random_part' => $parts[0],
                'fecha' => $parts[1],
                'client_id' => $parts[2],
                'formato' => 'completo'
            ];
        } else {
            return [
                'random_part' => $token,
                'fecha' => null,
                'client_id' => null,
                'formato' => 'simple'
            ];
        }
    }

 // Validar formato del token
public function validateTokenFormat($token) {
    // El token debe tener aproximadamente 44 caracteres (32 + 1 + 8 + 1 + n)
    if (strlen($token) < 40) {
        return false;
    }
    
    // Debe tener exactamente 2 guiones bajos
    $parts = explode('_', $token);
    if (count($parts) !== 3) {
        return false;
    }
    
    // Verificar que cada parte tenga el formato correcto
    if (!ctype_xdigit($parts[0]) || strlen($parts[0]) !== 32) {
        return false; // Random part debe ser 32 caracteres hex
    }
    
    if (!is_numeric($parts[1]) || strlen($parts[1]) !== 8) {
        return false; // Fecha debe ser 8 dígitos (YYYYMMDD)
    }
    
    if (!is_numeric($parts[2])) {
        return false; // Client ID debe ser numérico
    }
    
    return true;
}
}
?>