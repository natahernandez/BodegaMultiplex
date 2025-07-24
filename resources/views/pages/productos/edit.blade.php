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
          <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
      </nav>

      <h1 class="page-header-title">Editar Producto</h1>
      <p class="page-header-text">Modifique la información del producto "{{ $producto->nombre }}"</p>
    </div>
    
    <div class="col-sm-auto">
      <div class="btn-group" role="group">
        <a class="btn btn-white" href="{{ route('productos.show', $producto) }}">
          <i class="bi-eye me-1"></i> Ver Detalles
        </a>
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

<form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  
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
                       value="{{ old('codigo_interno', $producto->codigo_interno) }}" 
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
                       value="{{ old('codigo_barras', $producto->codigo_barras) }}" 
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
                   value="{{ old('nombre', $producto->nombre) }}" 
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
                      placeholder="Descripción detallada del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
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
                       value="{{ old('marca', $producto->marca) }}" 
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
                    <option value="{{ $categoria }}" {{ old('categoria', $producto->categoria) == $categoria ? 'selected' : '' }}>
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
                         value="{{ old('precio_compra', $producto->precio_compra) }}" required>
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
                         value="{{ old('precio_venta', $producto->precio_venta) }}" required>
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
                         value="{{ old('precio_mayoreo', $producto->precio_mayoreo) }}">
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
                         value="{{ old('iva', $producto->iva) }}">
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
                       value="{{ old('stock_actual', $producto->stock_actual) }}" required>
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
                       value="{{ old('stock_minimo', $producto->stock_minimo) }}" required>
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
                       value="{{ old('stock_maximo', $producto->stock_maximo) }}">
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
                    <option value="{{ $unidad }}" {{ old('unidad_medida', $producto->unidad_medida) == $unidad ? 'selected' : '' }}>
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
                       value="{{ old('proveedor', $producto->proveedor) }}" 
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
                       value="{{ old('ubicacion', $producto->ubicacion) }}" 
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
                       value="{{ old('fecha_vencimiento', $producto->fecha_vencimiento?->format('Y-m-d')) }}">
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
                       id="activo" name="activo" value="1"
                       {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">
                  Producto Activo
                </label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" 
                       id="requiere_receta" name="requiere_receta" value="1"
                       {{ old('requiere_receta', $producto->requiere_receta) ? 'checked' : '' }}>
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
      <!-- Imágenes Existentes -->
      @if($producto->imagenes->count() > 0)
      <div class="card">
        <div class="card-header">
          <h4 class="card-header-title">Imágenes Actuales</h4>
        </div>
        <div class="card-body">
          <div class="row g-2">
            @foreach($producto->imagenes as $imagen)
            <div class="col-6">
              <div class="card">
                <img src="{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}" class="card-img-top" style="height: 120px; object-fit: contain; background: #f8f9fa;" alt="{{ $producto->nombre }}">
                <div class="card-body p-2">
                  <div class="d-flex justify-content-between align-items-center">
                    <small class="text-body">
                      @if($imagen->es_principal)
                        <i class="bi-star-fill text-warning"></i> Principal
                      @else
                        Imagen {{ $imagen->orden }}
                      @endif
                    </small>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" 
                             name="eliminar_imagenes[]" value="{{ $imagen->id }}" 
                             id="eliminar_{{ $imagen->id }}">
                      <label class="form-check-label text-danger" for="eliminar_{{ $imagen->id }}" title="Eliminar imagen">
                        <i class="bi-trash"></i>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="form-text mt-2">
            <i class="bi-info-circle"></i> Marque las imágenes que desea eliminar
          </div>
        </div>
      </div>
      @endif

      <!-- Agregar Nuevas Imágenes -->
      <div class="card {{ $producto->imagenes->count() > 0 ? 'mt-3' : '' }}">
        <div class="card-header">
          <h4 class="card-header-title">Agregar Nuevas Imágenes</h4>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <label for="imagenes" class="form-label">Seleccionar Imágenes</label>
            <input type="file" class="form-control @error('imagenes.*') is-invalid @enderror" 
                   id="imagenes" name="imagenes[]" accept="image/*" multiple>
            <div class="form-text">
              Puede seleccionar múltiples imágenes nuevas.
              <br>Formatos aceptados: JPG, PNG, GIF. Tamaño máximo: 2MB por imagen.
            </div>
            @error('imagenes.*')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Preview de nuevas imágenes -->
          <div id="imagePreview" class="row g-2" style="display: none;">
            <!-- Las imágenes aparecerán aquí -->
          </div>
        </div>
      </div>

      <!-- Resumen -->
      <div class="card mt-3">
        <div class="card-header">
          <h4 class="card-header-title">Información del Producto</h4>
        </div>
        <div class="card-body">
          <dl class="row">
            <dt class="col-sm-5">Estado:</dt>
            <dd class="col-sm-7">
              <span class="badge bg-soft-{{ $producto->activo ? 'success' : 'danger' }} text-{{ $producto->activo ? 'success' : 'danger' }}">
                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
              </span>
            </dd>

            <dt class="col-sm-5">Creado:</dt>
            <dd class="col-sm-7">{{ $producto->created_at->format('d/m/Y') }}</dd>

            <dt class="col-sm-5">Modificado:</dt>
            <dd class="col-sm-7">{{ $producto->updated_at->format('d/m/Y') }}</dd>

            <dt class="col-sm-5">Stock Actual:</dt>
            <dd class="col-sm-7">
              <span class="badge bg-soft-{{ $producto->estado_stock_color }} text-{{ $producto->estado_stock_color }}">
                {{ $producto->stock_actual }} {{ $producto->unidad_medida }}
              </span>
            </dd>
          </dl>

          <hr>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi-check-circle me-1"></i> Actualizar Producto
            </button>
            <a href="{{ route('productos.show', $producto) }}" class="btn btn-white">
              <i class="bi-eye me-1"></i> Ver Detalles
            </a>
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
    // Preview de nuevas imágenes
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
                                        <i class="bi-plus-circle text-success"></i> Nueva ${index + 1}
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

    // Agregar opción para nueva categoría si no existe
    if (!Array.from(categoriaSelect.options).some(option => option.value === 'nueva')) {
        const nuevaOpcion = new Option('+ Nueva Categoría', 'nueva', false, false);
        categoriaSelect.add(nuevaOpcion);
    }

    // Confirmación para eliminar imágenes
    const checkboxesEliminar = document.querySelectorAll('input[name="eliminar_imagenes[]"]');
    checkboxesEliminar.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                const confirmacion = confirm('¿Está seguro de que desea eliminar esta imagen? Esta acción no se puede deshacer.');
                if (!confirmacion) {
                    this.checked = false;
                }
            }
        });
    });
});
</script>
@endsection
