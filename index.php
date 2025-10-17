<?php
require_once 'config/config.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/RecreoController.php';
require_once 'controllers/ClienteApiController.php';
require_once 'controllers/TokenApiController.php';
require_once 'controllers/CountRequestController.php';

// Obtener la acción de la URL
$action = $_GET['action'] ?? 'login';
$method = $_GET['method'] ?? '';

try {
    switch ($action) {
        // Autenticación
        case 'login':
            $controller = new AuthController();
            $controller->login();
            break;

        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        // Dashboard
        case 'dashboard':
            requireLogin();
            include 'views/dashboard.php';
            break;

        // Gestión de Usuarios
        case 'usuarios':
            $controller = new UsuarioController();
            
            switch ($method) {
                case 'create':
                    $controller->create();
                    break;
                case 'edit':
                    $controller->edit();
                    break;
                
                case 'change_status':
                    $controller->changeStatus();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        // Gestión de Recreos
        case 'recreos':
            $controller = new RecreoController();
            
            switch ($method) {
                case 'create':
                    $controller->create();
                    break;
                case 'edit':
                    $controller->edit();
                    break;
                case 'view':
                    $controller->view();
                    break;
                case 'change_status':
                    $controller->changeStatus();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        // Gestión de Clientes API
        case 'cliente_api':
            $controller = new ClienteApiController();
            
            switch ($method) {
                case 'create':
                    $controller->create();
                    break;
                case 'edit':
                    $controller->edit();
                    break;
                case 'view':
                    $controller->view();
                    break;
                case 'change_status':
                    $controller->changeStatus();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        // Gestión de Tokens API
        case 'tokens_api':
            $controller = new TokenApiController();
            
            switch ($method) {
                case 'create':
                    $controller->create();
                    break;
                case 'edit':
                    $controller->edit();
                    break;
                case 'view':
                    $controller->view();
                    break;
                case 'change_status':
                    $controller->changeStatus();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                case 'generate':
                    $controller->generate();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        // Gestión de Estadísticas API
        case 'count_request':
            $controller = new CountRequestController();
            
            switch ($method) {
              /* case 'create':
                    $controller->create();
                    break;
                case 'edit':
                    $controller->edit();
                    break;*/
                case 'view':
                    $controller->view();
                    break;
                case 'delete':
                    $controller->delete();
                    break;
                case 'cleanup':
                    $controller->cleanup();
                    break;
                case 'stats':
                    $controller->stats();
                    break;
                case 'tokens':
                    $controller->tokens();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        // Ruta por defecto
        default:
            if (isLoggedIn()) {
                redirect('index.php?action=dashboard');
            } else {
                redirect('index.php?action=login');
            }
            break;
            // Después del caso 'count_request', agrega esto:

        // API PÚBLICA (sin requerir login)
        case 'api_public':
            require_once 'controllers/ApiPublicController.php';
            $controller = new ApiPublicController();
            
            switch ($method) {
                case 'listarRecreos':
                    $controller->listarRecreos();
                    break;
                case 'buscarRecreos':
                    $controller->buscarRecreos();
                    break;
                case 'verRecreo':
                    $id = $_GET['id'] ?? 0;
                    $controller->verRecreo($id);
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;
    }

} catch (Exception $e) {
    error_log("Error en router: " . $e->getMessage());
    showAlert('Ha ocurrido un error interno. Por favor intente nuevamente.', 'error');
    
    if (isLoggedIn()) {
        redirect('index.php?action=dashboard');
    } else {
        redirect('index.php?action=login');
    }
}
?>