<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Finalizar Compra - Bodegas Multiplex</title>

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('front-dashboard-v2.1.1/dist/assets/css/theme.min.css') }}">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
    }
    
    .checkout-header {
      background: white;
      border-bottom: 1px solid #e9ecef;
      margin-bottom: 2rem;
    }
    
    .checkout-content {
      background: white;
      border-radius: 0.5rem;
      border: 1px solid #e9ecef;
      padding: 2rem;
    }
    
    .btn-checkout {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 600;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }
    
    .btn-checkout:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header class="checkout-header py-3">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <h2 class="h4 mb-0 text-primary me-3">
            <i class="bi-shop me-2"></i>Bodegas Multiplex
          </h2>
          <span class="text-muted">|</span>
          <span class="ms-3 text-muted">Checkout Seguro</span>
        </div>
        <div class="d-flex align-items-center">
          @auth
            <span class="text-muted me-3">{{ Auth::user()->name }}</span>
            <a href="/" class="btn btn-outline-primary btn-sm">
              <i class="bi-arrow-left me-1"></i>Seguir Comprando
            </a>
          @endauth
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
<div class="container">
  <!-- Header -->
  <div class="row">
    <div class="col-12">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Inicio</a></li>
          <li class="breadcrumb-item"><a href="{{ route('shop.cart') }}">Carrito</a></li>
          <li class="breadcrumb-item active">Checkout</li>
        </ol>
      </nav>
      
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Finalizar Compra</h1>
        <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary">
          <i class="bi-arrow-left"></i> Volver al Carrito
        </a>
      </div>
    </div>
  </div>

  <!-- Mensajes de Error/Éxito -->
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi-exclamation-triangle me-2"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi-check-circle me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <h6><i class="bi-exclamation-triangle me-2"></i>Por favor corrige los siguientes errores:</h6>
      <ul class="mb-0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form action="{{ route('shop.process.order') }}" method="POST" id="checkout-form">
    @csrf
    <div class="row">
      <!-- Información de Entrega -->
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="bi-person-lines-fill text-primary me-2"></i>
              Información de Entrega
            </h5>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Nombre Completo -->
              <div class="col-md-6 mb-3">
                <label for="nombre_completo" class="form-label">Nombre Completo *</label>
                <input type="text" class="form-control @error('nombre_completo') is-invalid @enderror" 
                       id="nombre_completo" name="nombre_completo" 
                       value="{{ old('nombre_completo', Auth::user()->name ?? '') }}" required>
                @error('nombre_completo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Email -->
              <div class="col-md-6 mb-3">
                <label for="email_cliente" class="form-label">Correo Electrónico *</label>
                <input type="email" class="form-control @error('email_cliente') is-invalid @enderror" 
                       id="email_cliente" name="email_cliente" 
                       value="{{ old('email_cliente', Auth::user()->email ?? '') }}" required>
                @error('email_cliente')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Teléfono -->
              <div class="col-md-6 mb-3">
                <label for="telefono_cliente" class="form-label">Teléfono *</label>
                <input type="tel" class="form-control @error('telefono_cliente') is-invalid @enderror" 
                       id="telefono_cliente" name="telefono_cliente" 
                       value="{{ old('telefono_cliente') }}" required>
                @error('telefono_cliente')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- DPI -->
              <div class="col-md-6 mb-3">
                <label for="dpi" class="form-label">DPI *</label>
                <input type="text" class="form-control @error('dpi') is-invalid @enderror" 
                       id="dpi" name="dpi" 
                       value="{{ old('dpi') }}" required 
                       placeholder="0000 00000 0000">
                @error('dpi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- NIT -->
              <div class="col-md-6 mb-3">
                <label for="nit" class="form-label">NIT</label>
                <input type="text" class="form-control @error('nit') is-invalid @enderror" 
                       id="nit" name="nit" 
                       value="{{ old('nit') }}" 
                       placeholder="C/F o número de NIT">
                @error('nit')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Dirección -->
              <div class="col-12 mb-3">
                <label for="direccion_entrega" class="form-label">Dirección de Entrega *</label>
                <textarea class="form-control @error('direccion_entrega') is-invalid @enderror" 
                          id="direccion_entrega" name="direccion_entrega" rows="3" required>{{ old('direccion_entrega') }}</textarea>
                @error('direccion_entrega')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Ciudad y Departamento -->
              <div class="col-md-6 mb-3">
                <label for="ciudad" class="form-label">Ciudad *</label>
                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" 
                       id="ciudad" name="ciudad" 
                       value="{{ old('ciudad') }}" required>
                @error('ciudad')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label for="departamento" class="form-label">Departamento *</label>
                <select class="form-select @error('departamento') is-invalid @enderror" 
                        id="departamento" name="departamento" required>
                  <option value="">Seleccionar departamento</option>
                  <option value="Guatemala" {{ old('departamento') == 'Guatemala' ? 'selected' : '' }}>Guatemala</option>
                  <option value="Alta Verapaz" {{ old('departamento') == 'Alta Verapaz' ? 'selected' : '' }}>Alta Verapaz</option>
                  <option value="Baja Verapaz" {{ old('departamento') == 'Baja Verapaz' ? 'selected' : '' }}>Baja Verapaz</option>
                  <option value="Chimaltenango" {{ old('departamento') == 'Chimaltenango' ? 'selected' : '' }}>Chimaltenango</option>
                  <option value="Chiquimula" {{ old('departamento') == 'Chiquimula' ? 'selected' : '' }}>Chiquimula</option>
                  <option value="El Progreso" {{ old('departamento') == 'El Progreso' ? 'selected' : '' }}>El Progreso</option>
                  <option value="Escuintla" {{ old('departamento') == 'Escuintla' ? 'selected' : '' }}>Escuintla</option>
                  <option value="Huehuetenango" {{ old('departamento') == 'Huehuetenango' ? 'selected' : '' }}>Huehuetenango</option>
                  <option value="Izabal" {{ old('departamento') == 'Izabal' ? 'selected' : '' }}>Izabal</option>
                  <option value="Jalapa" {{ old('departamento') == 'Jalapa' ? 'selected' : '' }}>Jalapa</option>
                  <option value="Jutiapa" {{ old('departamento') == 'Jutiapa' ? 'selected' : '' }}>Jutiapa</option>
                  <option value="Petén" {{ old('departamento') == 'Petén' ? 'selected' : '' }}>Petén</option>
                  <option value="Quetzaltenango" {{ old('departamento') == 'Quetzaltenango' ? 'selected' : '' }}>Quetzaltenango</option>
                  <option value="Quiché" {{ old('departamento') == 'Quiché' ? 'selected' : '' }}>Quiché</option>
                  <option value="Retalhuleu" {{ old('departamento') == 'Retalhuleu' ? 'selected' : '' }}>Retalhuleu</option>
                  <option value="Sacatepéquez" {{ old('departamento') == 'Sacatepéquez' ? 'selected' : '' }}>Sacatepéquez</option>
                  <option value="San Marcos" {{ old('departamento') == 'San Marcos' ? 'selected' : '' }}>San Marcos</option>
                  <option value="Santa Rosa" {{ old('departamento') == 'Santa Rosa' ? 'selected' : '' }}>Santa Rosa</option>
                  <option value="Sololá" {{ old('departamento') == 'Sololá' ? 'selected' : '' }}>Sololá</option>
                  <option value="Suchitepéquez" {{ old('departamento') == 'Suchitepéquez' ? 'selected' : '' }}>Suchitepéquez</option>
                  <option value="Totonicapán" {{ old('departamento') == 'Totonicapán' ? 'selected' : '' }}>Totonicapán</option>
                  <option value="Zacapa" {{ old('departamento') == 'Zacapa' ? 'selected' : '' }}>Zacapa</option>
                </select>
                @error('departamento')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>

        <!-- Método de Pago -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="bi-credit-card text-primary me-2"></i>
              Método de Pago
            </h5>
          </div>
          <div class="card-body">
            <!-- Tipo de Pago -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="tipo_pago" id="pago_linea" value="linea" 
                         {{ old('tipo_pago') == 'linea' ? 'checked' : '' }} onchange="togglePaymentMethod()">
                  <label class="form-check-label" for="pago_linea">
                    <i class="bi-credit-card me-1"></i> Pago en Línea
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="tipo_pago" id="contra_entrega" value="contra_entrega" 
                         {{ old('tipo_pago', 'contra_entrega') == 'contra_entrega' ? 'checked' : '' }} onchange="togglePaymentMethod()">
                  <label class="form-check-label" for="contra_entrega">
                    <i class="bi-cash me-1"></i> Pago Contra Entrega
                  </label>
                </div>
              </div>
            </div>

            <!-- Datos de Tarjeta (solo para pago en línea) -->
            <div id="tarjeta-section" style="display: none;">
              <div class="alert alert-info">
                <i class="bi-info-circle me-2"></i>
                <strong>Información de Tarjeta</strong> - Los datos se procesan de forma segura
              </div>
              
              <div class="row">
                <div class="col-12 mb-3">
                  <label for="numero_tarjeta" class="form-label">Número de Tarjeta</label>
                  <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" 
                         placeholder="0000 0000 0000 0000" maxlength="19">
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                  <input type="text" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" 
                         placeholder="MM/AA" maxlength="5">
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="cvv" class="form-label">CVV</label>
                  <input type="text" class="form-control" id="cvv" name="cvv" 
                         placeholder="000" maxlength="4">
                </div>
              </div>
            </div>

            <!-- Información para Contra Entrega -->
            <div id="contra-entrega-section">
              <div class="alert alert-warning">
                <i class="bi-truck me-2"></i>
                <strong>Pago Contra Entrega</strong> - Pagarás cuando recibas tu pedido. Se aplicará un costo de envío.
              </div>
            </div>
          </div>
        </div>

        <!-- Notas -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="bi-chat-text text-primary me-2"></i>
              Notas Adicionales
            </h5>
          </div>
          <div class="card-body">
            <textarea class="form-control" name="notas_cliente" rows="3" 
                      placeholder="Instrucciones especiales de entrega, referencias, etc.">{{ old('notas_cliente') }}</textarea>
          </div>
        </div>
      </div>

      <!-- Resumen de Orden -->
      <div class="col-lg-4">
        <div class="card position-sticky" style="top: 2rem;">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="bi-receipt text-primary me-2"></i>
              Resumen de Orden
            </h5>
          </div>
          <div class="card-body">
            <!-- Productos -->
            @foreach($carrito as $item)
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="flex-grow-1">
                  <h6 class="mb-0">{{ $item['nombre'] }}</h6>
                  <small class="text-muted">Cantidad: {{ $item['cantidad'] }}</small>
                </div>
                <span class="fw-bold">Q{{ number_format($item['subtotal'], 2) }}</span>
              </div>
            @endforeach
            
            <hr>
            
            <!-- Total -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0">Total</h5>
              <h5 class="mb-0 text-primary">Q{{ number_format($total, 2) }}</h5>
            </div>

            <!-- Botones -->
            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="btn-procesar">
              <i class="bi-check-circle me-2"></i>
              <span id="btn-text">Procesar Orden</span>
            </button>
            
            <a href="{{ route('shop.cart') }}" class="btn btn-outline-secondary w-100">
              <i class="bi-arrow-left me-2"></i>
              Volver al Carrito
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Scripts -->
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('front-dashboard-v2.1.1/dist/assets/js/theme.min.js') }}"></script>

