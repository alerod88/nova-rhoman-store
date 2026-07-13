@extends('layouts.app')

@section('content')
<div class="row">
    
    <div class="col-md-3 mb-4">
        
        <div class="d-block d-md-none mb-3">
            <button class="btn btn-outline-primary w-100 py-2.5 fw-bold text-uppercase d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#collapseFiltros" 
                    aria-expanded="false" 
                    aria-controls="collapseFiltros">
                <i class="bi bi-funnel-fill text-primary"></i> 
                <span>Buscar y Filtrar Catálogo</span>
            </button>
        </div>

        <div class="collapse d-md-block sticky-md-top" id="collapseFiltros" style="top: 90px; z-index: 10;">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 1rem;">
                
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center" style="font-size: 1.15rem;">
                    <i class="bi bi-sliders text-primary me-2" style="font-size: 1.25rem;"></i> Filtrar Resultados
                </h5>
                
                <form action="{{ route('catalogo') }}" method="GET" class="d-flex flex-column gap-3">
                    
                    <div>
                        <label class="form-label fw-bold text-secondary small text-uppercase mb-1" style="font-size: 0.75rem;"><small>¿Qué estás buscando?</small></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control bg-light border-start-0" placeholder="Título, palabra clave...">
                        </div>
                    </div>

                    <div>
                        <label class="form-label fw-bold text-secondary small text-uppercase mb-1" style="font-size: 0.75rem;"><small>Por Tema / Colección</small></label>
                        <select name="tema" class="form-select bg-light fw-semibold text-dark" style="font-size: 0.9rem;" onchange="this.form.submit()">
                            <option value="">Todos los temas</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->slug }}" {{ request('tema') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->nombre }} ({{ $cat->libros_count ?? $cat->libros->count() }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label fw-bold text-secondary small text-uppercase mb-1" style="font-size: 0.75rem;"><small>Por Material</small></label>
                        <select name="material" class="form-select bg-light fw-semibold text-dark" style="font-size: 0.9rem;" onchange="this.form.submit()">
                            <option value="">Todos los materiales</option>
                            @foreach($materiales as $mat)
                                <option value="{{ $mat->slug }}" {{ request('material') == $mat->slug ? 'selected' : '' }}>
                                    {{ $mat->nombre }} ({{ $mat->libros_count ?? $mat->libros->count() }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        @if(request('buscar') || request('tema') || request('material'))
                            <a href="{{ route('catalogo') }}" class="btn btn-light border flex-grow-1 py-2 fw-bold small text-uppercase text-danger" style="font-size: 0.75rem;">
                                Limpiar
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-bold small text-uppercase" style="font-size: 0.75rem;">
                            Buscar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="bg-white p-4 rounded shadow-sm mb-4" style="border-radius: 1rem !important;">
            <h3 class="fw-bold text-primary text-uppercase mb-1" style="font-size: 1.3rem; letter-spacing: -0.3px;">
                @if(request('tema'))
                    {{ $categorias->firstWhere('slug', request('tema'))->nombre ?? 'Tema Seleccionado' }}
                @elseif(request('material'))
                    {{ $materiales->firstWhere('slug', request('material'))->nombre ?? 'Material Seleccionado' }}
                @elseif(request('buscar'))
                    Resultados para: "{{ request('buscar') }}"
                @else
                    Nuestro Catálogo Completo
                @endif
            </h3>
            <p class="text-muted mb-0"><small>Se encontraron {{ $libros->total() }} títulos disponibles en total.</small></p>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach($libros as $libro)
                <div class="col">
                    <div class="card h-100 producto-card shadow-sm border-0 bg-white rounded-3 overflow-hidden position-relative">
                        
                        <div class="p-3 text-center bg-light position-relative">
                            
                            @if(!empty($libro->caracteristicas) && isset($libro->caracteristicas['Etiqueta']))
                                @php 
                                    $tag = trim($libro->caracteristicas['Etiqueta']);
                                    $colorBg = 'bg-primary'; // Azul base
                                    
                                    if (strcasecmp($tag, 'Novedad') === 0 || strcasecmp($tag, 'Novedades') === 0) { 
                                        $colorBg = 'bg-danger text-white'; // Rojo
                                    } elseif (strcasecmp($tag, 'Próximamente') === 0 || strcasecmp($tag, 'Proximamente') === 0) { 
                                        $colorBg = 'bg-warning text-dark fw-bold'; // Amarillo
                                    } elseif (strcasecmp($tag, 'Oferta') === 0) {
                                        $colorBg = 'bg-primary text-white'; // Azul
                                    } elseif (strcasecmp($tag, 'Agotado') === 0) {
                                        $colorBg = 'bg-dark text-white fw-bold'; // Negro para Agotados ⚫
                                    }
                                @endphp
                                
                                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                    <span class="badge {{ $colorBg }} shadow-sm px-2.5 py-1.5 rounded-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        <i class="bi bi-bookmark-star-fill me-1"></i>{{ $tag }}
                                    </span>
                                </div>
                            @endif

                            <img src="{{ asset('storage/libros/' . ($libro->imagenes->first()->ruta_imagen ?? 'default.jpg')) }}" 
                                 class="img-fluid rounded-2" 
                                 style="max-height: 180px; object-fit: contain;">
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between text-center">
                            <h6 class="fw-bold text-dark text-uppercase mb-3" style="font-size: 0.9rem; line-height: 1.3;">
                                {{ Str::limit($libro->titulo, 45) }}
                            </h6>
                            
                            <a href="{{ route('libro.detalle', $libro->id) }}" class="btn btn-primary btn-sm fw-bold w-100 py-2 text-uppercase stretched-link">
                                Ver Detalles
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center my-5">
            {{ $libros->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.2s ease-in-out !important;
    }
    .card-filter-item:hover {
        background-color: #f8f9fa !important;
        color: #1b3d81 !important; 
        padding-left: 1.15rem !important; 
    }
</style>
@endsection