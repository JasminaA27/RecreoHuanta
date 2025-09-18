<?php
require_once __DIR__ . '/../models/Recreo.php';

class RecreoController {
    private $recreoModel;

    public function __construct() {
        requireLogin(); // Verificar que esté logueado
        $this->recreoModel = new Recreo();
    }

    // Mostrar lista de recreos
    public function index() {
        $search = $_GET['search'] ?? '';
        $ubicacion = $_GET['ubicacion'] ?? '';
        
        if (!empty($search)) {
            $recreos = $this->recreoModel->search($search);
        } elseif (!empty($ubicacion)) {
            $recreos = $this->recreoModel->getByUbicacion($ubicacion);
        } else {
            $recreos = $this->recreoModel->getAll();
        }

        $stats = $this->recreoModel->getStats();
        $ubicaciones = $this->recreoModel->getUbicaciones();
        
        include __DIR__ . '/../views/recreos/index.php';
    }

    // Mostrar formulario de crear recreo
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            include __DIR__ . '/../views/recreos/create.php';
        }
    }

    // Guardar nuevo recreo
    public function store() {
        $data = [
            'nombre' => clean($_POST['nombre'] ?? ''),
            'direccion' => clean($_POST['direccion'] ?? ''),
            'referencia' => clean($_POST['referencia'] ?? ''),
            'telefono' => clean($_POST['telefono'] ?? ''),
            'ubicacion' => clean($_POST['ubicacion'] ?? ''),
            'servicio' => clean($_POST['servicio'] ?? ''),
            'precio' => clean($_POST['precio'] ?? 0),
            'horario' => clean($_POST['horario'] ?? ''),
            'url_maps' => clean($_POST['url_maps'] ?? ''),
            'estado' => clean($_POST['estado'] ?? 'activo')
        ];

        // Validaciones
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($data['direccion'])) {
            $errors[] = 'La dirección es obligatoria';
        }

        if (empty($data['ubicacion'])) {
            $errors[] = 'La ubicación es obligatoria';
        }

        if ($data['precio'] < 0) {
            $errors[] = 'El precio no puede ser negativo';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            include __DIR__ . '/../views/recreos/create.php';
            return;
        }

        // Crear recreo
        $recreoId = $this->recreoModel->create($data);

        if ($recreoId) {
            showAlert('Recreo creado exitosamente', 'success');
            redirect('index.php?action=recreos');
        } else {
            showAlert('Error al crear el recreo', 'error');
            include __DIR__ . '/../views/recreos/create.php';
        }
    }

    // Mostrar formulario de editar recreo
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            showAlert('ID de recreo no válido', 'error');
            redirect('index.php?action=recreos');
        }

        $recreo = $this->recreoModel->getById($id);
        
        if (!$recreo) {
            showAlert('Recreo no encontrado', 'error');
            redirect('index.php?action=recreos');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            include __DIR__ . '/../views/recreos/edit.php';
        }
    }

    // Actualizar recreo
    public function update($id) {
        $data = [
            'nombre' => clean($_POST['nombre'] ?? ''),
            'direccion' => clean($_POST['direccion'] ?? ''),
            'referencia' => clean($_POST['referencia'] ?? ''),
            'telefono' => clean($_POST['telefono'] ?? ''),
            'ubicacion' => clean($_POST['ubicacion'] ?? ''),
            'servicio' => clean($_POST['servicio'] ?? ''),
            'precio' => clean($_POST['precio'] ?? 0),
            'horario' => clean($_POST['horario'] ?? ''),
            'url_maps' => clean($_POST['url_maps'] ?? ''),
            'estado' => clean($_POST['estado'] ?? 'activo')
        ];

        // Validaciones
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($data['direccion'])) {
            $errors[] = 'La dirección es obligatoria';
        }

        if (empty($data['ubicacion'])) {
            $errors[] = 'La ubicación es obligatoria';
        }

        if ($data['precio'] < 0) {
            $errors[] = 'El precio es obligatorio';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            $recreo = $this->recreoModel->getById($id);
            include __DIR__ . '/../views/recreos/edit.php';
            return;
        }

        // Actualizar recreo
        $success = $this->recreoModel->update($id, $data);

        if ($success) {
            showAlert('Recreo actualizado exitosamente', 'success');
            redirect('index.php?action=recreos');
        } else {
            showAlert('Error al actualizar el recreo', 'error');
            $recreo = $this->recreoModel->getById($id);
            include __DIR__ . '/../views/recreos/edit.php';
        }
    }

    // Cambiar estado del recreo
    public function changeStatus() {
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? '';

        if (!$id || !in_array($estado, ['activo', 'inactivo', 'pendiente'])) {
            showAlert('Datos no válidos', 'error');
            redirect('index.php?action=recreos');
        }

        $success = $this->recreoModel->changeStatus($id, $estado);

        if ($success) {
            showAlert("Estado del recreo cambiado a {$estado}", 'success');
        } else {
            showAlert('Error al cambiar el estado del recreo', 'error');
        }

        redirect('index.php?action=recreos');
    }

    // Eliminar recreo (soft delete)
    public function delete() {
        $id = $_POST['id'] ?? 0;

        if (!$id) {
            showAlert('ID de recreo no válido', 'error');
            redirect('index.php?action=recreos');
        }

        $success = $this->recreoModel->delete($id);

        if ($success) {
            showAlert('Recreo eliminado exitosamente', 'success');
        } else {
            showAlert('Error al eliminar el recreo', 'error');
        }

        redirect('index.php?action=recreos');
    }

    // Ver detalles del recreo
    public function view() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            showAlert('ID de recreo no válido', 'error');
            redirect('index.php?action=recreos');
        }

        $recreo = $this->recreoModel->getById($id);
        
        if (!$recreo) {
            showAlert('Recreo no encontrado', 'error');
            redirect('index.php?action=recreos');
        }

        include __DIR__ . '/../views/recreos/view.php';
    }
}