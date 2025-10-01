<?php
require_once __DIR__ . '/../models/ClienteApi.php';

class ClienteApiController {
    private $clienteApiModel;

    public function __construct() {
        requireLogin(); // Verificar que esté logueado
        $this->clienteApiModel = new ClienteApi();
    }

    // Mostrar lista de clientes API
    public function index() {
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            // Implementar búsqueda si es necesario
            $clientes = $this->clienteApiModel->getAll();
        } else {
            $clientes = $this->clienteApiModel->getAll();
        }

        $stats = $this->clienteApiModel->getStats();
        
        include __DIR__ . '/../views/cliente_api/index.php';
    }

    // Mostrar formulario de crear cliente API
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            include __DIR__ . '/../views/cliente_api/create.php';
        }
    }

    // Guardar nuevo cliente API
    public function store() {
        $data = [
            'dni' => clean($_POST['dni'] ?? ''),
            'nombre' => clean($_POST['nombre'] ?? ''),
            'apellido' => clean($_POST['apellido'] ?? ''),
            'telefono' => clean($_POST['telefono'] ?? ''),
            'correo' => clean($_POST['correo'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        // Validaciones
        $errors = [];

        if (empty($data['dni'])) {
            $errors[] = 'El DNI es obligatorio';
        }

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($data['apellido'])) {
            $errors[] = 'El apellido es obligatorio';
        }

        if ($this->clienteApiModel->dniExists($data['dni'])) {
            $errors[] = 'El DNI ya está registrado';
        }

        if (!empty($data['correo']) && $this->clienteApiModel->correoExists($data['correo'])) {
            $errors[] = 'El correo electrónico ya está registrado';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            include __DIR__ . '/../views/cliente_api/create.php';
            return;
        }

        // Crear cliente API
        $clienteId = $this->clienteApiModel->create($data);

        if ($clienteId) {
            showAlert('Cliente API creado exitosamente', 'success');
            redirect('index.php?action=cliente_api');
        } else {
            showAlert('Error al crear el cliente API', 'error');
            include __DIR__ . '/../views/cliente_api/create.php';
        }
    }

    // Mostrar formulario de editar cliente API
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            showAlert('ID de cliente API no válido', 'error');
            redirect('index.php?action=cliente_api');
        }

        $cliente = $this->clienteApiModel->getById($id);
        
        if (!$cliente) {
            showAlert('Cliente API no encontrado', 'error');
            redirect('index.php?action=cliente_api');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            include __DIR__ . '/../views/cliente_api/edit.php';
        }
    }

    // Actualizar cliente API
    public function update($id) {
        $data = [
            'dni' => clean($_POST['dni'] ?? ''),
            'nombre' => clean($_POST['nombre'] ?? ''),
            'apellido' => clean($_POST['apellido'] ?? ''),
            'telefono' => clean($_POST['telefono'] ?? ''),
            'correo' => clean($_POST['correo'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        // Validaciones
        $errors = [];

        if (empty($data['dni'])) {
            $errors[] = 'El DNI es obligatorio';
        }

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($data['apellido'])) {
            $errors[] = 'El apellido es obligatorio';
        }

        if ($this->clienteApiModel->dniExists($data['dni'], $id)) {
            $errors[] = 'El DNI ya está registrado';
        }

        if (!empty($data['correo']) && $this->clienteApiModel->correoExists($data['correo'], $id)) {
            $errors[] = 'El correo electrónico ya está registrado';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            $cliente = $this->clienteApiModel->getById($id);
            include __DIR__ . '/../views/cliente_api/edit.php';
            return;
        }

        // Actualizar cliente API
        $success = $this->clienteApiModel->update($id, $data);

        if ($success) {
            showAlert('Cliente API actualizado exitosamente', 'success');
            redirect('index.php?action=cliente_api');
        } else {
            showAlert('Error al actualizar el cliente API', 'error');
            $cliente = $this->clienteApiModel->getById($id);
            include __DIR__ . '/../views/cliente_api/edit.php';
        }
    }

    // Cambiar estado del cliente API
    public function changeStatus() {
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? 0;

        if (!$id) {
            showAlert('ID de cliente API no válido', 'error');
            redirect('index.php?action=cliente_api');
        }

        $success = $this->clienteApiModel->changeStatus($id, $estado);

        if ($success) {
            $estadoTexto = $estado ? 'activado' : 'desactivado';
            showAlert("Cliente API {$estadoTexto} exitosamente", 'success');
        } else {
            showAlert('Error al cambiar el estado del cliente API', 'error');
        }

        redirect('index.php?action=cliente_api');
    }

    // Eliminar cliente API
    public function delete() {
        $id = $_POST['id'] ?? 0;

        if (!$id) {
            showAlert('ID de cliente API no válido', 'error');
            redirect('index.php?action=cliente_api');
        }

        $success = $this->clienteApiModel->delete($id);

        if ($success) {
            showAlert('Cliente API eliminado exitosamente', 'success');
        } else {
            showAlert('Error al eliminar el cliente API', 'error');
        }

        redirect('index.php?action=cliente_api');
    }
}
?>