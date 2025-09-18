<?php
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        requireLogin(); // Verificar que esté logueado
        $this->usuarioModel = new Usuario();
    }

    // Mostrar lista de usuarios
    public function index() {
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $usuarios = $this->usuarioModel->search($search);
        } else {
            $usuarios = $this->usuarioModel->getAll();
        }

        $stats = $this->usuarioModel->getStats();
        
        include __DIR__ . '/../views/usuarios/index.php';
    }

    // Mostrar formulario de crear usuario
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
        } else {
            include __DIR__ . '/../views/usuarios/create.php';
        }
    }

    // Guardar nuevo usuario
    public function store() {
        $nombre = clean($_POST['nombre'] ?? '');
        $username = clean($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $estado = clean($_POST['estado'] ?? 'activo');

        // Validaciones
        $errors = [];

        if (empty($nombre)) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($username)) {
            $errors[] = 'El nombre de usuario es obligatorio';
        } elseif (strlen($username) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres';
        }

        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        } elseif (strlen($password) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        }

        if ($password !== $password_confirm) {
            $errors[] = 'Las contraseñas no coinciden';
        }

        if ($this->usuarioModel->usernameExists($username)) {
            $errors[] = 'El nombre de usuario ya existe';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            include __DIR__ . '/../views/usuarios/create.php';
            return;
        }

        // Crear usuario
        $userId = $this->usuarioModel->create($nombre, $username, $password, $estado);

        if ($userId) {
            showAlert('Usuario creado exitosamente', 'success');
            redirect('index.php?action=usuarios');
        } else {
            showAlert('Error al crear el usuario', 'error');
            include __DIR__ . '/../views/usuarios/create.php';
        }
    }

    // Mostrar formulario de editar usuario
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            showAlert('ID de usuario no válido', 'error');
            redirect('index.php?action=usuarios');
        }

        $usuario = $this->usuarioModel->getById($id);
        
        if (!$usuario) {
            showAlert('Usuario no encontrado', 'error');
            redirect('index.php?action=usuarios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
        } else {
            include __DIR__ . '/../views/usuarios/edit.php';
        }
    }

    // Actualizar usuario
    public function update($id) {
        $nombre = clean($_POST['nombre'] ?? '');
        $username = clean($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $estado = clean($_POST['estado'] ?? 'activo');

        // Validaciones
        $errors = [];

        if (empty($nombre)) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($username)) {
            $errors[] = 'El nombre de usuario es obligatorio';
        } elseif (strlen($username) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres';
        }

        if (!empty($password)) {
            if (strlen($password) < 6) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres';
            }
            if ($password !== $password_confirm) {
                $errors[] = 'Las contraseñas no coinciden';
            }
        }

        if ($this->usuarioModel->usernameExists($username, $id)) {
            $errors[] = 'El nombre de usuario ya existe';
        }

        if (!empty($errors)) {
            showAlert(implode('<br>', $errors), 'error');
            $usuario = $this->usuarioModel->getById($id);
            include __DIR__ . '/../views/usuarios/edit.php';
            return;
        }

        // Actualizar usuario
        $success = $this->usuarioModel->update($id, $nombre, $username, $estado, !empty($password) ? $password : null);

        if ($success) {
            showAlert('Usuario actualizado exitosamente', 'success');
            redirect('index.php?action=usuarios');
        } else {
            showAlert('Error al actualizar el usuario', 'error');
            $usuario = $this->usuarioModel->getById($id);
            include __DIR__ . '/../views/usuarios/edit.php';
        }
    }

    // Cambiar estado del usuario
    public function changeStatus() {
        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? '';

        if (!$id || !in_array($estado, ['activo', 'inactivo'])) {
            showAlert('Datos no válidos', 'error');
            redirect('index.php?action=usuarios');
        }

        $success = $this->usuarioModel->changeStatus($id, $estado);

        if ($success) {
            $action = $estado === 'activo' ? 'activado' : 'desactivado';
            showAlert("Usuario {$action} exitosamente", 'success');
        } else {
            showAlert('Error al cambiar el estado del usuario', 'error');
        }

        redirect('index.php?action=usuarios');
    }

    // Eliminar usuario (soft delete)
    public function delete() {
        $id = $_POST['id'] ?? 0;

        if (!$id) {
            showAlert('ID de usuario no válido', 'error');
            redirect('index.php?action=usuarios');
        }

        // No permitir eliminar al usuario actual
        if ($id == $_SESSION['admin_id']) {
            showAlert('No puedes eliminar tu propio usuario', 'error');
            redirect('index.php?action=usuarios');
        }

        $success = $this->usuarioModel->delete($id);

        if ($success) {
            showAlert('Usuario eliminado exitosamente', 'success');
        } else {
            showAlert('Error al eliminar el usuario', 'error');
        }

        redirect('index.php?action=usuarios');
    }
}
?>