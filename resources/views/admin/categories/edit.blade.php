@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-no-gutter">
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('categories.index') }}">Categorías</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar Categoría</li>
                    </ol>
                </nav>

                <h1 class="page-header-title">Editar Categoría</h1>
                <p class="page-header-text">Modifica la información de la categoría "{{ $category->nombre }}"</p>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row justify-content-lg-center">
        <div class="col-lg-8">
            <!-- Card -->
            <div class="card">
                <!-- Header -->
                <div class="card-header">
                    <h4 class="card-header-title">Información de la Categoría</h4>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="card-body">
                    <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        


                        <!-- Form -->
                        <div class="row mb-4">
                            <label for="nombre" class="col-sm-3 col-form-label form-label">Nombre <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Nombre de la categoría"></i></label>

                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" id="nombre" placeholder="Ej: Electrónicos, Ropa, Hogar..." value="{{ old('nombre', $category->nombre) }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- End Form -->

                        <!-- Form -->
                        <div class="row mb-4">
                            <label for="descripcion" class="col-sm-3 col-form-label form-label">Descripción</label>

                            <div class="col-sm-9">
                                <textarea class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion" placeholder="Descripción de la categoría..." rows="4">{{ old('descripcion', $category->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- End Form -->

                        <!-- Form -->
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label form-label">Imagen</label>

                            <div class="col-sm-9">
                                @if($category->imagen_url)
                                    <div class="mb-3">
                                        <img src="{{ $category->imagen_url }}" alt="{{ $category->nombre }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        <p class="small text-muted mt-1">Imagen actual</p>
                                    </div>
                                @endif

                                <!-- Dropzone -->
                                <div id="attachFilesNewProjectLabel" class="js-dropzone dz-dropzone dz-dropzone-card">
                                    <div class="dz-message">
                                        <img class="avatar avatar-xl avatar-4x3 mb-3" src="{{ asset('svg/illustrations/oc-browse.svg') }}" alt="Image Description">

                                        <h5>Arrastra y suelta tu archivo aquí</h5>

                                        <p class="mb-2">o</p>

                                        <span class="btn btn-white btn-sm">Buscar archivos</span>
                                    </div>
                                </div>
                                <!-- End Dropzone -->

                                <input type="file" class="form-control @error('imagen') is-invalid @enderror" name="imagen" id="imagen" accept="image/*" style="display: none;">
                                @error('imagen')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Deja vacío para mantener la imagen actual.</small>
                            </div>
                        </div>
                        <!-- End Form -->

                        <!-- Form -->
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label form-label">Estado</label>

                            <div class="col-sm-9">
                                <!-- Form Switch -->
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="activo" name="activo" {{ old('activo', $category->activo) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">Categoría activa</label>
                                </div>
                                <!-- End Form Switch -->
                                <small class="form-text text-muted">Las categorías inactivas no se mostrarán en los formularios de productos.</small>
                            </div>
                        </div>
                        <!-- End Form -->

                        <!-- Sticky Block End Point -->
                        <div id="stickyBlockEndPoint"></div>

                        <!-- Sticky Block -->
                        <div class="js-sticky-block card" data-hs-sticky-block-options='{
                            "parentSelector": "#stickyBlockEndPoint",
                            "targetSelector": "#header",
                            "breakpoint": "md"
                        }'>
                            <div class="card-body">
                                <div class="d-flex justify-content-end gap-3">
                                    <a class="btn btn-white" href="{{ route('categories.index') }}">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
                                </div>
                            </div>
                        </div>
                        <!-- End Sticky Block -->
                    </form>
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Handle file upload display
        document.getElementById('attachFilesNewProjectLabel').addEventListener('click', function() {
            document.getElementById('imagen').click();
        });

        document.getElementById('imagen').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                const dropzone = document.querySelector('.js-dropzone .dz-message');
                dropzone.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi-file-earmark-image fs-3 me-2"></i>
                        <div>
                            <h6 class="mb-0">${fileName}</h6>
                            <small class="text-muted">Archivo seleccionado</small>
                        </div>
                    </div>
                `;
            }
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
@endsection
