@extends('layouts.shop')

@section('title', 'Finalizar Compra - Bodegas Multiplex')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('shop.cart') }}">Carrito</a></li>
                        <li class="breadcrumb-item active">Pagar</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">Finalizar Compra</h1>
                    <a href="{{ route('shop.cart') }}" class="btn btn-outline-primary"><i class="bi-arrow-left"></i> Volver
                        al Carrito</a>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6><i class="bi-exclamation-triangle me-2"></i>Por favor corrige los siguientes errores:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('shop.process.order') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="bi-person-lines-fill text-primary me-2"></i>Información de
                                Entrega</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre_completo" class="form-label">Nombre Completo *</label>
                                    <input type="text"
                                        class="form-control @error('nombre_completo') is-invalid @enderror"
                                        id="nombre_completo" name="nombre_completo"
                                        value="{{ old('nombre_completo', Auth::user()->name ?? '') }}" required>
                                    @error('nombre_completo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email_cliente" class="form-label">Correo Electrónico *</label>
                                    <input type="email" class="form-control @error('email_cliente') is-invalid @enderror"
                                        id="email_cliente" name="email_cliente"
                                        value="{{ old('email_cliente', Auth::user()->email ?? '') }}" required>
                                    @error('email_cliente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefono_cliente" class="form-label">Teléfono *</label>
                                    <input type="tel"
                                        class="form-control @error('telefono_cliente') is-invalid @enderror"
                                        id="telefono_cliente" name="telefono_cliente" maxlength="8" minlength="8"
                                        pattern="[0-9]*" title="El teléfono debe tener 8 dígitos"
                                        value="{{ old('telefono_cliente') }}" required>
                                    @error('telefono_cliente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dpi" class="form-label">DPI *</label>
                                    <input type="text" class="form-control @error('dpi') is-invalid @enderror"
                                        id="dpi" name="dpi" maxlength="13" minlength="13" pattern="[0-9]*"
                                        title="El DPI debe tener 13 dígitos" value="{{ old('dpi') }}" required
                                        placeholder="0000 00000 0000">
                                    @error('dpi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nit" class="form-label">NIT</label>
                                    <input type="text" class="form-control @error('nit') is-invalid @enderror"
                                        id="nit" name="nit" value="{{ old('nit') }}"
                                        placeholder="C/F o número de NIT">
                                    @error('nit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="direccion_entrega" class="form-label">Dirección de Entrega *</label>
                                    <textarea class="form-control @error('direccion_entrega') is-invalid @enderror" id="direccion_entrega"
                                        name="direccion_entrega" rows="3" required>{{ old('direccion_entrega') }}</textarea>
                                    @error('direccion_entrega')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="departamento" class="form-label">Departamento *</label>
                                    <select class="form-select @error('departamento') is-invalid @enderror"
                                        id="departamento" name="departamento" required>
                                        <option value="">Seleccionar departamento</option>
                                        @foreach (['Guatemala', 'Alta Verapaz', 'Baja Verapaz', 'Chimaltenango', 'Chiquimula', 'El Progreso', 'Escuintla', 'Huehuetenango', 'Izabal', 'Jalapa', 'Jutiapa', 'Petén', 'Quetzaltenango', 'Quiché', 'Retalhuleu', 'Sacatepéquez', 'San Marcos', 'Santa Rosa', 'Sololá', 'Suchitepéquez', 'Totonicapán', 'Zacapa'] as $dep)
                                            <option value="{{ $dep }}"
                                                {{ old('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('departamento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ciudad" class="form-label">Ciudad *</label>
                                    <input type="text" class="form-control @error('ciudad') is-invalid @enderror"
                                        id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required>
                                    @error('ciudad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="bi-credit-card text-primary me-2"></i>Método de Pago
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_pago" id="pago_linea"
                                            value="linea" {{ old('tipo_pago') == 'linea' ? 'checked' : '' }}
                                            onchange="togglePaymentMethod()">
                                        <label class="form-check-label" for="pago_linea"><i
                                                class="bi-credit-card me-1"></i> Pago en Línea</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="tipo_pago"
                                            id="contra_entrega" value="contra_entrega"
                                            {{ old('tipo_pago', 'contra_entrega') == 'contra_entrega' ? 'checked' : '' }}
                                            onchange="togglePaymentMethod()">
                                        <label class="form-check-label" for="contra_entrega"><i class="bi-cash me-1"></i>
                                            Pago Contra Entrega</label>
                                    </div>
                                </div>
                            </div>

                            <div id="tarjeta-section" style="display:none;">
                                <div class="alert alert-info">
                                    <i class="bi-credit-card me-2"></i><strong>Pago Seguro con Pagadito</strong><br>
                                    <small>Serás redirigido a Pagadito. Los precios se muestran en GTQ; Pagadito mostrará el
                                        equivalente en USD.</small>
                                </div>
                                <div class="card border-primary">
                                    <div class="card-body text-center"><i class="bi-shield-check text-primary"
                                            style="font-size:2rem;"></i>
                                        <h6 class="mt-2">Pago 100% Seguro</h6>
                                        <p class="text-muted small mb-0">• Tarjetas Visa, Mastercard<br>• Encriptación
                                            SSL<br>• Sin almacenar datos bancarios</p>
                                    </div>
                                </div>
                            </div>
                            <div id="contra-entrega-section">
                                <div class="alert alert-warning"><i class="bi-truck me-2"></i><strong>Pago Contra
                                        Entrega</strong> - Pagarás cuando recibas tu pedido. Se aplicará un costo de envío.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="bi-chat-text text-primary me-2"></i>Notas Adicionales
                            </h5>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="notas_cliente" rows="3"
                                placeholder="Instrucciones especiales de entrega, referencias, etc.">{{ old('notas_cliente') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card position-sticky" style="top:2rem;">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="bi-receipt text-primary me-2"></i>Resumen de Orden</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($carrito as $item)
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item['nombre'] }}</h6>
                                        <small class="text-muted d-block">Cantidad: {{ $item['cantidad'] }}</small>
                                        
                                        {{-- Mostrar si está en oferta --}}
                                        @if(isset($item['en_oferta']) && $item['en_oferta'])
                                            <span class="badge bg-success mt-1">
                                                <i class="bi-fire me-1"></i>{{ $item['descuento_porcentaje'] }}% OFF
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        @if(isset($item['en_oferta']) && $item['en_oferta'])
                                            <div class="text-decoration-line-through text-muted small">Q{{ number_format($item['precio_original'] * $item['cantidad'], 2) }}</div>
                                            <span class="fw-bold text-danger">Q{{ number_format($item['subtotal'], 2) }}</span>
                                        @else
                                            <span class="fw-bold">Q{{ number_format($item['subtotal'], 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Total</h5>
                                <h5 class="mb-0 text-primary">Q{{ number_format($total, 2) }}</h5>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="btn-procesar"><i
                                    class="bi-check-circle me-2"></i><span id="btn-text">Procesar Orden</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePaymentMethod() {
            const tipoLinea = document.getElementById('pago_linea').checked;
            const tarjeta = document.getElementById('tarjeta-section');
            const contra = document.getElementById('contra-entrega-section');
            const btnText = document.getElementById('btn-text');
            if (tipoLinea) {
                tarjeta.style.display = 'block';
                contra.style.display = 'none';
                btnText.textContent = 'Procesar con Pagadito';
            } else {
                tarjeta.style.display = 'none';
                contra.style.display = 'block';
                btnText.textContent = 'Procesar Orden';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            togglePaymentMethod();
        });

        // Interceptar submit para pago en línea y abrir modal/iframe
        (function(){
            const form = document.getElementById('checkout-form');
            const pagoLinea = document.getElementById('pago_linea');
            const btn = document.getElementById('btn-procesar');
            let modalEl, intervalId;

            function openModal(url, ern){
                // Pagadito no permite embebido por X-Frame-Options; abrimos una ventana nueva y mantenemos modal informativo
                const modalHtml = `
                <div class="modal fade" id="pgModal" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Completar pago en Pagadito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <p class="mb-2">Se abrió una ventana para completar el pago. No cierres esta ventana hasta finalizar.</p>
                        <div class="d-flex align-items-center gap-2 text-muted"><span class="spinner-border spinner-border-sm"></span> Esperando confirmación...</div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>`;
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                modalEl = document.getElementById('pgModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                const w = 960, h = 720;
                const left = (screen.width/2)-(w/2);
                const top = (screen.height/2)-(h/2);
                const payWin = window.open(url, 'pagadito_window', `toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=${w},height=${h},top=${top},left=${left}`);

                let attempts = 0;
                const MAX_ATTEMPTS = 60; // ~3 minutos
                intervalId = setInterval(async ()=>{
                    try{
                        const res = await fetch(`{{ route('pagadito.status') }}?ern=${encodeURIComponent(ern)}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
                        const data = await res.json();
                        if (data.paid){
                            clearInterval(intervalId);
                            try{ if (payWin && !payWin.closed) payWin.close(); }catch(e){}
                            Swal.fire({icon:'success', title:'Pago confirmado', text:'Tu compra se ha completado exitosamente.', timer:1800, showConfirmButton:false});
                            setTimeout(()=>{ window.location.href = `{{ route('shop.order.success', ':ern') }}`.replace(':ern', ern); }, 1200);
                            return;
                        }
                        if (['expirado','cancelado'].includes((data.estado||'').toLowerCase())){
                            clearInterval(intervalId);
                            try{ if (payWin && !payWin.closed) payWin.close(); }catch(e){}
                            Swal.fire({icon:'error', title:'Pago no completado', text:'El pago fue cancelado o expiró. Puedes intentarlo nuevamente.'});
                            btn.disabled = false; btn.innerHTML = '<i class="bi-check-circle me-2"></i><span id="btn-text">Procesar Orden</span>';
                            return;
                        }
                        attempts++;
                        if (attempts >= MAX_ATTEMPTS){
                            clearInterval(intervalId);
                            Swal.fire({icon:'warning', title:'Demora en confirmar', text:'Aún no se confirma el pago. Si ya pagaste, espera un momento o verifica tus órdenes.'});
                            btn.disabled = false; btn.innerHTML = '<i class="bi-check-circle me-2"></i><span id="btn-text">Procesar Orden</span>';
                        }
                    }catch(e){
                        // Silencioso; continuar intentando
                    }
                }, 3000);
                modalEl.addEventListener('hidden.bs.modal', ()=>{ if (intervalId) clearInterval(intervalId); modalEl.remove(); });
            }

            form.addEventListener('submit', async function(e){
                if (pagoLinea && pagoLinea.checked){
                    e.preventDefault();
                    btn.disabled = true; const original = btn.innerHTML; btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';
                    try{
                        const formData = new FormData(form);
                        const res = await fetch(form.action, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: formData });
                        const data = await res.json();
                        if (!data || !data.iniciar_url || !data.ern){ throw new Error('Respuesta inválida'); }
                        // Llamar iniciar en modo JSON para obtener paymentUrl
                        const resInit = await fetch(data.iniciar_url, { headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'} });
                        const init = await resInit.json();
                        if (!init || !init.url){ throw new Error('No se obtuvo URL de pago'); }
                        openModal(init.url, data.ern);
                    }catch(err){
                        Swal.fire({icon:'error', title:'Error', text:'No se pudo iniciar el pago. ' + (err?.message || '')});
                        btn.disabled = false; btn.innerHTML = original;
                    }
                }
            });
        })();
    </script>
@endpush
