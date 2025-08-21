<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Carrito de Compras - Bodegas Multiplex</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/css/theme.min.css') }}">

  <style>
  body {
    font-family: 'Inter', sans-serif;
    background-color: #f8f9fa;
  }

  .cart-header {
    background: white;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 2rem;
  }

  /* Card general */
  .cart-content,
  .cart-summary {
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  }

  .cart-content .card-header,
  .cart-summary .card-header {
    background: #f9fafb;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
  }

  .cart-content .card-body,
  .cart-summary .card-body {
    padding: 1.5rem;
  }

  .cart-item-row {
    padding: 1rem 0;
    border-bottom: 1px solid #e9ecef;
  }

  .cart-item-row:last-child {
    border-bottom: none;
  }

  .product-image {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 0.5rem;
  }

  .quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .quantity-btn {
    width: 34px;
    height: 34px;
    border: 1px solid #dee2e6;
    background: white;
    border-radius: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
  }

  .quantity-btn:hover {
    background-color: #f1f5f9;
    border-color: #adb5bd;
  }

  .quantity-input {
    width: 60px;
    text-align: center;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 0.375rem 0.5rem;
  }

  .btn-checkout {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: all 0.15s ease;
  }

  .btn-checkout:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
  }

  .empty-cart {
    text-align: center;
    padding: 4rem 2rem;
    color: #6b7280;
    background: white;
    border-radius: 0.75rem;
    border: 1px solid #e9ecef;
  }

  .empty-cart-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1rem;
  }

  .product-image, 
.product-placeholder {
    width: 80px;         /* Ancho fijo */
    height: 80px;        /* Alto fijo */
    object-fit: contain; /* Evita deformación */
    background: #f8f9fa; /* Fondo consistente */
    border-radius: 8px;  /* Bordes redondeados */
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}
.product-placeholder i {
    font-size: 1.8rem;
}

</style>

</head>

<body>
<header class="cart-header">
  <div class="container py-3">
    <div class="row align-items-center">
      <div class="col">
        <h3 class="mb-0 text-primary align-items-right">
          <a class="navbar-brand p-0 m-0" href="/" style="display:inline-block;">
            Bodegas <span class="text-warning">Multiplex</span>
          </a>
        </h3>
      </div>
    </div>
  </div>
