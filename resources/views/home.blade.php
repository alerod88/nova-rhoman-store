@extends('layouts.app')

@section('content')

<div id="homeCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    
    <div class="carousel-indicators custom-indicators">
        @foreach($banners as $index => $banner)
            <button type="button" 
                    data-bs-target="#homeCarousel" 
                    data-bs-slide-to="{{ $index }}" 
                    class="{{ $index == 0 ? 'active' : '' }}" 
                    aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}">
            </button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                @php
                    $urlDestino = null;
                    if (!empty($banner->enlace_personalizado)) {
                        $urlDestino = $banner->enlace_personalizado;
                    } elseif (!empty($banner->libro_id)) {
                        $urlDestino = route('libro.detalle', $banner->libro_id);
                    }
                @endphp

                @if($urlDestino)
                    <a href="{{ $urlDestino }}" class="d-block text-decoration-none content-banner-link">
                @endif

                {{-- 🖥️ VERSIÓN ESCRITORIO: No deforma --}}
                <div class="banner-image-container-desktop d-none d-md-block position-relative" style="background-color: #f8f9fa;">
                    <img src="{{ asset('storage/banners/' . $banner->ruta_imagen) }}" class="w-100 img-fluid" style="height: auto; object-fit: contain;" alt="{{ $banner->titulo }}">
                </div>

                {{-- 📱 VERSIÓN MOBILE: Cuadrados enteros --}}
                <div class="banner-image-container-mobile d-block d-md-none position-relative" style="background-color: #f8f9fa;">
                    <img src="{{ asset('storage/banners/' . ($banner->ruta_imagen_mobile ?? $banner->ruta_imagen)) }}" class="w-100 img-fluid" style="height: auto; object-fit: contain;" alt="{{ $banner->titulo }}">
                </div>

                @if(!empty($banner->titulo) || !empty($banner->subtitulo))
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100 align-items-start text-start px-4 px-md-5">
                        @if(!empty($banner->titulo))
                            <h1 class="fw-bold text-white text-uppercase mb-2 drop-shadow">{{ $banner->titulo }}</h1>
                        @endif
                        @if(!empty($banner->subtitulo))
                            <p class="text-white fs-5 opacity-90 drop-shadow d-none d-sm-block">{{ $banner->subtitulo }}</p>
                        @endif
                    </div>
                @endif

                @if($urlDestino)
                    </a>
                @endif
            </div>
        @endforeach
    </div>

    <button class="carousel-control-prev custom-nav-btn" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
        <span class="custom-nav-icon" aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next custom-nav-btn" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
        <span class="custom-nav-icon" aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>

