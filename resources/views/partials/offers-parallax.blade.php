{{-- Sección de Ofertas con Parallax --}}
<section class="offers-parallax" id="ofertas">
    <div class="parallax-bg"></div>
    <div class="parallax-pattern"></div>
    <div class="offers-overlay">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h2 class="display-4 fw-bold text-white mb-3">
                        <i class="bi-fire text-warning me-3"></i>
                        ¡Ofertas Especiales!
                    </h2>
                    <p class="lead text-white-50">Descubre nuestras mejores ofertas con descuentos increíbles</p>
                </div>
            </div>
            
            <div class="row">
                @forelse($productosEnOferta as $producto)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card product-card position-relative h-100">
                            {{-- Imagen del producto --}}
                            @if($producto->imagen_principal_url)
                                <img src="{{ $producto->imagen_principal_url }}" class="card-img-top product-image"
                                    alt="{{ $producto->nombre }}">
                            @else
                                <div class="product-image d-flex align-items-center justify-content-center">
                                    <i class="bi-tag-fill fs-1 text-primary"></i>
                                </div>
                            @endif
                            
                            {{-- Badge de descuento --}}
                            @if($producto->descuento_calculado > 0)
                                <span class="discount-badge-small">-{{ $producto->descuento_calculado }}%</span>
                            @endif
                            
                            <div class="card-body">
                                <span class="category-badge">{{ optional($producto->category)->nombre ?? $producto->categoria }}</span>
                                <h5 class="card-title fw-bold">{{ $producto->nombre }}</h5>
                                
                                {{-- Precios simplificados --}}
                                <div class="mb-3">
                                    <span class="text-decoration-line-through text-muted me-2">Q{{ number_format($producto->precio_venta, 2) }}</span>
                                    <span class="h5 text-danger mb-0">Q{{ number_format($producto->precio_final, 2) }}</span>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <button class="btn btn-add-cart w-100 mb-2"
                                    onclick="addToCart(event, {{ $producto->id }}, '{{ $producto->nombre }}', '{{ route('shop.cart.add') }}')">
                                    <i class="bi-cart-plus"></i> Agregar al Carrito
                                </button>
                                <a href="{{ route('shop.product.show', $producto) }}"
                                    class="btn btn-outline-primary w-100 btn-sm">
                                    <i class="bi-eye"></i> Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <div class="no-offers">
                            <i class="bi-tag display-1 text-white-50 mb-3"></i>
                            <h4 class="text-white">No hay ofertas disponibles</h4>
                            <p class="text-white-50">¡Mantente atento para nuestras próximas ofertas especiales!</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            @if($productosEnOferta->count() > 0)
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <a href="{{ route('welcome', ['en_oferta' => 1]) }}" class="btn btn-outline-light btn-lg">
                            <i class="bi-eye me-2"></i>Ver Todas las Ofertas
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Estilos simplificados para el parallax --}}
<style>
.offers-parallax {
    position: relative;
    min-height: 80vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    margin: 40px 0;
}

.parallax-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 120%;
    height: 120%;
    background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 219, 226, 0.2) 0%, transparent 50%),
        linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    background-attachment: fixed;
    background-size: 400% 400%, 300% 300%, 250% 250%, 100% 100%;
    background-position: center;
    transform: translateZ(0);
    will-change: transform;
    animation: gradientShift 12s ease-in-out infinite;
}

@keyframes gradientShift {
    0%, 100% { 
        background-position: 0% 50%, 0% 50%, 0% 50%, 0% 50%; 
        filter: hue-rotate(0deg);
    }
    25% { 
        background-position: 100% 50%, 25% 75%, 50% 50%, 25% 75%; 
        filter: hue-rotate(5deg);
    }
    50% { 
        background-position: 100% 100%, 75% 25%, 100% 100%, 75% 25%; 
        filter: hue-rotate(10deg);
    }
    75% { 
        background-position: 50% 100%, 100% 50%, 50% 0%, 100% 50%; 
        filter: hue-rotate(5deg);
    }
}

.offers-overlay {
    position: relative;
    z-index: 2;
    background: rgba(0, 0, 0, 0.2);
    width: 100%;
    padding: 60px 0;
}

/* Solo estilos esenciales para el parallax */

.no-offers {
    padding: 60px 20px;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive */
@media (max-width: 768px) {
    .offers-parallax {
        min-height: auto;
    }
    
    .parallax-bg {
        background-attachment: scroll;
    }
    
    .offer-actions {
        flex-direction: column;
    }
    
    .offers-overlay {
        padding: 40px 0;
    }
}

/* Efecto parallax en scroll */
@media (min-width: 769px) {
    .offers-parallax {
        background-attachment: fixed;
    }
}
</style>

{{-- JavaScript para countdown y parallax --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Parallax effect
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.parallax-bg');
        if (parallax) {
            const speed = scrolled * 0.5;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    });
    
    // JavaScript simplificado - solo parallax
});
</script>


