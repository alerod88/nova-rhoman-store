@if ($paginator->hasPages())
    <nav class="w-100 mt-4 px-2">
        <div class="d-flex flex-column align-items-center gap-3 w-100">
            
            <div class="d-md-none w-100">
                <div class="d-flex justify-content-between align-items-center gap-2 w-100">
                    {{-- Botón Anterior Celular --}}
                    @if ($paginator->onFirstPage())
                        <button class="btn btn-outline-secondary opacity-50 flex-grow-1 py-2.5 fw-bold text-uppercase" style="font-size: 0.8rem;" disabled>
                            <i class="bi bi-chevron-left me-1"></i> Anterior
                        </button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-dark flex-grow-1 py-2.5 fw-bold text-uppercase" style="font-size: 0.8rem;">
                            <i class="bi bi-chevron-left me-1"></i> Anterior
                        </a>
                    @endif

                    {{-- Botón Siguiente Celular --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-dark flex-grow-1 py-2.5 fw-bold text-uppercase" style="font-size: 0.8rem;">
                            Siguiente <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    @else
                        <button class="btn btn-outline-secondary opacity-50 flex-grow-1 py-2.5 fw-bold text-uppercase" style="font-size: 0.8rem;" disabled>
                            Siguiente <i class="bi bi-chevron-right ms-1"></i>
                        </button>
                    @endif
                </div>
            </div>

            <div class="d-none d-md-block">
                <ul class="pagination mb-0 justify-content-center shadow-2xs rounded">
                    {{-- Botón Anterior --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">&lsaquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
                    @endif

                    {{-- Números --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Botón Siguiente --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
                    @else
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">&rsaquo;</span></li>
                    @endif
                </ul>
            </div>
            
            <div class="text-center mt-1">
                <p class="small text-muted mb-0" style="font-size: 0.85rem; letter-spacing: 0.2px;">
                    Mostrando <span class="fw-bold text-dark">{{ $paginator->firstItem() }}</span> al <span class="fw-bold text-dark">{{ $paginator->lastItem() }}</span> de <span class="fw-bold text-dark">{{ $paginator->total() }}</span> libros
                </p>
            </div>
            
        </div>
    </nav>
@endif