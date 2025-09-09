/**
 * MENU FIX - SOLO PARA MÓVIL, DESKTOP ES PURO CSS
 */

(function() {
    'use strict';
    
    function initMobileMenu() {
        // Solo ejecutar en móvil
        if (window.innerWidth >= 1200) {
            return; // Desktop se maneja con CSS puro
        }
        
        const body = document.body;
        const toggleButton = document.querySelector('header .js-navbar-vertical-aside-toggle-invoker');
        
        // Crear overlay si no existe
        let overlay = document.querySelector('.navbar-vertical-aside-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'navbar-vertical-aside-overlay';
            document.body.appendChild(overlay);
        }
        
        function closeMobileMenu() {
            body.classList.remove('navbar-vertical-aside-open');
        }
        
        function openMobileMenu() {
            body.classList.add('navbar-vertical-aside-open');
        }
        
        function toggleMobileMenu() {
            if (body.classList.contains('navbar-vertical-aside-open')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        }
        
        // Event listeners solo para móvil
        if (toggleButton) {
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMobileMenu();
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', closeMobileMenu);
        }
        
        // Cerrar con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileMenu();
            }
        });
    }
    
    // Solo inicializar en móvil
    function init() {
        if (window.innerWidth < 1200) {
            initMobileMenu();
        }
    }
    
    // Inicializar dropdowns de Bootstrap
    function initDropdowns() {
        const dropdownToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
        dropdownToggles.forEach(function(toggle) {
            // Asegurar que Bootstrap maneje los collapse correctamente
            if (!toggle.hasAttribute('data-initialized')) {
                toggle.setAttribute('data-initialized', 'true');
            }
        });
    }
    
    // Inicializar
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            init();
            initDropdowns();
        });
    } else {
        init();
        initDropdowns();
    }
    
    // Re-inicializar solo si cambia a móvil
    window.addEventListener('resize', function() {
        if (window.innerWidth < 1200) {
            initMobileMenu();
        }
    });
    
})();