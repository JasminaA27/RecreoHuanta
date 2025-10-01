<?php
$pageTitle = 'Detalles de Solicitud API - Sistema Recreos';
$action = 'count_request';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-graph-up me-2"></i>
                Detalles de Solicitud API
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=count_request" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Información de la Solicitud</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ID de Solicitud</label>
                        <p><?php echo $request['id']; ?></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha y Hora</label>
                        <p><?php echo date('d/m/Y H:i:s', strtotime($request['fecha'])); ?></p>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-bold">Tipo de Solicitud</label>
                        <p>
                            <?php if (!empty($request['tipo'])): ?>
                                <span class="badge bg-info fs-6"><?php echo htmlspecialchars($request['tipo']); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary fs-6">No especificado</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Token</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Token</label>
                    <div class="card bg-dark text-light">
                        <div class="card-body p-2">
                            <code class="small"><?php echo htmlspecialchars($request['token']); ?></code>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Cliente</label>
                    <p><?php echo htmlspecialchars($request['nombre'] . ' ' . $request['apellido']); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">DNI</label>
                    <p><?php echo htmlspecialchars($request['dni']); ?></p>
                </div>
                
                <?php if (!empty($request['telefono'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Teléfono</label>
                    <p><?php echo htmlspecialchars($request['telefono']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($request['correo'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <p><?php echo htmlspecialchars($request['correo']); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <form method="POST" 
                      action="<?php echo BASE_URL; ?>index.php?action=count_request&method=delete" 
                      onsubmit="return confirm('¿Estás seguro de eliminar esta solicitud?')"
                      class="d-grid">
                    <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>Eliminar Solicitud
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>