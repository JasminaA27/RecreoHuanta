<?php
$pageTitle = 'Crear Recreo - Sistema Recreos';
$action = 'recreos';
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
                    <a href="<?php echo BASE_URL; ?>index.php?action=recreos">Recreos</a>
                </li>
                <li class="breadcrumb-item active">Crear Recreo</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-geo-alt-fill me-2"></i>
                Crear Nuevo Recreo
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=recreos" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>
                    Información del Recreo
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=recreos&method=create" id="createRecreoForm">
                    <!-- Información Básica -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="bi bi-building me-1"></i>
                                    Nombre del Recreo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre" 
                                       name="nombre"
                                       placeholder="Ej: Parque Principal de Huanta"
                                       value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ubicacion" class="form-label">
                                    <i class="bi bi-pin-map me-1"></i>
                                    Ubicación <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="ubicacion" name="ubicacion" required>
                                    <option value="">Seleccione una ubicación</option>
                                    <option value="Huanta" <?php echo ($_POST['ubicacion'] ?? '') === 'Huanta' ? 'selected' : ''; ?>>Huanta</option>
                                    <option value="Luricocha" <?php echo ($_POST['ubicacion'] ?? '') === 'Luricocha' ? 'selected' : ''; ?>>Luricocha</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="direccion" class="form-label">
                                    <i class="bi bi-geo me-1"></i>
                                    Dirección <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="direccion" 
                                       name="direccion"
                                       placeholder="Ej: Plaza de Armas de Huanta"
                                       value="<?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">
                                    <i class="bi bi-telephone me-1"></i>
                                    Teléfono
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="telefono" 
                                       name="telefono"
                                       placeholder="Ej: 066-123456"
                                       value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Referencias -->
                    <div class="mb-3">
                        <label for="referencia" class="form-label">
                            <i class="bi bi-signpost me-1"></i>
                            Referencias
                        </label>
                        <textarea class="form-control" 
                                  id="referencia" 
                                  name="referencia"
                                  rows="2"
                                  placeholder="Ej: Frente a la municipalidad, cerca del mercado central"><?php echo htmlspecialchars($_POST['referencia'] ?? ''); ?></textarea>
                    </div>

                    <!-- Servicios y Precio -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="servicio" class="form-label">
                                    <i class="bi bi-list-check me-1"></i>
                                    Servicios Ofrecidos
                                </label>
                                <textarea class="form-control" 
                                          id="servicio" 
                                          name="servicio"
                                          rows="3"
                                          placeholder="Ej: Áreas verdes, bancas, juegos infantiles, canchas deportivas"><?php echo htmlspecialchars($_POST['servicio'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                      <div class="col-md-4">
    <div class="mb-3">
        <label for="precio" class="form-label">
            <i class="bi bi-clock me-1"></i>
            Precio de Entrada
        </label>
        <input type="text" 
               class="form-control" 
               id="precio" 
               name="precio"
               placeholder="Ej: 34-60 "
               value="<?php echo htmlspecialchars($_POST['precio'] ?? ''); ?>">
        <div class="form-text">Ingrese un valor fijo, un rango (ej: 34-60)</div>
    </div>
</div>

                    </div>

                    <!-- Horario y Maps -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="horario" class="form-label">
                                    <i class="bi bi-clock me-1"></i>
                                    Horario de Atención
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="horario" 
                                       name="horario"
                                       placeholder="Ej: 6:00 - 22:00"
                                       value="<?php echo htmlspecialchars($_POST['horario'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="url_maps" class="form-label">
                                    <i class="bi bi-map me-1"></i>
                                    URL de Google Maps
                                </label>
                                <input type="url" 
                                       class="form-control" 
                                       id="url_maps" 
                                       name="url_maps"
                                       placeholder="https://maps.google.com/?q=..."
                                       value="<?php echo htmlspecialchars($_POST['url_maps'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">
                                    <i class="bi bi-toggle-on me-1"></i>
                                    Estado del Recreo
                                </label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="activo" <?php echo ($_POST['estado'] ?? 'activo') === 'activo' ? 'selected' : ''; ?>>
                                        Activo
                                    </option>
                                    <option value="pendiente" <?php echo ($_POST['estado'] ?? '') === 'pendiente' ? 'selected' : ''; ?>>
                                        Pendiente
                                    </option>
                                    <option value="inactivo" <?php echo ($_POST['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>
                                        Inactivo
                                    </option>
                                </select>
                                <div class="form-text">Los recreos activos aparecerán en el listado público</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo BASE_URL; ?>index.php?action=recreos" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Crear Recreo
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
// Validación del formulario
document.getElementById('createRecreoForm').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const direccion = document.getElementById('direccion').value.trim();
    const ubicacion = document.getElementById('ubicacion').value;
    const precio = document.getElementById('precio').value;
    
    let errors = [];
    
    if (nombre === '') {
        errors.push('El nombre del recreo es obligatorio');
    }
    
    if (direccion === '') {
        errors.push('La dirección es obligatoria');
    }
    
    if (ubicacion === '') {
        errors.push('La ubicación es obligatoria');
    }
    
    if (precio < 0) {
        errors.push('El precio no puede ser negativo');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Por favor corrija los siguientes errores:\n\n' + errors.join('\n'));
    }
});

// Validar URL de Maps
document.getElementById('url_maps').addEventListener('blur', function() {
    const url = this.value.trim();
    if (url && !url.startsWith('http')) {
        this.value = 'https://' + url;
    }
});

// Auto-completar horarios comunes
document.getElementById('horario').addEventListener('focus', function() {
    if (this.value === '') {
        // Sugerencia de horarios comunes
        this.placeholder = 'Ej: 6:00 - 22:00, 8:00 - 18:00, 24 horas';
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>