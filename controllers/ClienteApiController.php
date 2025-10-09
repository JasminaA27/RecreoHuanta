<?php
require_once __DIR__ . '/../models/ClienteApi.php';
require_once __DIR__ . '/../models/TokenApi.php';

class ClienteApiController {
    private $clienteApiModel;
    private $tokenApiModel;

    public function __construct() {
        $this->clienteApiModel = new ClienteApi();
        $this->tokenApiModel = new TokenApi();
    }

    // MOSTRAR VISTA PRINCIPAL CON HTML
    public function index() {
        $search = $_GET['search'] ?? '';
        
        try {
            // Obtener todos los clientes
            $clientes = $this->clienteApiModel->getAll();
            
            // Aplicar filtro de búsqueda si existe
            if (!empty($search)) {
                $clientes = array_filter($clientes, function($cliente) use ($search) {
                    return stripos($cliente['dni'], $search) !== false || 
                           stripos($cliente['nombre'], $search) !== false ||
                           stripos($cliente['apellido'], $search) !== false ||
                           stripos($cliente['correo'], $search) !== false;
                });
                // Reindexar el array
                $clientes = array_values($clientes);
            }
            
            // Obtener estadísticas
            $stats = $this->clienteApiModel->getStats();
            
            // Incluir la vista
            include __DIR__ . '/../views/cliente_api/index.php';
            
        } catch (Exception $e) {
            // Mostrar error en la vista
            $error = 'Error al cargar clientes: ' . $e->getMessage();
            include __DIR__ . '/../views/clientes¿_api/index.php';
        }
    }

    // MOSTRAR FORMULARIO DE CREACIÓN
    public function create() {
        // Incluir el formulario de creación
        include __DIR__ . '/../views/cliente_api/create.php';
    }

    // PROCESAR CREACIÓN DE CLIENTE
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=create');
            exit;
        }

        $data = [
            'dni' => trim($_POST['dni'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        // Validaciones
        $errors = $this->validarCliente($data);

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=create');
            exit;
        }

        try {
            $clienteId = $this->clienteApiModel->create($data);

            if ($clienteId) {
                $_SESSION['success'] = 'Cliente API creado exitosamente';
                header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear cliente API';
                $_SESSION['form_data'] = $data;
                header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=create');
                exit;
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear cliente: ' . $e->getMessage();
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=create');
            exit;
        }
    }

    // MOSTRAR DETALLES DEL CLIENTE
    public function view() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no válido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        try {
            $cliente = $this->clienteApiModel->getById($id);
            
            if ($cliente) {
                // Obtener tokens del cliente
                $tokens = $this->tokenApiModel->getByClient($id);
                $cliente['tokens'] = $tokens;
                
                include __DIR__ . '/../views/cliente_api/view.php';
            } else {
                $_SESSION['error'] = 'Cliente no encontrado';
                header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
                exit;
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al obtener cliente: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }
    }

    // MOSTRAR FORMULARIO DE EDICIÓN
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no válido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        try {
            $cliente = $this->clienteApiModel->getById($id);
            
            if ($cliente) {
                include __DIR__ . '/../views/clientes_api/edit.php';
            } else {
                $_SESSION['error'] = 'Cliente no encontrado';
                header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
                exit;
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar cliente: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }
    }

    // PROCESAR ACTUALIZACIÓN DE CLIENTE
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no válido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        $data = [
            'dni' => trim($_POST['dni'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        // Validaciones
        $errors = $this->validarCliente($data, $id);

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=edit&id=' . $id);
            exit;
        }

        try {
            $success = $this->clienteApiModel->update($id, $data);

            if ($success) {
                $_SESSION['success'] = 'Cliente actualizado exitosamente';
                header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=view&id=' . $id);
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar cliente';
                $_SESSION['form_data'] = $data;
                header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=edit&id=' . $id);
                exit;
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar cliente: ' . $e->getMessage();
            $_SESSION['form_data'] = $data;
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api&method=edit&id=' . $id);
            exit;
        }
    }

    // CAMBIAR ESTADO DEL CLIENTE
    public function change_status() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        $id = $_POST['id'] ?? 0;
        $estado = $_POST['estado'] ?? 0;

        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no válido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        try {
            $success = $this->clienteApiModel->changeStatus($id, $estado);

            if ($success) {
                $estadoTexto = $estado ? 'activado' : 'desactivado';
                $_SESSION['success'] = "Cliente {$estadoTexto} exitosamente";
            } else {
                $_SESSION['error'] = 'Error al cambiar estado del cliente';
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cambiar estado: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
        exit;
    }

    // ELIMINAR CLIENTE
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        $id = $_POST['id'] ?? 0;

        if (!$id) {
            $_SESSION['error'] = 'ID de cliente no válido';
            header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
            exit;
        }

        try {
            // Verificar si el cliente tiene tokens activos
            $tokens = $this->tokenApiModel->getByClient($id);
            $tokensActivos = array_filter($tokens, function($token) {
                return $token['estado'] == 1;
            });

            if (!empty($tokensActivos)) {
                $_SESSION['error'] = 'No se puede eliminar el cliente porque tiene tokens activos';
                header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
                exit;
            }

            $success = $this->clienteApiModel->delete($id);

            if ($success) {
                $_SESSION['success'] = 'Cliente eliminado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar cliente';
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar cliente: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'index.php?action=cliente_api');
        exit;
    }

    // ========== MÉTODOS PRIVADOS ==========

    private function validarCliente($data, $excludeId = null) {
        $errors = [];

        if (empty($data['dni'])) {
            $errors[] = 'El DNI es obligatorio';
        } elseif (!preg_match('/^[0-9]{8}$/', $data['dni'])) {
            $errors[] = 'El DNI debe tener 8 dígitos';
        } elseif ($this->clienteApiModel->dniExists($data['dni'], $excludeId)) {
            $errors[] = 'El DNI ya está registrado';
        }

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es obligatorio';
        } elseif (strlen($data['nombre']) < 2) {
            $errors[] = 'El nombre debe tener al menos 2 caracteres';
        }

        if (empty($data['apellido'])) {
            $errors[] = 'El apellido es obligatorio';
        } elseif (strlen($data['apellido']) < 2) {
            $errors[] = 'El apellido debe tener al menos 2 caracteres';
        }

        if (!empty($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico no es válido';
        } elseif (!empty($data['correo']) && $this->clienteApiModel->correoExists($data['correo'], $excludeId)) {
            $errors[] = 'El correo electrónico ya está registrado';
        }

        return $errors;
    }
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}