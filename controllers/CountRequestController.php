<?php
require_once __DIR__ . '/../models/CountRequest.php';
require_once __DIR__ . '/../models/TokenApi.php';

class CountRequestController {
    private $countRequestModel;
    private $tokenApiModel;

    public function __construct() {
        requireLogin();
        $this->countRequestModel = new CountRequest();
        $this->tokenApiModel = new TokenApi();
    }

    // Mostrar lista de solicitudes
    public function index() {
        $search = $_GET['search'] ?? '';
        $token_id = $_GET['token_id'] ?? '';
        $limit = $_GET['limit'] ?? 100;
        
        if (!empty($search)) {
            $requests = $this->countRequestModel->getAll(200);
            $requests = array_filter($requests, function($request) use ($search) {
                return stripos($request['token'], $search) !== false || 
                       stripos($request['nombre'], $search) !== false ||
                       stripos($request['tipo'], $search) !== false;
            });
            $requests = array_slice($requests, 0, $limit);
        } elseif (!empty($token_id)) {
            $requests = $this->countRequestModel->getByToken($token_id, $limit);
        } else {
            $requests = $this->countRequestModel->getAll($limit);
        }

        $stats = $this->countRequestModel->getStats();
        $topTokens = $this->countRequestModel->getTopTokens(5);
        $tokens = $this->tokenApiModel->getActive();
        
        include __DIR__ . '/../views/count_request/index.php';
    }

    // Ver detalles de solicitud
    public function view() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            showAlert('ID de solicitud no válido', 'error');
            redirect('index.php?action=count_request');
        }

        $request = $this->countRequestModel->getById($id);
        
        if (!$request) {
            showAlert('Solicitud no encontrada', 'error');
            redirect('index.php?action=count_request');
        }

        include __DIR__ . '/../views/count_request/view.php';
    }

    // Mostrar estadísticas
    public function stats() {
        $stats = $this->countRequestModel->getStats();
        $dailyStats = $this->countRequestModel->getDailyCount(30);
        $requestTypes = $this->countRequestModel->getRequestTypes();
        
        include __DIR__ . '/../views/count_request/stats.php';
    }

    // Mostrar top tokens
    public function tokens() {
        $limit = $_GET['limit'] ?? 20;
        $topTokens = $this->countRequestModel->getTopTokens($limit);
        
        include __DIR__ . '/../views/count_request/tokens.php';
    }

    // Eliminar solicitud
    public function delete() {
        $id = $_POST['id'] ?? 0;

        if (!$id) {
            showAlert('ID de solicitud no válido', 'error');
            redirect('index.php?action=count_request');
        }

        $success = $this->countRequestModel->delete($id);

        if ($success) {
            showAlert('Solicitud eliminada exitosamente', 'success');
        } else {
            showAlert('Error al eliminar la solicitud', 'error');
        }

        redirect('index.php?action=count_request');
    }

    // Limpiar solicitudes antiguas
    public function cleanup() {
        $days = $_POST['days'] ?? 30;
        
        $success = $this->countRequestModel->deleteOldRequests($days);

        if ($success) {
            showAlert("Solicitudes antiguas (más de {$days} días) eliminadas exitosamente", 'success');
        } else {
            showAlert('Error al limpiar las solicitudes antiguas', 'error');
        }

        redirect('index.php?action=count_request');
    }
}
?>