<script>
function togglePaymentMethod() {
  const tipoLinea = document.getElementById('pago_linea').checked;
  const tarjetaSection = document.getElementById('tarjeta-section');
  const contraEntregaSection = document.getElementById('contra-entrega-section');
  const btnText = document.getElementById('btn-text');
  
  if (tipoLinea) {
    tarjetaSection.style.display = 'block';
    contraEntregaSection.style.display = 'none';
    btnText.textContent = 'Pagar Ahora';
    
    // Hacer campos de tarjeta requeridos
    document.getElementById('numero_tarjeta').required = true;
    document.getElementById('fecha_vencimiento').required = true;
    document.getElementById('cvv').required = true;
  } else {
    tarjetaSection.style.display = 'none';
    contraEntregaSection.style.display = 'block';
    btnText.textContent = 'Procesar Orden';
    
    // Quitar requeridos de tarjeta
    document.getElementById('numero_tarjeta').required = false;
    document.getElementById('fecha_vencimiento').required = false;
    document.getElementById('cvv').required = false;
  }
}

// Formatear número de tarjeta
document.getElementById('numero_tarjeta').addEventListener('input', function(e) {
  let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
  let formattedValue = value.match(/.{1,4}/g)?.join(' ');
  e.target.value = formattedValue || value;
});

// Formatear fecha de vencimiento
document.getElementById('fecha_vencimiento').addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length >= 2) {
    value = value.substring(0, 2) + '/' + value.substring(2, 4);
  }
  e.target.value = value;
});

// Solo números en CVV
document.getElementById('cvv').addEventListener('input', function(e) {
  e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// Inicializar al cargar
document.addEventListener('DOMContentLoaded', function() {
  togglePaymentMethod();
});

// Debug del formulario
document.getElementById('checkout-form').addEventListener('submit', function(e) {
  console.log('Formulario enviado');
  console.log('Action:', this.action);
  console.log('Method:', this.method);
});
</script>

</body>
</html> 