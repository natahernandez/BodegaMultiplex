@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        {{-- Información de resultados --}}
        <div class="pagination-info text-center mb-3">
            <small class="text-muted">
                Mostrando <strong>{{ $paginator->firstItem() ?? 0 }}</strong> a 
                <strong>{{ $paginator->lastItem() ?? 0 }}</strong> de 
                <strong>{{ $paginator->total() }}</strong> productos
            </small>
        </div>

        {{-- Navegación de páginas --}}
        <nav class="d-flex justify-content-center" aria-label="Navegación de páginas">
            <ul class="pagination pagination-sm">
                {{-- Botón Anterior --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link border-0 bg-transparent text-muted" aria-disabled="true">
                            <i class="bi-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link border-0 bg-transparent text-primary" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                            <i class="bi-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Números de página --}}
                @foreach ($elements as $element)
                    {{-- "Separador" --}}
                    @if (is_string($element))
                        <li class="page-item disabled">
                            <span class="page-link border-0 bg-transparent text-muted">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array de enlaces --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active">
                                    <span class="page-link border-0 bg-primary text-white rounded-circle mx-1" 
                                          style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link border-0 bg-transparent text-muted rounded-circle mx-1" 
                                       href="{{ $url }}"
                                       style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Botón Siguiente --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link border-0 bg-transparent text-primary" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <i class="bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link border-0 bg-transparent text-muted" aria-disabled="true">
                            <i class="bi-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
