@if ($paginator->hasPages())
    <div class="pagination-wrapper">


        {{-- Navegación de páginas --}}
        <nav class="d-flex justify-content-center" aria-label="Navegación de páginas">
            <ul class="pagination pagination-sm mb-0">
                {{-- Botón Primera Página --}}
                @if ($paginator->currentPage() > 3)
                    <li class="page-item">
                        <a class="page-link border-0 bg-transparent text-primary" href="{{ $paginator->url(1) }}" aria-label="Primera página">
                            <i class="bi-chevron-double-left"></i>
                        </a>
                    </li>
                @endif

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
                                          style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link border-0 bg-transparent text-muted rounded-circle mx-1" 
                                       href="{{ $url }}"
                                       style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
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

                {{-- Botón Última Página --}}
                @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                    <li class="page-item">
                        <a class="page-link border-0 bg-transparent text-primary" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Última página">
                            <i class="bi-chevron-double-right"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        {{-- Navegación rápida --}}
        @if ($paginator->lastPage() > 5)
            <div class="pagination-quick-nav text-center mt-3">
                <small class="text-muted">Ir a página:</small>
                <div class="d-inline-flex align-items-center ms-2">
                    <input type="number" 
                           class="form-control form-control-sm" 
                           style="width: 60px; height: 30px; margin: 0 8px;"
                           min="1" 
                           max="{{ $paginator->lastPage() }}" 
                           value="{{ $paginator->currentPage() }}"
                           onchange="goToPage(this.value, {{ $paginator->lastPage() }})">
                    <button class="btn btn-sm btn-outline-primary" 
                            onclick="goToPage(document.querySelector('.pagination-quick-nav input').value, {{ $paginator->lastPage() }})">
                        Ir
                    </button>
                </div>
            </div>
        @endif
    </div>


@endif
