<?php
$pageTitle = 'Crear Usuario - Sistema Recreos';
$action = 'usuarios';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo BASE_URL; ?>index.php?action=usuarios">Usuarios</a>
                </li>
                <li class="breadcrumb-item active">Crear Usuario</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-person-plus me-2"></i>
                Crear Nuevo Usuario
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=usuarios" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Información del Usuario
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=usuarios&method=create" id="createUserForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre" 
                                       name="nombre"
                                       placeholder="Ej: Juan Pérez García"
                                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                                       required>
                                <div class="form-text">Ingrese el nombre completo del usuario</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-at me-1"></i>
                                    Nombre de Usuario <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username"
                                       placeholder="Ej: juanperez"
                                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                       required>
                                <div class="form-text">Mínimo 3 caracteres, solo letras y números</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>
                                    Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password"
                                           placeholder="Mínimo 6 caracteres"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Confirmar Contraseña <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirm" 
                                       name="password_confirm"
                                       placeholder="Repita la contraseña"
                                       required>
                                <div class="form-text">Debe coincidir con la contraseña anterior</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">
                                    <i class="bi bi-toggle-on me-1"></i>
                                    Estado del Usuario
                                </label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="activo" <?php echo ($_POST['estado'] ?? 'activo') === 'activo' ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="inactivo" <?php echo ($_POST['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>
                                        Inactivo
                                    </option>
                                </select>
                                <div class="form-text">El usuario activo podrá acceder al sistema</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo BASE_URL; ?>index.php?action=usuarios" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Crear Usuario
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        password.type = 'password';
        icon.className = 'bi bi-eye';
    }
});

// Validación del formulario
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    
    let errors = [];
    
    if (nombre === '') {
        errors.push('El nombre es obligatorio');
    }
    
    if (username === '') {
        errors.push('El nombre de usuario es obligatorio');
    } else if (username.length < 3) {
        errors.push('El nombre de usuario debe tener al menos 3 caracteres');
    }
    
    if (password === '') {
        errors.push('La contraseña es obligatoria');
    } else if (password.length < 6) {
        errors.push('La contraseña debe tener al menos 6 caracteres');
    }
    
    if (password !== passwordConfirm) {
        errors.push('Las contraseñas no coinciden');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Por favor corrija los siguientes errores:\n\n' + errors.join('\n'));
    }
});

// Validación en tiempo real de confirmación de contraseña
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordConfirm = this.value;
    
    if (passwordConfirm !== '' && password !== passwordConfirm) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Validación de username
document.getElementById('username').addEventListener('input', function() {
    const username = this.value;
    const regex = /^[a-zA-Z0-9]+$/;
    
    if (username !== '' && (!regex.test(username) || username.length < 3)) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>