<div class="container my-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center flex-grow-1 gap-3">
            <h3 class="text-primary fw-bold text-uppercase mb-0" style="letter-spacing: 0.5px; font-size: 1.4rem; white-space: nowrap;">
                <span class="text-warning me-2">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </span>
                Libros Recomendados
            </h3>
            <div class="flex-grow-1 border-bottom opacity-10" style="height: 1px; margin-top: 4px;"></div>
        </div>
        
        @auth
            <div class="d-flex gap-2 align-items-center bg-light p-2 rounded border shadow-2xs">
                <span class="badge bg-danger text-uppercase px-2 py-1 small" style="font-size:0.7rem;"><i class="bi bi-pencil-square me-1"></i>Modo Editor</span>
                <button type="button" class="btn btn-xs btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalAgregarDestacado">
                    <i class="bi bi-plus-circle-fill me-1"></i> Vincular Libro
                </button>
                <button type="button" class="btn btn-xs btn-success fw-bold" onclick="guardarPosicionesEditor()">
                    <i class="bi bi-check-circle-fill me-1"></i> Guardar Cambios
                </button>
            </div>
        @endauth
    </div>
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4" id="contenedor-destacados-grilla">
        @foreach($librosDestacados as $libro)
            <div class="col item-tarjeta-portada" data-id="{{ $libro->id }}" style="{{ Auth::check() ? 'cursor: move;' : '' }}">
                <div class="card h-100 producto-card shadow-sm border-0 bg-white rounded-3 overflow-hidden position-relative">
                    
                    @auth
                        <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 shadow-sm" 
                                style="z-index: 20; width:30px; height:30px; padding:0; display:flex; align-items:center; justify-content:center;"
                                onclick="removerTarjetaEditor(this)">
                            <i class="bi bi-x-lg" style="font-size: 0.8rem;"></i>
                        </button>
                    @endauth

                    <div class="p-3 text-center bg-light position-relative" style="height: 240px; display: flex; align-items: center; justify-content: center;">
                        @if(!empty($libro->caracteristicas) && isset($libro->caracteristicas['Etiqueta']))
                            @php 
                                $tag = trim($libro->caracteristicas['Etiqueta']);
                                $colorBg = 'bg-primary'; 
                                if (strcasecmp($tag, 'Novedad') === 0 || strcasecmp($tag, 'Novedades') === 0) { $colorBg = 'bg-danger text-white'; }
                                elseif (strcasecmp($tag, 'Próximamente') === 0 || strcasecmp($tag, 'Proximamente') === 0) { $colorBg = 'bg-warning text-dark fw-bold'; }
                                elseif (strcasecmp($tag, 'Oferta') === 0) { $colorBg = 'bg-primary text-white'; }
                                elseif (strcasecmp($tag, 'Agotado') === 0) { $colorBg = 'bg-dark text-white fw-bold'; }
                            @endphp
                            <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                <span class="badge {{ $colorBg }} shadow-sm px-2.5 py-1.5 rounded-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <i class="bi bi-bookmark-star-fill me-1"></i>{{ $tag }}
                                </span>
                            </div>
                        @endif

                        @if($libro->imagenes && $libro->imagenes->count() > 0)
                            <img src="{{ asset('storage/libros/' . ($libro->imagenes->first()->ruta_imagen ?? 'default.jpg')) }}" class="img-fluid rounded-2" style="max-height: 100%; max-width: 100%; object-fit: contain;" alt="{{ $libro->titulo }}">
                        @elseif($libro->ruta_imagen) 
                            <img src="{{ asset('storage/libros/' . $libro->ruta_imagen) }}" class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;" alt="{{ $libro->titulo }}">
                        @else
                            <img src="{{ asset('storage/libros/default.jpg') }}" class="img-fluid rounded-2 opacity-50" style="max-height: 140px; object-fit: contain;" alt="Sin imagen">
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between text-center">
                        <h6 class="fw-bold text-dark text-uppercase mb-3" style="font-size: 0.9rem; line-height: 1.3; height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $libro->titulo }}
                        </h6>
                        <div class="mt-3">
                            <a href="{{ route('libro.detalle', $libro->id) }}" class="btn btn-primary btn-sm fw-bold w-100 py-2 text-uppercase {{ Auth::check() ? 'disabled' : 'stretched-link' }}">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@auth
