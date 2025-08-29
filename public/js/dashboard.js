/**
 * Dashboard JavaScript
 * Funcionalidades específicas para el panel administrativo
 */

// FORCE IMMEDIATE EXECUTION
(function() {
    // Asegurar clases del body inmediatamente
    if (!document.body.classList.contains('has-navbar-vertical-aside')) {
        document.body.classList.add('has-navbar-vertical-aside');
    }
    
    if (window.innerWidth >= 1200) {
        document.body.classList.add('navbar-vertical-aside-show-xl');
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializar sidebar toggle INMEDIATAMENTE
    initSidebarToggle();
    
    // Inicializar tooltips
    initTooltips();
    
    // Inicializar dropdowns
    initDropdowns();
    
    // Auto-ocultar alertas
    autoHideAlerts();
    
    // Force layout fix
    setTimeout(function() {
        window.dispatchEvent(new Event('resize'));
    }, 100);
    
});

/**
 * Inicializar toggle del sidebar
 */
function initSidebarToggle() {
    const toggleButton = document.querySelector('.js-navbar-vertical-aside-toggle-invoker');
    const sidebar = document.querySelector('.js-navbar-vertical-aside');
    const body = document.body;
    
    if (toggleButton && sidebar) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            body.classList.toggle('navbar-vertical-aside-mini');
            
            // Trigger resize event para que otros componentes se ajusten
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 300);
            
            // Guardar estado en localStorage
            const isMinified = body.classList.contains('navbar-vertical-aside-mini');
            localStorage.setItem('sidebar-mini', isMinified);
        });
        
        // Restaurar estado del sidebar
        const savedState = localStorage.getItem('sidebar-mini');
        if (savedState === 'true') {
            body.classList.add('navbar-vertical-aside-mini');
        }
        
        // Asegurar que el body tenga la clase correcta
        if (!body.classList.contains('has-navbar-vertical-aside')) {
            body.classList.add('has-navbar-vertical-aside');
        }
        
        // Ajustar en resize de ventana
        window.addEventListener('resize', function() {
            // En pantallas pequeñas, forzar el sidebar a estar oculto
            if (window.innerWidth < 1200) {
                body.classList.remove('navbar-vertical-aside-show-xl');
            } else {
                body.classList.add('navbar-vertical-aside-show-xl');
            }
        });
        
        // Ejecutar una vez al cargar
        window.dispatchEvent(new Event('resize'));
    }
}

/**
 * Inicializar tooltips de Bootstrap
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Inicializar dropdowns de Bootstrap
 */
function initDropdowns() {
    const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
}

/**
 * Auto-ocultar alertas después de unos segundos
 */
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

/**
 * Mostrar loading state en botones
 */
function showButtonLoading(button, loadingText = 'Cargando...') {
    const originalText = button.innerHTML;
    button.setAttribute('data-original-text', originalText);
    button.disabled = true;
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        ${loadingText}
    `;
}

/**
 * Restaurar estado normal del botón
 */
function hideButtonLoading(button) {
    const originalText = button.getAttribute('data-original-text');
    if (originalText) {
        button.innerHTML = originalText;
        button.disabled = false;
        button.removeAttribute('data-original-text');
    }
}

/**
 * Confirmar acción con SweetAlert2
 */
function confirmAction(title, text, confirmText = 'Sí, continuar', cancelText = 'Cancelar') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#377dff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true
    });
}

/**
 * Mostrar notificación de éxito
 */
function showSuccess(message, title = '¡Éxito!') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

/**
 * Mostrar notificación de error
 */
function showError(message, title = 'Error') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonColor: '#377dff'
    });
}

/**
 * Mostrar notificación de información
 */
function showInfo(message, title = 'Información') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        confirmButtonColor: '#377dff'
    });
}

/**
 * Formatear números como moneda
 */
function formatCurrency(amount, currency = 'Q') {
    return currency + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

/**
 * Copiar texto al portapapeles
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showSuccess('Texto copiado al portapapeles');
    }, function(err) {
        console.error('Error al copiar: ', err);
        showError('No se pudo copiar el texto');
    });
}

/**
 * Validar formulario antes del envío
 */
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(function(field) {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

/**
 * Limpiar formulario
 */
function clearForm(form) {
    form.reset();
    form.querySelectorAll('.is-invalid').forEach(function(field) {
        field.classList.remove('is-invalid');
    });
    form.querySelectorAll('.is-valid').forEach(function(field) {
        field.classList.remove('is-valid');
    });
}

// Hacer funciones disponibles globalmente
window.showButtonLoading = showButtonLoading;
window.hideButtonLoading = hideButtonLoading;
window.confirmAction = confirmAction;
window.showSuccess = showSuccess;
window.showError = showError;
window.showInfo = showInfo;
window.formatCurrency = formatCurrency;
window.copyToClipboard = copyToClipboard;
window.validateForm = validateForm;
window.clearForm = clearForm;
