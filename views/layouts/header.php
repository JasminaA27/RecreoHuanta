<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Panel Administrativo - Sistema Recreos'; ?></title>
    
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="<?php echo $bodyClass ?? ''; ?>">

<?php if (isLoggedIn() && ($action ?? '') !== 'login'): ?>
<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-header">
        <h4>
            <i class="bi bi-house-heart me-2"></i>
            Sistema Recreos
        </h4>
    </div>
    
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?php echo ($action ?? '') === 'dashboard' ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>index.php?action=dashboard">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($action ?? '') === 'recreos' ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>index.php?action=recreos">
                <i class="bi bi-geo-alt"></i>
                <span>Recreos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($action ?? '') === 'usuarios' ? 'active' : ''; ?>" 
               href="<?php echo BASE_URL; ?>index.php?action=usuarios">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
            </a>
        </li>
        <li class="nav-item">
    <a class="nav-link <?php echo ($action ?? '') === 'cliente_api' ? 'active' : ''; ?>" 
       href="<?php echo BASE_URL; ?>index.php?action=cliente_api">
        <!-- Cliente -->
        <i class="bi bi-person"></i> 
        <span>Cliente</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?php echo ($action ?? '') === 'tokens' ? 'active' : ''; ?>" 
       href="<?php echo BASE_URL; ?>index.php?action=tokens">
        <!-- Tokens -->
        <i class="bi bi-key"></i> 
        <span>Tokens</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?php echo ($action ?? '') === 'request' ? 'active' : ''; ?>" 
       href="<?php echo BASE_URL; ?>index.php?action=request">
        <!-- Request -->
        <i class="bi bi-send"></i>
        <span>Request</span>
    </a>
</li>

    </ul>
    
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <?php echo strtoupper(substr($_SESSION['admin_nombre'] ?? 'A', 0, 1)); ?>
            </div>
            <div>
                <div style="font-weight: 600; font-size: 0.875rem;">
                    <?php echo $_SESSION['admin_nombre'] ?? 'Admin'; ?>
                </div>
                <div style="font-size: 0.75rem; opacity: 0.7;">
                    @<?php echo $_SESSION['admin_username'] ?? 'admin'; ?>
                </div>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="logout-btn">
            <i class="bi bi-box-arrow-right me-2"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
</nav>
<?php endif; ?>

<!-- Main Content -->
<?php if (isLoggedIn() && ($action ?? '') !== 'login'): ?>
<div class="main-content">
    <div class="content-wrapper">
        
        <?php 
        // Mostrar alertas
        $alert = getAlert();
        if ($alert): 
        ?>
        <div class="alert alert-<?php echo $alert['type'] === 'error' ? 'danger' : $alert['type']; ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-<?php echo $alert['type'] === 'success' ? 'check-circle' : ($alert['type'] === 'error' || $alert['type'] === 'danger' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
            <?php echo $alert['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

<?php else: ?>
<!-- Login Container -->
<?php 
// Mostrar alertas para login
$alert = getAlert();
if ($alert): 
?>
<div class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 9999;">
    <div class="alert alert-<?php echo $alert['type'] === 'error' ? 'danger' : $alert['type']; ?> alert-dismissible fade show shadow" role="alert">
        <i class="bi bi-<?php echo $alert['type'] === 'success' ? 'check-circle' : ($alert['type'] === 'error' || $alert['type'] === 'danger' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
        <?php echo $alert['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>