<div class="modal fade" id="modalAgregarDestacado" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-dark">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-journal-plus me-2"></i>Vincular Libro a Destacados</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted mb-3">Buscá un libro por su título e inyectalo en la grilla de novedades.</p>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-primary"><i class="bi bi-search me-1"></i>Buscar por nombre</label>
                    <input type="text" id="input-buscador-modal" class="form-control" placeholder="Escribí parte del título para filtrar...">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Libros encontrados</label>
                    <select id="select-editor-catalogo" class="form-select fw-bold" size="5" style="max-height: 200px;">
                        <option value="" selected disabled>-- Cargando catálogo... --</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-sm btn-primary fw-bold px-4" onclick="inyectarTarjetaDesdeModal()">
                    <i class="bi bi-plus-lg me-1"></i>Añadir a la grilla
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let catalogoCompletoBooks = [];
    const select = document.getElementById('select-editor-catalogo');
    const buscador = document.getElementById('input-buscador-modal');
    const contenedorGrilla = document.getElementById('contenedor-destacados-grilla');

    fetch("/admin/destacados/datos")
    .then(res => res.json())
    .then(data => {
        catalogoCompletoBooks = data.todos;
        renderizarOpcionesSelect(data.todos);
    });

    function renderizarOpcionesSelect(libros) {
        if(libros.length === 0) {
            select.innerHTML = '<option value="" disabled>No se encontraron libros</option>';
            return;
        }
        let html = '';
        libros.forEach((libro, idx) => {
            html += `<option value="${libro.id}" ${idx === 0 ? 'selected' : ''}>${libro.titulo}</option>`;
        });
        select.innerHTML = html;
    }

    buscador.addEventListener('input', function() {
        const busqueda = this.value.toLowerCase().trim();
        if (busqueda === '') {
            renderizarOpcionesSelect(catalogoCompletoBooks);
            return;
        }
        const librosFiltrados = catalogoCompletoBooks.filter(libro => 
            libro.titulo.toLowerCase().includes(busqueda)
        );
        renderizarOpcionesSelect(librosFiltrados);
    });

    new Sortable(contenedorGrilla, { animation: 150, ghostClass: 'bg-primary-subtle' });

    window.inyectarTarjetaDesdeModal = function() {
        const idSelected = select.value;
        if(!idSelected) return;
        if(contenedorGrilla.querySelector(`[data-id="${idSelected}"]`)) {
            alert("Este libro ya se encuentra posicionado en la grilla actual.");
            return;
        }
        const book = catalogoCompletoBooks.find(l => l.id == idSelected);
        if(!book) return;
        let fotoFinal = '/storage/libros/default.jpg';
        if (book.imagenes && book.imagenes.length > 0) { fotoFinal = `/storage/libros/${book.imagenes[0].ruta_imagen}`; }

        const divCol = document.createElement('div');
        divCol.className = 'col item-tarjeta-portada';
        divCol.setAttribute('data-id', book.id);
        divCol.style.cursor = 'move';
        divCol.innerHTML = `
            <div class="card h-100 producto-card shadow-sm border-0 bg-white rounded-3 overflow-hidden position-relative">
                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 shadow-sm" style="z-index: 20; width:30px; height:30px; padding:0; display:flex; align-items:center; justify-content:center;" onclick="removerTarjetaEditor(this)"><i class="bi bi-x-lg" style="font-size: 0.8rem;"></i></button>
                <div class="p-3 text-center bg-light" style="height: 240px; display: flex; align-items: center; justify-content: center;"><img src="${fotoFinal}" class="img-fluid rounded-2" style="max-height: 100%; max-width: 100%; object-fit: contain;" alt="${book.titulo}"></div>
                <div class="card-body d-flex flex-column justify-content-between text-center">
                    <h6 class="fw-bold text-dark text-uppercase mb-3" style="font-size: 0.9rem; line-height: 1.3; height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${book.titulo}</h6>
                    <div class="mt-3"><a href="#" class="btn btn-primary btn-sm fw-bold w-100 py-2 text-uppercase disabled">Ver Detalles</a></div>
                </div>
            </div>
        `;
        contenedorGrilla.appendChild(divCol);
        bootstrap.Modal.getInstance(document.getElementById('modalAgregarDestacado')).hide();
        buscador.value = '';
        renderizarOpcionesSelect(catalogoCompletoBooks);
    }

    window.removerTarjetaEditor = function(btn) { btn.closest('.item-tarjeta-portada').remove(); }

    window.guardarPosicionesEditor = function() {
        const tarjetas = document.getElementById('contenedor-destacados-grilla').querySelectorAll('.item-tarjeta-portada');
        const listaIds = Array.from(tarjetas).map(t => t.getAttribute('data-id'));
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

        fetch("/admin/destacados/guardar", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": token, "X-Requested-With": "XMLHttpRequest" },
            body: JSON.stringify({ seccion: 'grilla', items: listaIds })
        })
        .then(res => {
            if (res.status === 419) throw new Error("La sesión expiró. Recargá (F5).");
            if (!res.ok) throw new Error("Error en el servidor (Código " + res.status + ")");
            return res.json();
        })
        .then(data => {
            if(data.status === 'success') { alert("¡Grilla guardada con éxito!"); location.reload(); } 
            else { alert("Hubo un problema: " + data.message); }
        })
        .catch(err => { alert(err.message); });
    }
});
</script>
@endauth

