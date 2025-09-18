<?php
$pageTitle = 'Editar Usuario - Sistema Recreos';
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
                <li class="breadcrumb-item active">Editar Usuario</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-person-gear me-2"></i>
                Editar Usuario
                <span class="text-muted fs-6">#<?php echo $usuario['id']; ?></span>
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
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=usuarios&method=edit&id=<?php echo $usuario['id']; ?>" id="editUserForm">
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
                                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? $usuario['nombre']); ?>"
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
                                       value="<?php echo htmlspecialchars($_POST['username'] ?? $usuario['username']); ?>"
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
                                    Nueva Contraseña
                                    <small class="text-muted">(Opcional)</small>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password"
                                           placeholder="Dejar vacío para mantener actual">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 6 caracteres. Dejar vacío para no cambiar</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirm" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Confirmar Nueva Contraseña
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirm" 
                                       name="password_confirm"
                                       placeholder="Repita la nueva contraseña">
                                <div class="form-text">Solo si está cambiando la contraseña</div>
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
                                    <option value="activo" <?php echo ($_POST['estado'] ?? $usuario['estado']) === 'activo' ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="inactivo" <?php echo ($_POST['estado'] ?? $usuario['estado']) === 'inactivo' ? 'selected' : ''; ?>>
                                        Inactivo
                                    </option>
                                </select>
                                <div class="form-text">
                                    <?php if ($usuario['id'] == $_SESSION['admin_id']): ?>
                                        <span class="text-warning">No puedes cambiar tu propio estado</span>
                                    <?php else: ?>
                                        El usuario activo podrá acceder al sistema
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Información Adicional
                                </label>
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted">
                                        <strong>Creado:</strong> <?php echo date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])); ?><br>
                                        <strong>Actualizado:</strong> <?php echo date('d/m/Y H:i', strtotime($usuario['fecha_actualizacion'])); ?>
                                        <?php if ($usuario['id'] == $_SESSION['admin_id']): ?>
                                            <br><span class="text-primary"><strong>Este es tu usuario</strong></span>
                                        <?php endif; ?>
                                    </small>
                                </div>
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
                                    <i class="bi bi-check-circle me-2"></i>Actualizar Usuario
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
// Deshabilitar cambio de estado para usuario actual
<?php if ($usuario['id'] == $_SESSION['admin_id']): ?>
document.getElementById('estado').disabled = true;
<?php endif; ?>

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
document.getElementById('editUserForm').addEventListener('submit', function(e) {
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
    
    // Solo validar contraseña si se está intentando cambiar
    if (password !== '' || passwordConfirm !== '') {
        if (password.length < 6) {
            errors.push('La nueva contraseña debe tener al menos 6 caracteres');
        }
        
        if (password !== passwordConfirm) {
            errors.push('Las contraseñas no coinciden');
        }
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

// Limpiar confirmación de contraseña si se borra la contraseña
document.getElementById('password').addEventListener('input', function() {
    const passwordConfirm = document.getElementById('password_confirm');
    if (this.value === '') {
        passwordConfirm.value = '';
        passwordConfirm.classList.remove('is-invalid');
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>