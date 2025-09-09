/**
 * MENU RESTRUCTURE - COMPLETE REWRITE
 * Handles menu behavior for desktop and mobile
 */

(function() {
    'use strict';
    
    let isInitialized = false;
    
    function initMenu() {
        if (isInitialized) return;
        
        const body = document.body;
        const toggleButton = document.querySelector('.js-navbar-vertical-aside-toggle-invoker');
        const sidebar = document.querySelector('.js-navbar-vertical-aside');
        
        // Create mobile overlay if it doesn't exist
        let overlay = document.querySelector('.navbar-vertical-aside-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'navbar-vertical-aside-overlay';
            document.body.appendChild(overlay);
        }
        
        // Initialize based on screen size
        function initializeMenu() {
            const isDesktop = window.innerWidth >= 1200;
            
            if (isDesktop) {
                // DESKTOP MODE: Always show sidebar, hide toggle button
                body.classList.add('has-navbar-vertical-aside');
                body.classList.remove('navbar-vertical-aside-open');
                body.classList.remove('navbar-vertical-aside-closed-mode');
                body.classList.remove('navbar-vertical-aside-mini-mode');
                
                if (sidebar) {
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.style.visibility = 'visible';
                    sidebar.style.opacity = '1';
                    sidebar.style.display = 'block';
                }
                
                if (overlay) {
                    overlay.style.opacity = '0';
                    overlay.style.visibility = 'hidden';
                }
                
                // Hide toggle button completely on desktop
                if (toggleButton) {
                    toggleButton.style.display = 'none';
                    toggleButton.style.visibility = 'hidden';
                    toggleButton.style.opacity = '0';
                    toggleButton.style.pointerEvents = 'none';
                }
                
            } else {
                // MOBILE MODE: Hide sidebar by default, show toggle button
                body.classList.add('has-navbar-vertical-aside');
                body.classList.remove('navbar-vertical-aside-show-xl');
                
                if (sidebar) {
                    sidebar.style.transform = 'translateX(-100%)';
                    sidebar.style.visibility = 'hidden';
                    sidebar.style.opacity = '0';
                    sidebar.style.display = 'block';
                }
                
                if (overlay) {
                    overlay.style.opacity = '0';
                    overlay.style.visibility = 'hidden';
                }
                
                // Show toggle button on mobile
                if (toggleButton) {
                    toggleButton.style.display = 'block';
                    toggleButton.style.visibility = 'visible';
                    toggleButton.style.opacity = '1';
                    toggleButton.style.pointerEvents = 'auto';
                }
            }
        }
        
        // Mobile menu functions
        function openMobileMenu() {
            if (window.innerWidth < 1200) {
                body.classList.add('navbar-vertical-aside-open');
                if (overlay) {
                    overlay.style.opacity = '1';
                    overlay.style.visibility = 'visible';
                }
                localStorage.setItem('mobile-menu-open', 'true');
            }
        }
        
        function closeMobileMenu() {
            if (window.innerWidth < 1200) {
                body.classList.remove('navbar-vertical-aside-open');
                if (overlay) {
                    overlay.style.opacity = '0';
                    overlay.style.visibility = 'hidden';
                }
                localStorage.setItem('mobile-menu-open', 'false');
            }
        }
        
        function toggleMobileMenu() {
            if (window.innerWidth < 1200) {
                if (body.classList.contains('navbar-vertical-aside-open')) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            }
        }
        
        // Event listeners
        if (toggleButton) {
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMobileMenu();
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                closeMobileMenu();
            });
        }
        
        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth < 1200) {
                closeMobileMenu();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            initializeMenu();
            
            // Restore mobile menu state if needed
            if (window.innerWidth < 1200) {
                const wasOpen = localStorage.getItem('mobile-menu-open') === 'true';
                if (wasOpen) {
                    openMobileMenu();
                } else {
                    closeMobileMenu();
                }
            }
        });
        
        // Initialize on load
        initializeMenu();
        
        // Restore mobile menu state
        if (window.innerWidth < 1200) {
            const wasOpen = localStorage.getItem('mobile-menu-open') === 'true';
            if (wasOpen) {
                openMobileMenu();
            }
        }
        
        isInitialized = true;
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMenu);
    } else {
        initMenu();
    }
    
    // Make functions available globally
    window.initMenu = initMenu;
    window.openMobileMenu = function() {
        const body = document.body;
        if (window.innerWidth < 1200) {
            body.classList.add('navbar-vertical-aside-open');
            const overlay = document.querySelector('.navbar-vertical-aside-overlay');
            if (overlay) {
                overlay.style.opacity = '1';
                overlay.style.visibility = 'visible';
            }
        }
    };
    window.closeMobileMenu = function() {
        const body = document.body;
        if (window.innerWidth < 1200) {
            body.classList.remove('navbar-vertical-aside-open');
            const overlay = document.querySelector('.navbar-vertical-aside-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                overlay.style.visibility = 'hidden';
            }
        }
    };
    
})();
