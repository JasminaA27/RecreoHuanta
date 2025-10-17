<?php
require_once __DIR__ . '/../models/Recreo.php';
require_once __DIR__ . '/../models/TokenApi.php';

class ApiPublicController {
    private $recreoModel;
    private $tokenApiModel;

    public function __construct() {
        $this->recreoModel = new Recreo();
        $this->tokenApiModel = new TokenApi();
    }

    // VISTA PÚBLICA DE BÚSQUEDA
    public function index() {
        include __DIR__ . '/../views/api_public/index.php';
    }

    // LISTAR RECREOS (JSON)
    public function listarRecreos() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValido = $this->validarToken();
        if (!$tokenValido) return;
        
        try {
            $recreos = $this->recreoModel->getActive();
            
            echo json_encode([
                'success' => true,
                'message' => 'Lista de recreos obtenida exitosamente',
                'data' => $recreos,
                'total' => count($recreos)
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener recreos: ' . $e->getMessage()
            ]);
        }
    }

    // BUSCAR RECREOS (JSON)
    public function buscarRecreos() {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValido = $this->validarToken();
        if (!$tokenValido) return;
        
        try {
            $termino = $_GET['q'] ?? '';
            $tipo = $_GET['tipo'] ?? 'todo';
            
            if (empty($termino)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Parámetro "q" requerido para la búsqueda'
                ]);
                return;
            }
            
            $resultados = [];
            switch($tipo) {
                case 'nombre':
                    $resultados = $this->recreoModel->buscarPorNombre($termino);
                    break;
                case 'servicio':
                    $resultados = $this->recreoModel->buscarPorServicio($termino);
                    break;
                case 'todo':
                    $resultados = $this->recreoModel->buscarEnTodo($termino);
                    break;
                default:
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Tipo de búsqueda no válido. Use: nombre, servicio o todo'
                    ]);
                    return;
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Búsqueda completada',
                'termino' => $termino,
                'tipo' => $tipo,
                'data' => $resultados,
                'total' => count($resultados)
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error en la búsqueda: ' . $e->getMessage()
            ]);
        }
    }

    // VER RECREO ESPECÍFICO (JSON)
    public function verRecreo($id) {
        $this->configurarHeadersJSON();
        
        // Validar token
        $tokenValido = $this->validarToken();
        if (!$tokenValido) return;
        
        try {
            if (empty($id)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de recreo requerido'
                ]);
                return;
            }
            
            $recreo = $this->recreoModel->getById($id);
            
            if (!$recreo) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Recreo no encontrado'
                ]);
                return;
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Recreo encontrado',
                'data' => $recreo
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener recreo: ' . $e->getMessage()
            ]);
        }
    }

    // MÉTODOS PRIVADOS
    private function configurarHeadersJSON() {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }
    }

    private function validarToken() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        $token = str_replace('Bearer ', '', $token);
        
        if (empty($token)) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => '❌ Token de acceso requerido'
            ]);
            return false;
        }
        
        $tokenData = $this->tokenApiModel->getByToken($token);
        
        if (!$tokenData) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => '❌ Token no existe'
            ]);
            return false;
        }
        
        if (!$tokenData['estado']) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => '❌ Token inactivo'
            ]);
            return false;
        }
        
        return true;
    }
}
?>