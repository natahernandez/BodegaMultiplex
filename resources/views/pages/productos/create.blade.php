@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col-sm mb-2 mb-sm-0">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-no-gutter">
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('productos.index') }}">Productos</a></li>
          <li class="breadcrumb-item active" aria-current="page">Nuevo Producto</li>
        </ol>
      </nav>

      <h1 class="page-header-title">Agregar Nuevo Producto</h1>
      <p class="page-header-text">Complete la información para agregar un nuevo producto al inventario</p>
    </div>
    
    <div class="col-sm-auto">
      <div class="btn-group" role="group">
        <a class="btn btn-white" href="{{ route('productos.index') }}">
          <i class="bi-arrow-left me-1"></i> Volver a Lista
        </a>
      </div>
    </div>
  </div>
</div>
<!-- End Page Header -->

<!-- Alerts -->
@if($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
  <div class="d-flex">
    <div class="flex-shrink-0">
      <i class="bi-exclamation-triangle-fill"></i>
    </div>
    <div class="flex-grow-1 ms-3">
      <strong>¡Errores encontrados!</strong>
      <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  
  <div class="row">
    <div class="col-lg-8">
      <!-- Información Básica -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-header-title">Información Básica</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <!-- Código Interno -->
              <div class="mb-4">
                <label for="codigo_interno" class="form-label">Código Interno <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('codigo_interno') is-invalid @enderror" 
                       id="codigo_interno" name="codigo_interno" 
                       value="{{ old('codigo_interno') }}" 
                       placeholder="Ej: PROD-001" required>
                @error('codigo_interno')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-sm-6">
              <!-- Código de Barras -->
              <div class="mb-4">
                <label for="codigo_barras" class="form-label">Código de Barras</label>
                <input type="text" class="form-control @error('codigo_barras') is-invalid @enderror" 
                       id="codigo_barras" name="codigo_barras" 
                       value="{{ old('codigo_barras') }}" 
                       placeholder="Opcional">
                @error('codigo_barras')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Nombre -->
          <div class="mb-4">
            <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                   id="nombre" name="nombre" 
                   value="{{ old('nombre') }}" 
                   placeholder="Nombre descriptivo del producto" required>
            @error('nombre')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Descripción -->
          <div class="mb-4">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                      id="descripcion" name="descripcion" rows="3" 
                      placeholder="Descripción detallada del producto...">{{ old('descripcion') }}</textarea>
            @error('descripcion')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row">
            <div class="col-sm-6">
              <!-- Marca -->
              <div class="mb-4">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" class="form-control @error('marca') is-invalid @enderror" 
                       id="marca" name="marca" 
                       value="{{ old('marca') }}" 
                       placeholder="Marca del producto">
                @error('marca')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-sm-6">
              <!-- Categoría -->
              <div class="mb-4">
                <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                <select class="form-select @error('categoria') is-invalid @enderror" 
                        id="categoria" name="categoria" required>
                  <option value="">Seleccionar categoría</option>
                  @foreach($categorias as $categoria)
                    <option value="{{ $categoria }}" {{ old('categoria') == $categoria ? 'selected' : '' }}>
                      {{ $categoria }}
                    </option>
                  @endforeach
                </select>
                <div class="form-text">O escriba una nueva categoría</div>
                @error('categoria')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Precios -->
      <div class="card mt-3">
        <div class="card-header">
          <h4 class="card-header-title">Información de Precios</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <!-- Precio de Compra -->
              <div class="mb-4">
                <label for="precio_compra" class="form-label">Precio de Compra <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">Q</span>
                  <input type="number" step="0.01" min="0" 
                         class="form-control @error('precio_compra') is-invalid @enderror" 
                         id="precio_compra" name="precio_compra" 
                         value="{{ old('precio_compra') }}" required>
                  @error('precio_compra')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <!-- Precio de Venta -->
              <div class="mb-4">
                <label for="precio_venta" class="form-label">Precio de Venta <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">Q</span>
                  <input type="number" step="0.01" min="0" 
                         class="form-control @error('precio_venta') is-invalid @enderror" 
                         id="precio_venta" name="precio_venta" 
                         value="{{ old('precio_venta') }}" required>
                  @error('precio_venta')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <!-- Precio Mayoreo -->
              <div class="mb-4">
                <label for="precio_mayoreo" class="form-label">Precio Mayoreo</label>
                <div class="input-group">
                  <span class="input-group-text">Q</span>
                  <input type="number" step="0.01" min="0" 
                         class="form-control @error('precio_mayoreo') is-invalid @enderror" 
                         id="precio_mayoreo" name="precio_mayoreo" 
                         value="{{ old('precio_mayoreo') }}">
                  @error('precio_mayoreo')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <!-- IVA -->
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-4">
                <label for="iva" class="form-label">IVA (%)</label>
                <div class="input-group">
                  <input type="number" step="0.01" min="0" max="100" 
                         class="form-control @error('iva') is-invalid @enderror" 
                         id="iva" name="iva" 
                         value="{{ old('iva', 0) }}">
                  <span class="input-group-text">%</span>
                  @error('iva')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventario -->
      <div class="card mt-3">
        <div class="card-header">
          <h4 class="card-header-title">Control de Inventario</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-3">
              <!-- Stock Actual -->
              <div class="mb-4">
                <label for="stock_actual" class="form-label">Stock Actual <span class="text-danger">*</span></label>
                <input type="number" min="0" 
                       class="form-control @error('stock_actual') is-invalid @enderror" 
                       id="stock_actual" name="stock_actual" 
                       value="{{ old('stock_actual', 0) }}" required>
                @error('stock_actual')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-sm-3">
              <!-- Stock Mínimo -->
              <div class="mb-4">
                <label for="stock_minimo" class="form-label">Stock Mínimo <span class="text-danger">*</span></label>
                <input type="number" min="0" 
                       class="form-control @error('stock_minimo') is-invalid @enderror" 
                       id="stock_minimo" name="stock_minimo" 
                       value="{{ old('stock_minimo', 5) }}" required>
                @error('stock_minimo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-sm-3">
              <!-- Stock Máximo -->
              <div class="mb-4">
                <label for="stock_maximo" class="form-label">Stock Máximo</label>
                <input type="number" min="0" 
                       class="form-control @error('stock_maximo') is-invalid @enderror" 
                       id="stock_maximo" name="stock_maximo" 
                       value="{{ old('stock_maximo') }}">
                @error('stock_maximo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-sm-3">
              <!-- Unidad de Medida -->
              <div class="mb-4">
                <label for="unidad_medida" class="form-label">Unidad <span class="text-danger">*</span></label>
                <select class="form-select @error('unidad_medida') is-invalid @enderror" 
                        id="unidad_medida" name="unidad_medida" required>
                  @foreach($unidades as $unidad)
                    <option value="{{ $unidad }}" {{ old('unidad_medida', 'Unidad') == $unidad ? 'selected' : '' }}>
                      {{ $unidad }}
                    </option>
                  @endforeach
                </select>
                @error('unidad_medida')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Información Adicional -->
      <div class="card mt-3">
        <div class="card-header">
          <h4 class="card-header-title">Información Adicional</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <!-- Proveedor -->
              <div class="mb-4">
                <label for="proveedor" class="form-label">Proveedor</label>
                <input type="text" class="form-control @error('proveedor') is-invalid @enderror" 
                       id="proveedor" name="proveedor" 
                       value="{{ old('proveedor') }}" 
                       placeholder="Nombre del proveedor">
                @error('proveedor')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-sm-6">
              <!-- Ubicación -->
              <div class="mb-4">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" 
                       id="ubicacion" name="ubicacion" 
                       value="{{ old('ubicacion') }}" 
                       placeholder="Ej: Pasillo A, Estante 3">
                @error('ubicacion')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Fecha de Vencimiento -->
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-4">
                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                <input type="date" class="form-control @error('fecha_vencimiento') is-invalid @enderror" 
                       id="fecha_vencimiento" name="fecha_vencimiento" 
                       value="{{ old('fecha_vencimiento') }}">
                @error('fecha_vencimiento')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Checkboxes -->
          <div class="row">
            <div class="col-sm-6">
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" 
                       id="requiere_receta" name="requiere_receta" value="1"
                       {{ old('requiere_receta') ? 'checked' : '' }}>
                <label class="form-check-label" for="requiere_receta">
                  Requiere Receta Médica
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Imágenes del Producto -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-header-title">Imágenes del Producto</h4>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <label for="imagenes" class="form-label">Seleccionar Imágenes</label>
            <input type="file" class="form-control @error('imagenes.*') is-invalid @enderror" 
                   id="imagenes" name="imagenes[]" accept="image/*" multiple>
            <div class="form-text">
              Puede seleccionar múltiples imágenes. La primera será la imagen principal.
              <br>Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB por imagen.
            </div>
            @error('imagenes.*')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Preview de imágenes -->
          <div id="imagePreview" class="row g-2" style="display: none;">
            <!-- Las imágenes aparecerán aquí -->
          </div>
        </div>
      </div>

      <!-- Resumen -->
      <div class="card mt-3">
        <div class="card-header">
          <h4 class="card-header-title">Resumen</h4>
        </div>
        <div class="card-body">
          <dl class="row">
            <dt class="col-sm-5">Estado:</dt>
            <dd class="col-sm-7">
              <span class="badge bg-soft-success text-success">Nuevo Producto</span>
            </dd>

            <dt class="col-sm-5">Creado por:</dt>
            <dd class="col-sm-7">{{ auth()->user()->name }}</dd>

            <dt class="col-sm-5">Fecha:</dt>
            <dd class="col-sm-7">{{ now()->format('d/m/Y H:i') }}</dd>
          </dl>

          <hr>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi-check-circle me-1"></i> Crear Producto
            </button>
            <a href="{{ route('productos.index') }}" class="btn btn-white">
              <i class="bi-x-lg me-1"></i> Cancelar
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview de imágenes
    const imageInput = document.getElementById('imagenes');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        imagePreview.innerHTML = '';
        
        if (files.length > 0) {
            imagePreview.style.display = 'block';
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-6';
                        
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-body">
                                        ${index === 0 ? '<i class="bi-star-fill text-warning"></i> Principal' : `Imagen ${index + 1}`}
                                    </small>
                                </div>
                            </div>
                        `;
                        
                        imagePreview.appendChild(col);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Hacer que la categoría sea editable
    const categoriaSelect = document.getElementById('categoria');
    categoriaSelect.addEventListener('change', function() {
        if (this.value === 'nueva') {
            const nuevaCategoria = prompt('Ingrese el nombre de la nueva categoría:');
            if (nuevaCategoria) {
                const option = new Option(nuevaCategoria, nuevaCategoria, true, true);
                this.add(option);
            }
        }
    });

    // Agregar opción para nueva categoría
    const nuevaOpcion = new Option('+ Nueva Categoría', 'nueva', false, false);
    categoriaSelect.add(nuevaOpcion);
});
</script>
@endsection
