/**
 * Sistema de Recreos - JavaScript Principal
 * Funciones generales del sistema
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeSystem();
});

/**
 * Inicializar el sistema
 */
function initializeSystem() {
    initializeTooltips();
    initializeAlerts();
    initializeFormValidation();
    initializeTableSearch();
    initializeAnimations();
    initializeUtilities();
    
    console.log('Sistema de Recreos inicializado correctamente');
}

/**
 * Inicializar tooltips de Bootstrap
 */
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Manejo de alertas
 */
function initializeAlerts() {
    // Auto-cerrar alertas después de 8 segundos
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        });
    }, 8000);

    // Animación para alertas
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach((alert, index) => {
        alert.style.animationDelay = (index * 0.1) + 's';
        alert.classList.add('fade-in');
    });
}

/**
 * Validación de formularios
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Scroll al primer campo con error
                const firstInvalidField = form.querySelector('.form-control:invalid, .form-select:invalid');
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalidField.focus();
                }
                
                showNotification('Por favor completa todos los campos requeridos', 'warning');
            }
            form.classList.add('was-validated');
        });

        // Validación en tiempo real
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                if (form.classList.contains('was-validated')) {
                    input.classList.toggle('is-valid', input.checkValidity());
                    input.classList.toggle('is-invalid', !input.checkValidity());
                }
            });
        });
    });
}

/**
 * Búsqueda en tablas
 */
function initializeTableSearch() {
    const searchInput = document.querySelector('#table-search');
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                let visibleCount = 0;
                
                rows.forEach(function(row) {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(filter);
                    row.style.display = isVisible ? '' : 'none';
                    
                    if (isVisible) visibleCount++;
                });
                
                // Actualizar contador de resultados
                updateSearchResults(visibleCount, rows.length);
                
            }, 300); // Delay para evitar búsquedas excesivas
        });

        // Limpiar búsqueda con Escape
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.dispatchEvent(new Event('keyup'));
            }
        });
    }
}

/**
 * Actualizar resultados de búsqueda
 */
function updateSearchResults(visible, total) {
    let counter = document.querySelector('.search-results-counter');
    if (!counter) {
        counter = document.createElement('small');
        counter.className = 'search-results-counter text-muted';
        const searchInput = document.querySelector('#table-search');
        if (searchInput && searchInput.parentNode) {
            searchInput.parentNode.insertAdjacentElement('afterend', counter);
        }
    }
    
    if (visible < total) {
        counter.textContent = `Mostrando ${visible} de ${total} registros`;
        counter.style.display = 'block';
    } else {
        counter.style.display = 'none';
    }
}

/**
 * Animaciones
 */
function initializeAnimations() {
    // Animaciones para cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.05) + 's';
        card.classList.add('fade-in');
    });

    // Animaciones para botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Observador para animaciones al hacer scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('slide-in-right');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observar elementos con la clase animate-on-scroll
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
}

/**
 * Utilidades generales
 */
function initializeUtilities() {
    // Click en elementos con data-href
    document.querySelectorAll('[data-href]').forEach(element => {
        element.style.cursor = 'pointer';
        element.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });

    // Auto-resize para textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Confirmación para enlaces de eliminación
    document.querySelectorAll('.btn-delete, [data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            const message = this.dataset.confirm || '¿Está seguro de que desea realizar esta acción?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
}

/**
 * Mostrar notificaciones
 */
function showNotification(message, type = 'info', duration = 5000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = `
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 300px; 
        max-width: 500px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    `;
    
    const iconClass = {
        'success': 'fa-check-circle',
        'danger': 'fa-exclamation-triangle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    
    alertDiv.innerHTML = `
        <i class="fas ${iconClass[type] || iconClass.info} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto-remover después del tiempo especificado
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, duration);

    return alertDiv;
}

/**
 * Confirmar eliminación
 */
function confirmarEliminacion(id, tipo = 'registro', url = '') {
    const swalConfig = {
        title: '¿Está seguro?',
        text: `Esta acción cambiará el estado del ${tipo} a inactivo`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    };

    // Si SweetAlert2 está disponible, usarlo
    if (typeof Swal !== 'undefined') {
        Swal.fire(swalConfig).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url || `index.php?action=delete&id=${id}`;
            }
        });
    } else {
        // Fallback a confirm nativo
        if (confirm(`¿Está seguro de que desea eliminar este ${tipo}? Esta acción cambiará su estado a inactivo.`)) {
            window.location.href = url || `index.php?action=delete&id=${id}`;
        }
    }
}

/**
 * Formatear números como moneda
 */
function formatCurrency(amount, currency = 'PEN') {
    return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

/**
 * Validar URL
 */
function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

/**
 * Copiar al portapapeles
 */
function copyToClipboard(text, successMessage = 'Copiado al portapapeles') {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification(successMessage, 'success', 2000);
        }).catch(() => {
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

/**
 * Fallback para copiar al portapapeles
 */
function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showNotification('Copiado al portapapeles', 'success', 2000);
    } catch (err) {
        showNotification('Error al copiar', 'danger', 2000);
    }
    
    document.body.removeChild(textArea);
}

/**
 * Loading state para botones
 */
function setButtonLoading(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
        button.classList.add('loading');
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText || 'Enviar';
        button.classList.remove('loading');
    }
}

/**
 * Previsualizar imagen
 */
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Debounce function
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

/**
 * Throttle function
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Obtener parámetros de URL
 */
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

/**
 * Scroll suave a elemento
 */
function scrollToElement(elementId, offset = 0) {
    const element = document.getElementById(elementId);
    if (element) {
        const elementPosition = element.offsetTop - offset;
        window.scrollTo({
            top: elementPosition,
            behavior: 'smooth'
        });
    }
}

/**
 * Manejar errores AJAX
 */
function handleAjaxError(xhr, status, error) {
    console.error('Error AJAX:', status, error);
    
    let message = 'Ha ocurrido un error inesperado';
    
    if (xhr.status === 404) {
        message = 'Recurso no encontrado';
    } else if (xhr.status === 500) {
        message = 'Error interno del servidor';
    } else if (xhr.status === 403) {
        message = 'No tiene permisos para realizar esta acción';
    }
    
    showNotification(message, 'danger');
}

/**
 * Validar formulario antes del envío
 */
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
    });
    
    return isValid;
}

// Exportar funciones para uso global
window.sistemRecreosUtils = {
    showNotification,
    confirmarEliminacion,
    formatCurrency,
    isValidUrl,
    copyToClipboard,
    setButtonLoading,
    previewImage,
    debounce,
    throttle,
    getUrlParameter,
    scrollToElement,
    handleAjaxError,
    validateForm
}

;