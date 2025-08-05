// Toggle status function
function toggleStatus(newStatus) {
    const action = newStatus ? 'activar' : 'desactivar';
    if (confirm(`¿Está seguro de ${action} este producto?`)) {
        // Make AJAX call to update status
        $.ajax({
            url: `/productos/{{ $producto->id }}`,
            method: 'PUT',
            data: {
                activo: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Error al cambiar el estado del producto');
            }
        });
    }
}

// Funciones para galería de imágenes
function cambiarImagenPrincipal(nuevaImagenUrl, elemento) {
    document.getElementById('imagenPrincipal').src = nuevaImagenUrl;
    const modalImage = document.getElementById('imagenModalAmpliada');
    if (modalImage) {
        modalImage.src = nuevaImagenUrl;
    }
    
    // Remover bordes de otras miniaturas y agregar al elemento actual
    document.querySelectorAll('.miniatura').forEach(img => {
        img.classList.remove('border-primary');
        img.style.borderWidth = '';
    });
    
    if (elemento) {
        elemento.classList.add('border-primary');
        elemento.style.borderWidth = '2px';
    }
}

function cambiarImagenModal(nuevaImagenUrl) {
    document.getElementById('imagenModalAmpliada').src = nuevaImagenUrl;
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Hover effects are now handled by CSS
}); 