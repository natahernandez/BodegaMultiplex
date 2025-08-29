@extends('layouts.shop')

@section('title', $producto->nombre . ' - Bodegas Multiplex')

@push('styles')
    <!-- Si necesitas estilos extra específicos de esta página, podríamos crear shop-show.css -->
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item"><a
                    href="{{ route('welcome', ['categoria' => $producto->categoria]) }}">{{ $producto->categoria }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
        </ol>
    </nav>

    <div class="row my-4">
        <!-- Galería -->
        <div class="col-lg-6">
            <div class="product-gallery">
                @php
                    $imagenes = $producto->imagenes;
                    $imagenPrincipal = $imagenes->where('es_principal', true)->first() ?? $imagenes->first();
                @endphp
                <div class="mb-4 position-relative">
                    @if ($producto->stock_actual <= 0)
                        <span class="badge bg-danger badge-stock">Agotado</span>
                    @elseif($producto->stock_actual <= $producto->stock_minimo)
                        <span class="badge bg-warning text-dark badge-stock">Stock Bajo</span>
                    @else
                        <span class="badge bg-success badge-stock">Disponible</span>
                    @endif

                    <div class="main-image-container">
                        @if ($producto->imagen_principal_url)
                            <img id="mainImage" src="{{ $producto->imagen_principal_url }}" alt="{{ $producto->nombre }}"
                                class="img-fluid main-image w-100"
                                style="height:400px; object-fit:contain; background:#f8f9fa;">
                        @else
                            <div class="main-image bg-light d-flex align-items-center justify-content-center w-100"
                                style="height:400px;">
                                <i class="bi-image text-muted" style="font-size:5rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($imagenes->count() > 1)
                    <div class="row g-2">
                        @foreach ($imagenes as $i => $imagen)
                            <div class="col-3">
                                <img src="{{ \App\Helpers\ImageHelper::getProductImageUrl($imagen->ruta_imagen) }}"
                                    alt="{{ $producto->nombre }}"
                                    class="img-fluid thumbnail w-100 {{ $imagen->es_principal ? 'active' : '' }}"
                                    data-index="{{ $i }}"
                                    style="height:80px; object-fit:contain; background:#f8f9fa;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Info -->
        <div class="col-lg-6">
            <div class="product-info card-soft p-4">
                <span class="badge bg-primary mb-3">{{ $producto->categoria }}</span>
                <h1 class="display-5 fw-bold mb-3">{{ $producto->nombre }}</h1>

                @php($brandName = optional($producto->brand)->nombre ?? $producto->marca)
                @if ($brandName)
                    <p class="text-muted mb-3"><i class="bi-tag me-2"></i>Marca: <strong>{{ $brandName }}</strong></p>
                @endif

                <div class="price-section">
                    <div class="row align-items-center">
                        <div class="col">
                            @if($producto->es_oferta_activa)
                                <div class="offer-prices-detail">
                                    <span class="price-original-detail">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                    <h3 class="mb-0 text-white">Q{{ number_format($producto->precio_final, 2) }}</h3>
                                    @if($producto->descuento_calculado > 0)
                                        <span class="discount-detail">¡Ahorras {{ $producto->descuento_calculado }}%!</span>
                                    @endif
                                </div>
                            @else
                                <h3 class="mb-0 text-white">Q{{ number_format($producto->precio_final, 2) }}</h3>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($producto->descripcion)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Descripción</h5>
                        <p class="text-muted">{{ $producto->descripcion }}</p>
                    </div>
                @endif

                <div class="product-features">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center"><i class="bi-box me-2 text-primary"></i>
                                <div><small
                                        class="text-muted d-block">Código</small><strong>{{ $producto->codigo_interno }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center"><i class="bi-archive me-2 text-success"></i>
                                <div><small class="text-muted d-block">Stock</small><strong>{{ $producto->stock_actual }}</strong></div>
                            </div>
                        </div>
                        @if ($producto->codigo_barras)
                            <div class="col-6">
                                <div class="d-flex align-items-center"><i class="bi-upc me-2 text-info"></i>
                                    <div><small class="text-muted d-block">Código de
                                            Barras</small><strong>{{ $producto->codigo_barras }}</strong></div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                @if ($producto->stock_actual > 0)
                    <div class="row align-items-center mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Cantidad</label>
                            <div class="input-group quantity-selector">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)"><i
                                        class="bi-dash"></i></button>
                                <input type="number" class="form-control text-center" id="quantity" value="1"
                                    min="1" max="{{ $producto->stock_actual }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)"><i
                                        class="bi-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <button class="btn btn-add-cart text-white w-100" onclick="addToCart()"><i
                                    class="bi-cart-plus me-2"></i>Agregar al Carrito</button>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger"><i class="bi-exclamation-triangle me-2"></i>Producto agotado - Consulta
                        disponibilidad</div>
                @endif

                <div class="row text-center">
                    <div class="col-4"><i class="bi-truck text-primary fs-4 d-block mb-2"></i><small
                            class="text-muted">Envío Gratis<br>en compras +Q200</small></div>
                    <div class="col-4"><i class="bi-shield-check text-success fs-4 d-block mb-2"></i><small
                            class="text-muted">Compra<br>Segura</small></div>
                    <div class="col-4"><i class="bi-arrow-clockwise text-info fs-4 d-block mb-2"></i><small
                            class="text-muted">Devoluciones<br>24 horas</small></div>
                </div>
            </div>
        </div>
    </div>

    @if ($productosRelacionados->count() > 0)
        <div class="mt-5">
            <h3 class="fw-bold mb-4">Productos Relacionados</h3>
            <div class="row related-products">
                @foreach ($productosRelacionados as $relacionado)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <div class="position-relative">
                                @if ($relacionado->imagen_principal_url)
                                    <img src="{{ $relacionado->imagen_principal_url }}" class="card-img-top"
                                        alt="{{ $relacionado->nombre }}"
                                        style="height:200px; object-fit:contain; background:#f8f9fa;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                        style="height: 200px;"><i class="bi-image text-muted fs-3"></i></div>
                                @endif
                                <span
                                    class="position-absolute top-0 end-0 m-2 badge bg-primary">Q{{ number_format($relacionado->precio_venta, 2) }}</span>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $relacionado->nombre }}</h6>
                                <p class="card-text text-muted small">{{ Str::limit($relacionado->descripcion, 60) }}</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-grid"><a href="{{ route('shop.product.show', $relacionado) }}"
                                        class="btn btn-outline-primary btn-sm"><i class="bi-eye me-1"></i>Ver Producto</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(function() {
            const images = @json($imagenes->pluck('ruta_imagen'));
            let currentIndex = 0,
                interval;
            const mainImage = $('#mainImage');

            // Solo configurar carrusel si hay múltiples imágenes
            if (images.length > 1) {
                function changeImage(index) {
                    if (index < 0) index = images.length - 1;
                    if (index >= images.length) index = 0;
                    currentIndex = index;
                    const imageUrl = `{{ url('/storage/productos/') }}/${images[currentIndex]}`;
                    mainImage.fadeOut(300, function() {
                        $(this).attr('src', imageUrl).fadeIn(300);
                    });
                    $('.thumbnail').removeClass('active').filter(`[data-index="${currentIndex}"]`).addClass('active');
                }

                function autoSlide() {
                    clearInterval(interval);
                    interval = setInterval(() => {
                        changeImage((currentIndex + 1) % images.length);
                    }, 5000);
                }
                
                $('.main-image-container').hover(() => clearInterval(interval), () => autoSlide());
                $('.thumbnail').on('click', function() {
                    const i = $(this).data('index');
                    changeImage(i);
                    autoSlide();
                });
                
                autoSlide();
            }
            const container = $('.main-image-container');
            let zoomLevel = 2;
            container.on('mousemove', function(e) {
                const o = $(this).offset();
                const x = ((e.pageX - o.left) / $(this).width()) * 100;
                const y = ((e.pageY - o.top) / $(this).height()) * 100;
                mainImage.css({
                    transform: `scale(${zoomLevel})`,
                    'transform-origin': `${x}% ${y}%`
                });
            });
            container.on('mouseleave', function() {
                mainImage.css({
                    transform: 'scale(1)',
                    'transform-origin': 'center center'
                });
            });
        });

        function changeQuantity(delta) {
            const input = document.getElementById('quantity');
            if (!input) return;
            const min = parseInt(input.min || '1', 10);
            const max = parseInt(input.max || '999999', 10);
            let value = parseInt(input.value || '1', 10) + delta;
            if (value < min) value = min;
            if (value > max) value = max;
            input.value = value;
        }

        function addToCart() {
            const cantidad = parseInt(document.getElementById('quantity').value || '1', 10);
            const button = event.currentTarget;
            const original = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Agregando...';
            $.ajax({
                url: '{{ route('shop.cart.add') }}',
                method: 'POST',
                data: {
                    producto_id: {{ $producto->id }},
                    cantidad: cantidad,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    window.location.href = '{{ route('welcome') }}?success=1&product=' + encodeURIComponent(
                        '{{ $producto->nombre }}');
                },
                error: function(xhr) {
                    shopAlert('danger', xhr.responseJSON?.error || 'Error al agregar al carrito');
                    button.disabled = false;
                    button.innerHTML = original;
                }
            });
        }
    </script>
@endpush