<div class="container my-5">
    <div class="w-100 rounded-3 overflow-hidden shadow-sm text-white position-relative entry-banner" style="background-color: #1b3d81; min-height: 140px; transition: all 0.2s;">
        <a href="{{ route('catalogo') }}" class="text-decoration-none text-white d-block h-100">
            <div class="row g-0 align-items-center h-100">
                <div class="col-sm-6 d-none d-sm-flex align-items-center ps-4 py-2 position-relative overflow-hidden" style="height: 140px;">
                    <div class="rounded-circle bg-white shadow-sm position-absolute circle-item" style="width: 110px; height: 110px; left: 20px; z-index: 1; overflow: hidden; border: 3px solid #ffffff; transition: all 0.25s;"><img src="{{ asset('storage/detallebanner/principito_full1.jpg') }}" class="w-100 h-100" style="object-fit: cover; transform: scale(1.1);"></div>
                    <div class="rounded-circle bg-white shadow-sm position-absolute circle-item" style="width: 110px; height: 110px; left: 100px; z-index: 2; overflow: hidden; border: 3px solid #ffffff; transition: all 0.25s;"><img src="{{ asset('storage/detallebanner/somos_tapa_mockups.jpg') }}" class="w-100 h-100" style="object-fit: cover; transform: scale(1.15);"></div>
                    <div class="rounded-circle bg-white shadow-sm position-absolute circle-item" style="width: 110px; height: 110px; left: 180px; z-index: 3; overflow: hidden; border: 3px solid #ffffff; transition: all 0.25s;"><img src="{{ asset('storage/detallebanner/zenonsonidos_full1.jpg') }}" class="w-100 h-100" style="object-fit: cover; transform: scale(1.15);"></div>
                </div>
                <div class="col-12 col-sm-6 text-center text-sm-end pe-sm-5 py-4 py-sm-0">
                    <div class="text-uppercase fw-bold" style="font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: 0.5px; line-height: 1.2;">
                        <span class="d-block" style="font-size: 1.1rem; color: rgba(255,255,255,0.85);">CLICK AQUÍ PARA</span>
                        <span class="d-block" style="font-size: 2.1rem; color: #ffffff;">VER EL CATÁLOGO</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="container my-5">
    <div class="w-100 rounded-3 overflow-hidden shadow-sm text-white position-relative contact-entry-banner" style="background-color: #2c5ebd; min-height: 140px; transition: all 0.2s;">
        <a href="{{ route('contacto') }}" class="text-decoration-none text-white d-block h-100">
            <div class="row g-0 align-items-center h-100">
                <div class="col-sm-6 d-none d-sm-flex align-items-center ps-4 py-2 position-relative overflow-hidden" style="height: 140px;">
                    <div class="rounded-circle shadow-sm position-absolute contact-circle-item d-flex align-items-center justify-content-center" style="width: 110px; height: 110px; left: 20px; z-index: 1; border: 3px solid #ffffff; background-color: #ffffff; color: #2c5ebd; transition: all 0.25s;"><i class="bi bi-telephone-fill fs-2"></i></div>
                    <div class="rounded-circle shadow-sm position-absolute contact-circle-item d-flex align-items-center justify-content-center" style="width: 110px; height: 110px; left: 100px; z-index: 2; border: 3px solid #ffffff; background-color: #1b3d81; color: #ffffff; transition: all 0.25s;"><i class="bi bi-envelope-open-fill fs-2"></i></div>
                    <div class="rounded-circle shadow-sm position-absolute contact-circle-item d-flex align-items-center justify-content-center" style="width: 110px; height: 110px; left: 180px; z-index: 3; border: 3px solid #ffffff; background-color: #ffffff; color: #1b3d81; transition: all 0.25s;"><i class="bi bi-geo-alt-fill fs-2"></i></div>
                </div>
                <div class="col-12 col-sm-6 text-center text-sm-end pe-sm-5 py-4 py-sm-0">
                    <div class="text-uppercase fw-bold" style="font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: 0.5px; line-height: 1.2;">
                        <span class="d-block" style="font-size: 1.1rem; color: rgba(255,255,255,0.85);">¿TENÉS ALGUNA DUDA?</span>
                        <span class="d-block" style="font-size: 2.1rem; color: #ffffff;">CONTACTANOS AQUÍ</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    /* BANNERS ULTRA-FLUIDOS */
    .banner-image-container-desktop { position: relative; width: 100%; overflow: hidden; background-color: #f8f9fa; }
    .banner-image-container-desktop img { width: 100%; height: auto; display: block; transition: transform 0.4s ease; }

    .banner-image-container-mobile { position: relative; width: 100%; overflow: hidden; background-color: #f8f9fa; }
    .banner-image-container-mobile img { width: 100%; height: auto; display: block; transition: transform 0.4s ease; }

    .content-banner-link:hover .banner-image-container-desktop img,
    .content-banner-link:hover .banner-image-container-mobile img { transform: scale(1.01); }

    .banner-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.15) 50%, transparent 100%); }
    .drop-shadow { text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.6); }
    
    .producto-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .producto-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important; }
    
    .contact-entry-banner:hover { background-color: #1b3d81 !important; transform: scale(1.005); }
    .contact-entry-banner:hover .contact-circle-item { transform: scale(1.05); }
    
    .entry-banner:hover { background-color: #2c5ebd !important; transform: scale(1.005); }
    .entry-banner:hover .circle-item { transform: scale(1.05); }

    @media (max-width: 768px) { .carousel-caption h1 { font-size: 1.5rem !important; } }

    /* 🌟 ESTILOS PREMIUM: LÍNEAS INDICADORAS */
    .custom-indicators { bottom: 15px; gap: 8px; z-index: 25; }
    .custom-indicators button {
        width: 25px !important; height: 5px !important; border-radius: 10px !important;
        background-color: rgba(255, 255, 255, 0.4) !important; border: none !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
    }
    .custom-indicators .active { width: 45px !important; background-color: #ffffff !important; opacity: 1 !important; }
    .custom-indicators button:hover { background-color: rgba(255, 255, 255, 0.8) !important; }

    /* 🏹 ESTILOS PREMIUM: FLECHAS LATERALES PERSONALIZADAS */
    .custom-nav-btn { width: 6%; opacity: 0; transition: all 0.3s ease; z-index: 26; }
    #homeCarousel:hover .custom-nav-btn { opacity: 1; } /* Las flechas flotan elegantemente solo al hacer hover en el slider */
    
    .custom-nav-icon {
        width: 46px !important;
        height: 46px !important;
        min-width: 46px !important;  /* 🔒 Evita que Bootstrap lo aplaste a lo ancho */
        min-height: 46px !important; /* 🔒 Evita que se estire a lo alto */
        flex-shrink: 0 !important;   /* 🔒 Bloquea cualquier deformación por flexbox */
        
        background-color: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50% !important; /* Fuerza el círculo perfecto */
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #ffffff;
        font-size: 1.3rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        transition: all 0.2s ease-in-out;
    }
    .custom-nav-btn:hover .custom-nav-icon {
        background-color: #1b3d81; /* Toma el azul institucional al apoyar el mouse */
        border-color: #1b3d81; transform: scale(1.1); color: #ffffff;
    }
</style>
@endsection