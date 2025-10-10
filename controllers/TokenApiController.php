<?php
require_once __DIR__ . '/../models/TokenApi.php';
require_once __DIR__ . '/../models/ClienteApi.php';

class TokenApiController {
    private $tokenApiModel;
    private $clienteApiModel;

    public function __construct() {
        requireLogin();
        $this->tokenApiModel = new TokenApi();
        $this->clienteApiModel = new ClienteApi();
    }

    // Mostrar lista de tokens
    public function index() {
        $search = $_GET['search'] ?? '';
        $cliente_id = $_GET['cliente_id'] ?? '';
        
        if (!empty($search)) {
            $tokens = $this->tokenApiModel->getAll();
            $tokens = array_filter($tokens, function($token) use ($search) {
                return stripos($token['token'], $search) !== false || 
                       stripos($token['nombre'], $search) !== false ||
                       stripos($token['apellido'], $search) !== false;
            });
        } elseif (!empty($cliente_id)) {
            $tokens = $this->tokenApiModel->getByClient($cliente_id);
        } else {
            $tokens = $this->tokenApiModel->getAll();
        }

        $clientes = $this->clienteApiModel->getAll();
        $stats = $this->tokenApiModel->getStats();
        
        include __DIR__ . '/../views/tokens_api/index.php';
    }

    // Mostrar formulario de crear token
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            $clientes = $this->clienteApiModel->getAll();
            include __DIR__ . '/../views/tokens_api/create.php';
        }
    }

    // Guardar nuevo token
    public function store() {
        $data = [
            'id_client_api' => clean($_POST['id_client_api'] ?? 0),
            'token' => clean($_POST['token'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        // Validaciones
        $errors = [];

        if (empty($data['id_client_api'])) {
            $errors[] = 'El cliente API es obligatorio';
        }

        if (empty($data['token'])) {
            $errors[] = 'El token es obligatorio';
        }

        if ($this->tokenApiModel->tokenExists($data['token'])) {
            $errors[] = 'El token ya está registrado';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            $clientes = $this->clienteApiModel->getAll();
            include __DIR__ . '/../views/tokens_api/create.php';
            return;
        }

        // Crear token
        $tokenId = $this->tokenApiModel->create($data);

        if ($tokenId) {
            showAlert('Token API creado exitosamente', 'success');
            redirect('index.php?action=tokens_api');
        } else {
            showAlert('Error al crear el token API', 'error');
            $clientes = $this->clienteApiModel->getAll();
            include __DIR__ . '/../views/tokens_api/create.php';
        }
    }

    // Mostrar formulario de editar token
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            showAlert('ID de token no válido', 'error');
            redirect('index.php?action=tokens_api');
        }

        $token = $this->tokenApiModel->getById($id);
        
        if (!$token) {
            showAlert('Token no encontrado', 'error');
            redirect('index.php?action=tokens_api');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            $clientes = $this->clienteApiModel->getAll();
            include __DIR__ . '/../views/tokens_api/edit.php';
        }
    }

    // Actualizar token
    public function update($id) {
        $data = [
            'id_client_api' => clean($_POST['id_client_api'] ?? 0),
            'token' => clean($_POST['token'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        // Validaciones
        $errors = [];

        if (empty($data['id_client_api'])) {
            $errors[] = 'El cliente API es obligatorio';
        }

        if (empty($data['token'])) {
            $errors[] = 'El token es obligatorio';
        }

        if ($this->tokenApiModel->tokenExists($data['token'], $id)) {
            $errors[] = 'El token ya está registrado';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            $token = $this->tokenApiModel->getById($id);
            $clientes = $this->clienteApiModel->getAll();
            include __DIR__ . '/../views/tokens_api/edit.php';
            return;
        }

        // Actualizar token
        $success = $this->tokenApiModel->update($id, $data);

        if ($success) {
            showAlert('Token API actualizado exitosamente', 'success');
            redirect('index.php?action=tokens_api');
        } else {
            showAlert('Error al actualizar el token API', 'error');
            $token = $this->tokenApiModel->getById($id);
            $clientes = $this->clienteApiModel->getAll();
            include __DIR__ . '/../views/tokens_api/edit.php';
        }
    }

    // Ver detalles del token
    public function view() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            showAlert('ID de token no válido', 'error');
            redirect('index.php?action=tokens_api');
        }

        $token = $this->tokenApiModel->getById($id);
        
        if (!$token) {
            showAlert('Token no encontrado', 'error');
            redirect('index.php?action=tokens_api');
        }

        include __DIR__ . '/../views/tokens_api/view.php';
    }

    // Generar token automáticamente
    public function generate() {
        $id_client_api = $_POST['id_client_api'] ?? 0;
        
        if (!$id_client_api) {
            showAlert('ID de cliente API no válido', 'error');
            redirect('index.php?action=tokens_api&method=create');
        }

        $token = $this->tokenApiModel->generateToken($id_client_api);
        $data = [
            'id_client_api' => $id_client_api,
            'token' => $token,
            'estado' => 1
        ];

        $tokenId = $this->tokenApiModel->create($data);

        if ($tokenId) {
            showAlert('Token API generado y creado exitosamente', 'success');
        } else {
            showAlert('Error al generar el token API', 'error');
        }

        redirect('index.php?action=tokens_api');
    }

    // Cambiar estado del token
    public function changeStatus() {
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? 0;

        if (!$id) {
            showAlert('ID de token no válido', 'error');
            redirect('index.php?action=tokens_api');
        }

        $success = $this->tokenApiModel->changeStatus($id, $estado);

        if ($success) {
            $estadoTexto = $estado ? 'activado' : 'desactivado';
            showAlert("Token API {$estadoTexto} exitosamente", 'success');
        } else {
            showAlert('Error al cambiar el estado del token API', 'error');
        }

        redirect('index.php?action=tokens_api');
    }

    // Eliminar token
    public function delete() {
        $id = $_POST['id'] ?? 0;

        if (!$id) {
            showAlert('ID de token no válido', 'error');
            redirect('index.php?action=tokens_api');
        }

        $success = $this->tokenApiModel->delete($id);

        if ($success) {
            showAlert('Token API eliminado exitosamente', 'success');
        } else {
            showAlert('Error al eliminar el token API', 'error');
        }

        redirect('index.php?action=tokens_api');
    }
}
?>