</header>


  <!-- Content -->
  <div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active">Carrito de compras</li>
          </ol>
        </nav>
        <h1 class="h3 mb-0">
          Carrito de compras
          @if($totalItems > 0)
            <span class="badge bg-soft-primary text-primary ms-2">{{ $totalItems }} artículos</span>
          @endif
        </h1>
      </div>
    </div>

    @if(!empty($carrito))
      <div class="row">
        <div class="col-lg-8 mb-4">
          <!-- Cart Items -->
          <div class="cart-content">
            <!-- Header -->
            <div class="card-header">
              <h4 class="card-header-title">
                Detalles del pedido 
                <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ count($carrito) }}</span>
              </h4>
            </div>
            
            <!-- Body -->
            <div class="card-body">
              @foreach($carrito as $productoId => $item)
                <!-- Cart Item -->
                <div class="d-flex cart-item-row py-3" data-product-id="{{ $productoId }}">
                  <div class="flex-shrink-0">
                    @if($item['imagen'])
                      <img class="product-image" src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}">
                    @else
                      <div class="product-placeholder">
                        <i class="bi-image text-muted"></i>
                      </div>
                    @endif
                  </div>

                  <div class="flex-grow-1 ms-3">
                    <div class="row">
                      <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="text-inherit mb-1">{{ $item['nombre'] }}</h5>
                        <div class="fs-6 text-body">
                          <span>Código:</span>
                          <span class="fw-semibold">{{ $item['codigo'] }}</span>
                        </div>
                      </div>
                      <!-- End Col -->

                      <div class="col col-md-2 align-self-center">
                        <h5 class="mb-0">Q{{ number_format($item['precio'], 2) }}</h5>
                      </div>
                      <!-- End Col -->

                      <div class="col col-md-2 align-self-center">
                        <div class="quantity-controls">
                          <button class="quantity-btn" onclick="updateQuantity({{ $productoId }}, {{ $item['cantidad'] - 1 }})">
                            <i class="bi-dash"></i>
                          </button>
                          <input type="number" class="quantity-input" value="{{ $item['cantidad'] }}" min="1" 
                                 onchange="updateQuantity({{ $productoId }}, this.value)">
                          <button class="quantity-btn" onclick="updateQuantity({{ $productoId }}, {{ $item['cantidad'] + 1 }})">
                            <i class="bi-plus"></i>
                          </button>
                        </div>
                      </div>
                      <!-- End Col -->

                      <div class="col col-md-2 align-self-center text-end">
                        <h5 class="mb-0">Q{{ number_format($item['subtotal'], 2) }}</h5>
                      </div>
                      <!-- End Col -->
                    </div>
                    <!-- End Row -->
                  </div>
                  
                  <!-- Remove Button -->
                  <div class="flex-shrink-0 ms-3 align-self-center">
                    <button type="button" class="btn btn-ghost-danger btn-icon btn-sm" 
                            onclick="removeFromCart({{ $productoId }}, '{{ $item['nombre'] }}')"
                            data-bs-toggle="tooltip" title="Eliminar del carrito">
                      <i class="bi-trash"></i>
                    </button>
                  </div>
                </div>

                @if(!$loop->last)
                  <hr class="my-0">
                @endif
              @endforeach

              <!-- Cart Totals -->
              <div class="row justify-content-md-end mt-4 pt-3 border-top">
                <div class="col-md-8 col-lg-7">
                  <dl class="row text-sm-end">
                    <dt class="col-sm-6">Subtotal:</dt>
                    <dd class="col-sm-6">Q{{ number_format($total, 2) }}</dd>
                    <dt class="col-sm-6 border-top pt-2"><strong>Total:</strong></dt>
                    <dd class="col-sm-6 border-top pt-2">
                      <strong>Q{{ number_format($total, 2) }}</strong>
                    </dd>
                  </dl>
                  
                </div>
              </div>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="/" class="btn btn-outline-secondary">
              <i class="bi-arrow-left me-1"></i>Seguir comprando
            </a>
            
            <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger" onclick="clearCart()">
              <i class="bi-trash me-1"></i>Vaciar carrito
            </button>
              @auth
                <a href="{{ route('shop.checkout') }}" class="btn btn-checkout text-white">
                  <i class="bi-credit-card me-1"></i>Proceder al pago
                </a>
              @else
                <a href="{{ route('login') }}" class="btn btn-checkout text-white">
                  <i class="bi-person-lock me-1"></i>Iniciar sesión para continuar
                </a>
              @endauth
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <!-- Order Summary -->
          <div class="cart-summary">
            <div class="card-header">
              <h4 class="card-header-title">Resumen del pedido</h4>
            </div>
            
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>Artículos ({{ $totalItems }}):</span>
                  <span>Q{{ number_format($total, 2) }}</span>
                </div>
                
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                  <span>Total:</span>
                  <span>Q{{ number_format($total, 2) }}</span>
                </div>
              </div>
              
              @if($total < 200)
                <div class="alert alert-soft-info mb-3">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <i class="bi-info-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-2">
                      <small>Agrega Q{{ number_format(200 - $total, 2) }} más para envío gratis</small>
                    </div>
                  </div>
                </div>
              @endif
              
              
              <!-- Security Badges -->
              <div class="row text-center mt-4 pt-3 border-top">
                <div class="col-4">
                  <i class="bi-shield-check text-success fs-4 d-block mb-1"></i>
                  <small class="text-muted">Compra Segura</small>
                </div>
                <div class="col-4">
                  <i class="bi-truck text-primary fs-4 d-block mb-1"></i>
                  <small class="text-muted">Envío Rápido</small>
                </div>
                <div class="col-4">
                  <i class="bi-arrow-clockwise text-info fs-4 d-block mb-1"></i>
                  <small class="text-muted">Devoluciones</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @else
      <!-- Empty Cart -->
      <div class="empty-cart">
        <i class="bi-cart-x empty-cart-icon d-block"></i>
        <h3 class="mb-3">Tu carrito está vacío</h3>
        <p class="mb-4">Parece que no has agregado ningún producto a tu carrito todavía.</p>
        <a href="/" class="btn btn-primary">
          <i class="bi-bag me-1"></i>Comenzar a comprar
        </a>
      </div>
    @endif
  </div>

  <!-- Scripts -->
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Configuración CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Actualizar cantidad
    function updateQuantity(productId, newQuantity) {
        if (newQuantity < 1) {
            removeFromCart(productId);
            return;
        }

        $.ajax({
            url: '{{ route("shop.cart.update") }}',
            method: 'PUT',
            data: {
                producto_id: productId,
                cantidad: newQuantity
            },
            success: function(response) {
                if (window.Swal) {
                  Swal.fire({ icon: 'success', title: 'Cantidad actualizada', timer: 1000, showConfirmButton: false })
                    .then(() => location.reload());
                } else {
                  location.reload();
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (window.Swal) {
                  Swal.fire({ icon: 'error', title: response.error || 'Error al actualizar el carrito' });
                } else {
                  showAlert('danger', response.error || 'Error al actualizar el carrito');
                }
            }
        });
    }

    // Eliminar del carrito
    function removeFromCart(productId, productName = '') {
        const proceed = () => $.ajax({
            url: '{{ route("shop.cart.remove") }}',
            method: 'DELETE',
            data: {
                producto_id: productId
            },
            success: function(response) {
                if (window.Swal) {
                  Swal.fire({ icon: 'success', title: 'Producto eliminado', timer: 1000, showConfirmButton: false })
                    .then(() => location.reload());
                } else {
                  location.reload();
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (window.Swal) {
                  Swal.fire({ icon: 'error', title: response.error || 'Error al eliminar del carrito' });
                } else {
                  showAlert('danger', response.error || 'Error al eliminar del carrito');
                }
            }
        });

        if (productName && window.Swal) {
            Swal.fire({
              icon: 'question',
              title: `¿Eliminar "${productName}" del carrito?`,
              showCancelButton: true,
              confirmButtonText: 'Sí, eliminar',
              cancelButtonText: 'Cancelar'
            }).then(r => { if (r.isConfirmed) proceed(); });
        } else if (productName) {
            if (confirm(`¿Eliminar "${productName}" del carrito?`)) proceed();
        } else {
            proceed();
        }
    }

    function clearCart() {
      const exec = () => $.ajax({
        url: "{{ route('shop.cart.clear') }}",
        type: "POST",
        data: { _token: "{{ csrf_token() }}" },
        success: function() {
          if (window.Swal) {
            Swal.fire({ icon: 'success', title: 'Carrito vaciado', timer: 1000, showConfirmButton: false })
              .then(() => location.reload());
          } else {
            location.reload();
          }
        },
        error: function() {
          if (window.Swal) Swal.fire({ icon: 'error', title: 'Error al vaciar el carrito' });
          else alert('Error al vaciar el carrito');
        }
      });

      if (window.Swal) {
        Swal.fire({
          icon: 'warning',
          title: '¿Vaciar carrito?',
          text: 'Esta acción eliminará todos los productos del carrito.',
          showCancelButton: true,
          confirmButtonText: 'Sí, vaciar',
          cancelButtonText: 'Cancelar'
        }).then(r => { if (r.isConfirmed) exec(); });
      } else {
        if (confirm('¿Vaciar carrito?')) exec();
      }
    }

    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }

    // Initialize tooltips
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
  </script>
</body>
</html> 