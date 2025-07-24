<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $producto->nombre }} - Bodegas Multiplex</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

  <style>
    .product-gallery {
      position: sticky;
      top: 20px;
    }
    
    .main-image {
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }
    
    .thumbnail {
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }
    
    .thumbnail:hover, .thumbnail.active {
      border-color: #007bff;
      transform: scale(1.05);
    }
    
    .product-info {
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      padding: 30px;
    }
    
    .price-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 25px;
    }
    
    .quantity-selector {
      border-radius: 25px;
      overflow: hidden;
    }
    
    .btn-add-cart {
      background: linear-gradient(45deg, #28a745, #20c997);
      border: none;
      border-radius: 25px;
      padding: 15px 30px;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
    }
    
    .btn-add-cart:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }
    
    .related-products .card {
      transition: all 0.3s ease;
      border: none;
      box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }
    
    .related-products .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .badge-stock {
      position: absolute;
      top: 15px;
      left: 15px;
      z-index: 10;
    }
    
    .product-features {
      background: #f8f9fa;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 25px;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
      <a class="navbar-brand" href="/">
        <h3 class="mb-0 text-primary fw-bold">Bodegas <span class="text-warning">Multiplex</span></h3>
      </a>
      
      <div class="d-flex">
        <a href="/" class="btn btn-outline-primary me-2">
          <i class="bi-arrow-left me-1"></i>Volver a la Tienda
        </a>
        <a href="{{ route('shop.cart') }}" class="btn btn-outline-success position-relative">
          <i class="bi-cart3 me-1"></i>Carrito
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">
            {{ session('carrito') ? array_sum(array_column(session('carrito'), 'cantidad')) : 0 }}
          </span>
        </a>
      </div>
    </div>
  </header>

  <main class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Inicio</a></li>
        <li class="breadcrumb-item"><a href="/?categoria={{ $producto->categoria }}">{{ $producto->categoria }}</a></li>
        <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
      </ol>
    </nav>

    <div class="row">
      <!-- Product Gallery -->
      <div class="col-lg-6">
        <div class="product-gallery">
          @php
            $imagenes = $producto->imagenes;
            $imagenPrincipal = $imagenes->where('es_principal', true)->first() ?? $imagenes->first();
          @endphp
          
          <!-- Main Image -->
          <div class="mb-4 position-relative">
            @if($producto->stock_actual <= 0)
              <span class="badge bg-danger badge-stock">Agotado</span>
            @elseif($producto->stock_actual <= $producto->stock_minimo)
              <span class="badge bg-warning text-dark badge-stock">Stock Bajo</span>
            @else
              <span class="badge bg-success badge-stock">Disponible</span>
            @endif
            
            @if($producto->imagen_principal_url)
              <img id="mainImage" src="{{ $producto->imagen_principal_url }}" 
                   alt="{{ $producto->nombre }}" class="img-fluid main-image w-100" 
                   style="height: 400px; object-fit: contain; background: #f8f9fa;">
            @else
              <div class="main-image bg-light d-flex align-items-center justify-content-center w-100" style="height: 400px;">
                <i class="bi-image text-muted" style="font-size: 5rem;"></i>
              </div>
            @endif
          </div>
          
          <!-- Thumbnails -->
          @if($imagenes->count() > 1)
            <div class="row g-2">
              @foreach($imagenes as $imagen)
                <div class="col-3">
                  <img src="{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}" 
                       alt="{{ $producto->nombre }}" 
                       class="img-fluid thumbnail w-100 {{ $imagen->es_principal ? 'active' : '' }}" 
                       style="height: 80px; object-fit: contain; background: #f8f9fa;"
                       onclick="changeMainImage('{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}', this)">
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
      
      <!-- Product Info -->
      <div class="col-lg-6">
        <div class="product-info">
          <!-- Category Badge -->
          <span class="badge bg-primary mb-3">{{ $producto->categoria }}</span>
          
          <!-- Product Name -->
          <h1 class="display-5 fw-bold mb-3">{{ $producto->nombre }}</h1>
          
          <!-- Brand -->
          @if($producto->marca)
            <p class="text-muted mb-3">
              <i class="bi-tag me-2"></i>Marca: <strong>{{ $producto->marca }}</strong>
            </p>
          @endif
          
          <!-- Price Section -->
          <div class="price-section">
            <div class="row align-items-center">
              <div class="col">
                <h3 class="mb-0">Q{{ number_format($producto->precio_venta, 2) }}</h3>
                @if($producto->precio_mayoreo && $producto->precio_mayoreo < $producto->precio_venta)
                  <small class="opacity-75">Precio mayoreo: Q{{ number_format($producto->precio_mayoreo, 2) }}</small>
                @endif
              </div>
              <div class="col-auto">
                <span class="fs-6">{{ $producto->unidad_medida }}</span>
              </div>
            </div>
          </div>
          
          <!-- Description -->
          @if($producto->descripcion)
            <div class="mb-4">
              <h5 class="fw-bold mb-3">Descripción</h5>
              <p class="text-muted">{{ $producto->descripcion }}</p>
            </div>
          @endif
          
          <!-- Product Features -->
          <div class="product-features">
            <div class="row g-3">
              <div class="col-6">
                <div class="d-flex align-items-center">
                  <i class="bi-box me-2 text-primary"></i>
                  <div>
                    <small class="text-muted d-block">Código</small>
                    <strong>{{ $producto->codigo_interno }}</strong>
                  </div>
                </div>
              </div>
              
              <div class="col-6">
                <div class="d-flex align-items-center">
                  <i class="bi-archive me-2 text-success"></i>
                  <div>
                    <small class="text-muted d-block">Stock</small>
                    <strong>{{ $producto->stock_actual }} {{ $producto->unidad_medida }}</strong>
                  </div>
                </div>
              </div>
              
              @if($producto->codigo_barras)
                <div class="col-6">
                  <div class="d-flex align-items-center">
                    <i class="bi-upc me-2 text-info"></i>
                    <div>
                      <small class="text-muted d-block">Código de Barras</small>
                      <strong>{{ $producto->codigo_barras }}</strong>
                    </div>
                  </div>
                </div>
              @endif
              
              @if($producto->peso)
                <div class="col-6">
                  <div class="d-flex align-items-center">
                    <i class="bi-speedometer me-2 text-warning"></i>
                    <div>
                      <small class="text-muted d-block">Peso</small>
                      <strong>{{ $producto->peso }}g</strong>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>
          
          <!-- Add to Cart Section -->
          @if($producto->stock_actual > 0)
            <div class="row align-items-center mb-4">
              <div class="col-md-4">
                <label class="form-label fw-semibold">Cantidad</label>
                <div class="input-group quantity-selector">
                  <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">
                    <i class="bi-dash"></i>
                  </button>
                  <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="{{ $producto->stock_actual }}">
                  <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">
                    <i class="bi-plus"></i>
                  </button>
                </div>
              </div>
              
              <div class="col-md-8">
                <button class="btn btn-add-cart text-white w-100" onclick="addToCart()">
                  <i class="bi-cart-plus me-2"></i>Agregar al Carrito
                </button>
              </div>
            </div>
          @else
            <div class="alert alert-danger">
              <i class="bi-exclamation-triangle me-2"></i>
              Producto agotado - Consulta disponibilidad
            </div>
          @endif
          
          <!-- Benefits -->
          <div class="row text-center">
            <div class="col-4">
              <i class="bi-truck text-primary fs-4 d-block mb-2"></i>
              <small class="text-muted">Envío Gratis<br>en compras +Q200</small>
            </div>
            <div class="col-4">
              <i class="bi-shield-check text-success fs-4 d-block mb-2"></i>
              <small class="text-muted">Compra<br>Segura</small>
            </div>
            <div class="col-4">
              <i class="bi-arrow-clockwise text-info fs-4 d-block mb-2"></i>
              <small class="text-muted">Devoluciones<br>24 horas</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Related Products -->
    @if($productosRelacionados->count() > 0)
      <div class="mt-5">
        <h3 class="fw-bold mb-4">Productos Relacionados</h3>
        <div class="row related-products">
          @foreach($productosRelacionados as $relacionado)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
              <div class="card h-100">
                <div class="position-relative">
                  @if($relacionado->imagen_principal_url)
                    <img src="{{ $relacionado->imagen_principal_url }}" 
                         class="card-img-top" alt="{{ $relacionado->nombre }}" 
                         style="height: 200px; object-fit: contain; background: #f8f9fa;">
                  @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                      <i class="bi-image text-muted fs-3"></i>
                    </div>
                  @endif
                  
                  <span class="position-absolute top-0 end-0 m-2 badge bg-primary">
                    Q{{ number_format($relacionado->precio_venta, 2) }}
                  </span>
                </div>
                
                <div class="card-body">
                  <h6 class="card-title fw-bold">{{ $relacionado->nombre }}</h6>
                  <p class="card-text text-muted small">{{ Str::limit($relacionado->descripcion, 60) }}</p>
                </div>
                
                <div class="card-footer bg-transparent">
                  <div class="d-grid">
                    <a href="{{ route('shop.product.show', $relacionado) }}" class="btn btn-outline-primary btn-sm">
                      <i class="bi-eye me-1"></i>Ver Producto
                    </a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </main>

  <!-- Scripts -->
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

  <script>
    // Configuración CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Cambiar imagen principal
    function changeMainImage(imageSrc, thumbnail) {
        $('#mainImage').attr('src', imageSrc);
        $('.thumbnail').removeClass('active');
        $(thumbnail).addClass('active');
    }

    // Cambiar cantidad
    function changeQuantity(delta) {
        const quantityInput = $('#quantity');
        let currentValue = parseInt(quantityInput.val());
        let newValue = currentValue + delta;
        
        const min = parseInt(quantityInput.attr('min'));
        const max = parseInt(quantityInput.attr('max'));
        
        if (newValue >= min && newValue <= max) {
            quantityInput.val(newValue);
        }
    }

    // Agregar al carrito
    function addToCart() {
        const quantity = parseInt($('#quantity').val());
        const button = $('.btn-add-cart');
        const originalText = button.html();
        
        // Cambiar botón a estado de carga
        button.html('<i class="spinner-border spinner-border-sm me-2"></i>Agregando...').prop('disabled', true);

        $.ajax({
            url: '{{ route("shop.cart.add") }}',
            method: 'POST',
            data: {
                producto_id: {{ $producto->id }},
                cantidad: quantity
            },
            success: function(response) {
                // Actualizar contador del carrito
                $('#cartCount').text(response.totalItems);
                
                // Mostrar mensaje de éxito
                showAlert('success', `{{ $producto->nombre }} agregado al carrito exitosamente`);
                
                // Restaurar botón
                button.html(originalText).prop('disabled', false);
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('danger', response.error || 'Error al agregar al carrito');
                
                // Restaurar botón
                button.html(originalText).prop('disabled', false);
            }
        });
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

    // Actualizar contador del carrito al cargar la página
    $(document).ready(function() {
        @if(session('carrito'))
            const cartCount = {{ array_sum(array_column(session('carrito'), 'cantidad')) }};
            $('#cartCount').text(cartCount);
        @endif
    });
  </script>
</body>
</html> 