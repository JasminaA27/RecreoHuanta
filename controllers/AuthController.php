<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function showLogin() {
        // Si ya está logueado, redirigir al dashboard
        if (isLoggedIn()) {
            redirect('index.php?action=dashboard');
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = clean($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                showAlert('Por favor complete todos los campos', 'error');
                $this->showLogin();
                return;
            }

            $user = $this->usuarioModel->login($username, $password);

            if ($user) {
                // Crear sesión
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_nombre'] = $user['nombre'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['login_time'] = time();

                showAlert('¡Bienvenido ' . $user['nombre'] . '!', 'success');
                redirect('index.php?action=dashboard');
            } else {
                showAlert('Usuario o contraseña incorrectos', 'error');
                $this->showLogin();
            }
        } else {
            $this->showLogin();
        }
    }

    public function logout() {
        // Destruir sesión
        session_destroy();
        showAlert('Sesión cerrada correctamente', 'success');
        redirect('index.php?action=login');
    }

    public function checkSession() {
        if (!isLoggedIn()) {
            showAlert('Su sesión ha expirado. Por favor inicie sesión nuevamente.', 'warning');
            redirect('index.php?action=login');
        }

        // Verificar tiempo de inactividad (opcional - 2 horas)
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 7200) {
            $this->logout();
        }

        // Actualizar tiempo de actividad
        $_SESSION['login_time'] = time();
    }
}
?>