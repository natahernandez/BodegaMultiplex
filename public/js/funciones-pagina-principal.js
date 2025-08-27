$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addToCart(evt, productId, productName, addToCartUrl) {
    const e = evt || window.event;
    const button = (e && (e.currentTarget || e.target)) || null;
    const originalText = button.innerHTML;

    button.innerHTML = '<i class="spinner-border spinner-border-sm"></i> Agregando...';
    button.disabled = true;

    $.ajax({
        url: addToCartUrl,
        method: 'POST',
        data: {
            producto_id: productId,
            cantidad: 1
        },
        success: function(response) {
            $('#cartCount').text(response.totalItems);
            showAlert('success', `${productName} agregado al carrito`);
            if (button) { button.innerHTML = originalText; button.disabled = false; }
        },
        error: function() {
            showAlert('danger', 'Error al agregar al carrito');
            if (button) { button.innerHTML = originalText; button.disabled = false; }
        }
    });
}

function showAlert(type, message) {
    const alertHtml = `
    <div class="alert alert-${type} alert-dismissible fade show position-fixed"
         style="top: 20px; right: 20px; z-index: 9999;" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
    $('body').append(alertHtml);
    setTimeout(() => $('.alert').fadeOut(), 5000);
}

// Función para navegación rápida de paginación
function goToPage(page, maxPage) {
    page = parseInt(page);
    if (page >= 1 && page <= maxPage) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('page', page);
        window.location.href = currentUrl.toString();
    } else {
        alert('Por favor ingresa un número de página válido entre 1 y ' + maxPage);
    }
}

// Mostrar mensaje de éxito si viene de agregar al carrito
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const product = urlParams.get('product');
    
    if (success === '1' && product) {
        showAlert('success', `¡${decodeURIComponent(product)} agregado al carrito exitosamente!`);
        
        // Limpiar la URL para que no se muestre el mensaje al recargar
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});