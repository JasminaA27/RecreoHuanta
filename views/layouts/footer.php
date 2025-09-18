<?php if (isLoggedIn() && ($action ?? '') !== 'login'): ?>
    </main> <!-- Cierre del main-content -->
</div> <!-- Cierre del main-wrapper -->

<!-- Footer -->
<footer class="bg-white border-top mt-auto py-3" style="margin-left: var(--sidebar-width);">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-muted">
                    <small>&copy; <?php echo date('Y'); ?> Sistema de Gestión de Recreos. Todos los derechos reservados.</small>
                </p>
            </div>
            <div class="col-md-6 text-end">
                <p class="mb-0 text-muted">
                    <small>
                        <i class="bi bi-person-badge me-1"></i>
                        <?php echo $_SESSION['admin_nombre'] ?? 'Admin'; ?> | 
                        <i class="bi bi-clock me-1"></i>
                        <?php echo date('d/m/Y H:i'); ?>
                    </small>
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
@media (max-width: 768px) {
    footer {
        margin-left: 0 !important;
    }
}
</style>

<?php endif; ?>

<?php if (!isLoggedIn() || ($action ?? '') === 'login'): ?>
</div> <!-- Cierre del login-wrapper -->
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>

<!-- Scripts adicionales -->
<?php if (isset($additionalScripts)): ?>
    <?php echo $additionalScripts; ?>
<?php endif; ?>

<script>
// Animaciones de entrada para las cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card, .stats-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate-fade-in-up');
        }, index * 100);
    });
});

// Mejorar experiencia del dropdown de usuario
document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.getElementById('userDropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function() {
            const icon = this.querySelector('.bi-chevron-up, .bi-chevron-down');
            if (icon) {
                icon.classList.toggle('bi-chevron-up');
                icon.classList.toggle('bi-chevron-down');
            }
        });
    }
});
</script>

</body>
</html>