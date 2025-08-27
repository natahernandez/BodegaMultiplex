// JS global para vistas públicas de la tienda

// CSRF para AJAX
if (window.$) {
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
  });
}

// Alert utilitario
function shopAlert(type, message) {
  const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'x-circle');
  const html = `
    <div class="alert alert-${type} alert-dismissible fade show alert-floating" role="alert">
      <i class="bi-${icon} me-2"></i>${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
  document.body.insertAdjacentHTML('beforeend', html);
  setTimeout(() => { document.querySelectorAll('.alert-floating').forEach(el => el.remove()); }, 5000);
}

// Actualiza contador en header si el backend lo retorna
function updateCartCount(totalItems) {
  const el = document.getElementById('cartCount');
  if (el && typeof totalItems !== 'undefined') el.textContent = totalItems;
}

// Mostrar mensaje de éxito tras redirecciones con query success/product
document.addEventListener('DOMContentLoaded', function() {
  const urlParams = new URLSearchParams(window.location.search);
  const success = urlParams.get('success');
  const product = urlParams.get('product');
  if (success === '1' && product) {
    shopAlert('success', `¡${decodeURIComponent(product)} agregado al carrito exitosamente!`);
    const newUrl = window.location.pathname;
    window.history.replaceState({}, document.title, newUrl);
  }
});

// Navegación rápida de paginación (reutilizada)
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

window.shopAlert = shopAlert;
window.goToPage = goToPage;
window.updateCartCount = updateCartCount;


