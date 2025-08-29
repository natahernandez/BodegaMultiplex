@extends('layouts.shop')

@section('title', 'Carrito de Compras - Bodegas Multiplex')

@section('content')
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
                @if ($totalItems > 0)
                    <span class="badge bg-soft-primary text-primary ms-2">{{ $totalItems }} artículos</span>
                @endif
            </h1>
        </div>
    </div>

    @if (!empty($carrito))
        <div class="row">
            <div class="col-lg-8 mb-4">
                <!-- Cart Items -->
                <div class="cart-content card-soft">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">
                            Detalles del pedido
                            <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ count($carrito) }}</span>
                        </h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        @foreach ($carrito as $productoId => $item)
                            <!-- Cart Item -->
                            <div class="d-flex cart-item-row py-3" data-product-id="{{ $productoId }}">
                                <div class="flex-shrink-0">
                                    @if ($item['imagen'])
                                        <img class="product-image" src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}">
                                    @else
                                        <div class="product-placeholder"><i class="bi-image text-muted"></i></div>
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
                                            
                                            {{-- Mostrar si está en oferta --}}
                                            @if(isset($item['en_oferta']) && $item['en_oferta'])
                                                <div class="mt-2">
                                                    <span class="badge bg-success">
                                                        <i class="bi-fire me-1"></i>{{ $item['descuento_porcentaje'] }}% OFF
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col col-md-2 align-self-center">
                                            @if(isset($item['en_oferta']) && $item['en_oferta'])
                                                <div class="text-decoration-line-through text-muted small">Q{{ number_format($item['precio_original'], 2) }}</div>
                                                <h5 class="mb-0 text-danger">Q{{ number_format($item['precio'], 2) }}</h5>
                                            @else
                                                <h5 class="mb-0">Q{{ number_format($item['precio'], 2) }}</h5>
                                            @endif
                                        </div>

                                        <div class="col col-md-2 align-self-center">
                                            <div class="quantity-controls">
                                                <button class="quantity-btn"
                                                    onclick="updateQuantity({{ $productoId }}, {{ $item['cantidad'] - 1 }})"><i
                                                        class="bi-dash"></i></button>
                                                <input type="number" class="quantity-input"
                                                    value="{{ $item['cantidad'] }}" min="1"
                                                    onchange="updateQuantity({{ $productoId }}, this.value)">
                                                <button class="quantity-btn"
                                                    onclick="updateQuantity({{ $productoId }}, {{ $item['cantidad'] + 1 }})"><i
                                                        class="bi-plus"></i></button>
                                            </div>
                                        </div>

                                        <div class="col col-md-2 align-self-center text-end">
                                            <h5 class="mb-0">Q{{ number_format($item['subtotal'], 2) }}</h5>
                                        </div>
                                    </div>
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

                            @if (!$loop->last)
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
                                    <dd class="col-sm-6 border-top pt-2"><strong>Q{{ number_format($total, 2) }}</strong>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="/" class="btn btn-outline-secondary"><i class="bi-arrow-left me-1"></i>Seguir
                        comprando</a>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-danger" onclick="clearCart()"><i
                                class="bi-trash me-1"></i>Vaciar carrito</button>
                        @auth
                            <a href="{{ route('shop.checkout') }}" class="btn btn-checkout text-white"><i
                                    class="bi-credit-card me-1"></i>Proceder al pago</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-checkout text-white"><i
                                    class="bi-person-lock me-1"></i>Iniciar sesión para continuar</a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="cart-summary card-soft">
                    <div class="card-header">
                        <h4 class="card-header-title">Resumen del pedido</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2"><span>Artículos
                                    ({{ $totalItems }}):</span><span>Q{{ number_format($total, 2) }}</span></div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span><span>Q{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        @if ($total < 200)
                            <div class="alert alert-soft-info mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0"><i class="bi-info-circle"></i></div>
                                    <div class="flex-grow-1 ms-2"><small>Agrega Q{{ number_format(200 - $total, 2) }} más
                                            para envío gratis</small></div>
                                </div>
                            </div>
                        @endif
                        <div class="row text-center mt-4 pt-3 border-top">
                            <div class="col-4"><i class="bi-shield-check text-success fs-4 d-block mb-1"></i><small
                                    class="text-muted">Compra Segura</small></div>
                            <div class="col-4"><i class="bi-truck text-primary fs-4 d-block mb-1"></i><small
                                    class="text-muted">Envío Rápido</small></div>
                            <div class="col-4"><i class="bi-arrow-clockwise text-info fs-4 d-block mb-1"></i><small
                                    class="text-muted">Devoluciones</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="empty-cart"><i class="bi-cart-x empty-cart-icon d-block"></i>
            <h3 class="mb-3">Tu carrito está vacío</h3>
            <p class="mb-4">Parece que no has agregado ningún producto a tu carrito todavía.</p><a href="/"
                class="btn btn-primary"><i class="bi-bag me-1"></i>Comenzar a comprar</a>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Actualizar cantidad (carrito)
        function updateQuantity(productId, newQuantity) {
            if (newQuantity < 1) {
                removeFromCart(productId);
                return;
            }
            $.ajax({
                url: '{{ route('shop.cart.update') }}',
                method: 'PUT',
                data: {
                    producto_id: productId,
                    cantidad: newQuantity
                },
                success: function(response) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cantidad actualizada',
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    const r = xhr.responseJSON;
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: r?.error || 'Error al actualizar el carrito'
                        });
                    } else {
                        shopAlert('danger', r?.error || 'Error al actualizar el carrito');
                    }
                }
            });
        }
        // Eliminar del carrito (carrito)
        function removeFromCart(productId, productName = '') {
            const proceed = () => $.ajax({
                url: '{{ route('shop.cart.remove') }}',
                method: 'DELETE',
                data: {
                    producto_id: productId
                },
                success: function() {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Producto eliminado',
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    const r = xhr.responseJSON;
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: r?.error || 'Error al eliminar del carrito'
                        });
                    } else {
                        shopAlert('danger', r?.error || 'Error al eliminar del carrito');
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
                }).then(r => {
                    if (r.isConfirmed) proceed();
                });
            } else if (productName) {
                if (confirm(`¿Eliminar "${productName}" del carrito?`)) proceed();
            } else {
                proceed();
            }
        }
        // Vaciar carrito (carrito)
        function clearCart() {
            const exec = () => $.ajax({
                url: '{{ route('shop.cart.clear') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Carrito vaciado',
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        location.reload();
                    }
                },
                error: function() {
                    if (window.Swal) Swal.fire({
                        icon: 'error',
                        title: 'Error al vaciar el carrito'
                    });
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
                }).then(r => {
                    if (r.isConfirmed) exec();
                });
            } else {
                if (confirm('¿Vaciar carrito?')) exec();
            }
        }
        // Tooltips
        $(function() {
            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(el => new bootstrap.Tooltip(
                el));
        });
    </script>
@endpush
