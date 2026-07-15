@extends('layouts.app')

{{-- Google indexará el título exacto del libro --}}
@section('meta_title', $libro->titulo . ' - Editorial Nova Rhoman')

{{-- Google usará los primeros 150 caracteres de tu descripción eliminando etiquetas HTML --}}
@section('meta_description', Str::limit(strip_tags($libro->descripcion), 150))

@section('content')
<div class="container my-5 text-dark">
    <div class="bg-white p-4 p-lg-5 rounded-4 shadow-sm border-0 mb-4">
        <div class="row g-5">
            
            <div class="col-12 col-lg-5 text-center">
                <div class="position-sticky" style="top: 100px; z-index: 10;">
                    
                    <div class="p-3 bg-light rounded-4 border mb-3 shadow-2xs position-relative overflow-hidden galeria-principal-wrapper" 
                         onclick="abrirLightbox()" 
                         title="Hacé click para agrandar la imagen" 
                         style="cursor: zoom-in;">
                        
                        @if(!empty($libro->caracteristicas) && isset($libro->caracteristicas['Etiqueta']))
                            @php 
                                $tag = trim($libro->caracteristicas['Etiqueta']);
                                $colorBg = 'bg-primary';
                                if (strcasecmp($tag, 'Novedad') === 0 || strcasecmp($tag, 'Novedades') === 0) { 
                                    $colorBg = 'bg-danger text-white'; 
                                } elseif (strcasecmp($tag, 'Próximamente') === 0 || strcasecmp($tag, 'Proximamente') === 0) { 
                                    $colorBg = 'bg-warning text-dark fw-bold'; 
                                } elseif (strcasecmp($tag, 'Oferta') === 0) {
                                    $colorBg = 'bg-primary text-white'; 
                                } elseif (strcasecmp($tag, 'Agotado') === 0) {
                                    $colorBg = 'bg-dark text-white fw-bold'; 
                                }
                            @endphp
                            <div class="position-absolute top-0 start-0 m-3" style="z-index: 20;">
                                <span class="badge {{ $colorBg }} shadow px-3 py-2 rounded-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="bi bi-bookmark-star-fill me-1"></i>{{ $tag }}
                                </span>
                            </div>
                        @endif

                        <div class="position-absolute bottom-0 end-0 m-3 bg-dark bg-opacity-70 text-white rounded-circle d-flex align-items-center justify-content-center visual-zoom-icon" style="width: 36px; height: 36px; z-index: 20; opacity: 0; transition: opacity 0.2s;">
                            <i class="bi bi-zoom-in"></i>
                        </div>

                        <img src="{{ asset('storage/libros/' . ($libro->imagenes->first()->ruta_imagen ?? 'default.jpg')) }}" 
                             id="vista-principal-libro" 
                             class="img-fluid rounded-3 img-principal-animada" 
                             style="max-height: 500px; object-fit: contain; filter: drop-shadow(0 10px 8px rgba(0,0,0,0.15)); transition: transform 0.3s ease;">
                    </div>
                    
                    @if($libro->imagenes->count() > 1)
                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-3" id="contenedor-miniaturas-folleto">
                            @foreach($libro->imagenes as $idx => $img)
                                <div class="p-1 bg-white border rounded-3 miniatura-click-wrapper {{ $idx == 0 ? 'miniatura-activa' : '' }}" 
                                     style="cursor: pointer; width: 65px; transition: all 0.2s;"
                                     data-index="{{ $idx }}"
                                     onclick="cambiarImagenPrincipal(this, '{{ asset('storage/libros/' . $img->ruta_imagen) }}')">
                                    <img src="{{ asset('storage/libros/' . $img->ruta_imagen) }}" 
                                         class="img-fluid rounded-2 img-complementaria-folleto" 
                                         style="max-height: 55px; object-fit: contain;">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- 🖥️ Se activa el Flexbox elástico solo de pantallas medianas (md) en adelante -->
            <div class="col-12 col-lg-7 d-flex flex-column justify-content-md-between gap-3">
                
                <div>
                    <div class="mb-2 user-select-none">
                        @if(isset($libro->categorias))
                            @foreach($libro->categorias as $cat)
                                <span class="badge text-uppercase mb-1 me-1 text-white shadow-sm folleto-tag-categoria">
                                    <i class="bi bi-tag-fill me-1 opacity-75"></i>{{ $cat->nombre }}
                                </span>
                            @endforeach
                        @endif

                        @if(isset($libro->materiales))
                            @foreach($libro->materiales as $mat)
                                <span class="badge text-uppercase mb-1 me-1 text-primary shadow-sm folleto-tag-material">
                                    <i class="bi bi-layers-fill me-1 opacity-75"></i>{{ $mat->nombre }}
                                </span>
                            @endforeach
                        @endif
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <h1 id="folleto-titulo" class="fw-bold text-primary my-0" style="font-size: 2.1rem; line-height: 1.2;">
                            {{ $libro->titulo }}
                        </h1>
                    </div>

                    @if(!empty($libro->subtitulo))
                        <h4 id="folleto-subtitulo" class="text-secondary fw-bold border-bottom pb-2 mb-2" style="font-size: 1.1rem;">
                            {{ $libro->subtitulo }}
                        </h4>
                    @else
                        <span id="folleto-subtitulo" style="display: none;"></span>
                    @endif

                    <p id="folleto-descripcion" class="text-muted mb-3 lead" style="font-size: 0.95rem; line-height: 1.5;">
                        {!! nl2br(e($libro->descripcion)) !!}
                    </p>
                </div>

                <div class="mb-3 p-3 rounded-4 border" style="background-color: #f8f9fa;">
                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1rem;">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>Ficha Técnica
                    </h5>
                    <div class="d-flex flex-column gap-1.5" id="folleto-especificaciones">
                        @if(!empty($libro->caracteristicas))
                            @foreach($libro->caracteristicas as $clave => $valor)
                                @if($clave !== 'Etiqueta')
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-1.5 data-spec-row" data-clave="{{ $clave }}" data-valor="{{ $valor }}">
                                        <span class="text-secondary fw-bold small" style="font-size: 0.8rem;">{{ $clave }}</span>
                                        <span class="text-dark small text-end" style="max-width: 70%; text-align: right; font-size: 0.85rem;">{{ $valor }}</span>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <span class="text-muted small">No hay especificaciones cargadas.</span>
                        @endif
                    </div>
                </div>

                @php
                    $videoNombreArchivo = null;
                    if (!empty($libro->video_archivo)) {
                        $videoNombreArchivo = basename(str_replace('\\', '/', $libro->video_archivo));
                    }

                    $embedUrl = null;
                    $esYouTube = false;
                    $urlOriginalYouTube = null;
                    
                    if (!empty($libro->video_url) && trim($libro->video_url) !== '') {
                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', trim($libro->video_url), $match)) {
                            $embedUrl = "https://www.youtube.com/embed/" . $match[1];
                            $urlOriginalYouTube = "https://www.youtube.com/watch?v=" . $match[1];
                            $esYouTube = true;
                        } else {
                            $embedUrl = trim($libro->video_url);
                        }
                    }

                    $videoLinkQR = '';
                    if ($videoNombreArchivo) {
                        $videoLinkQR = asset('storage/videos/' . $videoNombreArchivo);
                    } elseif ($esYouTube) {
                        $videoLinkQR = $urlOriginalYouTube;
                    } elseif ($embedUrl) {
                        $videoLinkQR = $embedUrl;
                    }
                @endphp
                <input type="hidden" id="folleto-video-url-raw" value="{{ $videoLinkQR }}">
                <input type="hidden" id="folleto-dominio-produccion" value="https://novarhoman.com">

                @if($videoNombreArchivo || $esYouTube || (!empty($libro->video_url) && trim($libro->video_url) !== ''))
                    <div class="mt-5 border-top pt-4 text-dark">
                        <h4 class="fw-bold text-primary mb-3 text-uppercase">
                            <i class="bi bi-play-btn-fill me-2"></i>Presentación en Video
                        </h4>
                        
                        <div class="row">
                            <div class="col-12 col-lg-10"> 
                                @if($videoNombreArchivo)
                                    <div class="rounded shadow-sm overflow-hidden border bg-black w-100" style="height: auto;">
                                        <video controls class="w-100 d-block" style="height: auto; max-height: 550px; object-fit: contain;">
                                            <source src="{{ asset('storage/videos/' . $videoNombreArchivo) }}" type="video/mp4">
                                            Tu navegador no soporta la reproducción de video.
                                        </video>
                                    </div>
                                @elseif($esYouTube && $embedUrl)
                                    <div class="ratio ratio-16x9 rounded shadow-sm overflow-hidden border bg-black w-100">
                                        <iframe 
                                            src="{{ $embedUrl }}" 
                                            title="Presentación del libro" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen 
                                            style="border: 0; width: 100%; height: 100%;">
                                        </iframe>
                                    </div>
                                @elseif(!empty($libro->video_url))
                                    <div class="ratio ratio-16x9 rounded shadow-sm overflow-hidden border bg-black w-100">
                                        <iframe src="{{ $libro->video_url }}" title="Presentación del libro" allowfullscreen style="border: 0; width: 100%; height: 100%;"></iframe>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row g-3 w-100 mt-4 mb-2">
                    <div class="col-12 col-sm-7">
                        <a href="{{ route('contacto', ['libro' => $libro->titulo]) }}" class="btn btn-primary btn-lg fw-bold py-3 w-100 shadow-sm rounded-3 text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.5px; background-color: #3182ce; border-color: #3182ce;">
                            <i class="bi bi-envelope-fill me-2"></i>Consultar por este Libro
                        </a>
                    </div>
                    <div class="col-12 col-sm-5">
                        <button type="button" onclick="descargarFolletoA4()" class="btn btn-outline-success btn-lg fw-bold py-3 w-100 shadow-sm rounded-3 text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                            <i class="bi bi-cloud-arrow-down-fill me-2"></i>Descargar Folleto
                        </button>
                    </div>
                </div>

                <div class="text-start mt-4">
                    <button onclick="irAtrasConservandoTodo()" class="btn btn-outline-secondary px-3 py-1.5 btn-sm rounded-2 style-btn-volver">
                        <i class="bi bi-arrow-left me-1"></i>Volver al Catálogo
                    </button>
                </div>

            </div>
        </div>
    </div>

    @php
        $categoriaIds = $libro->categorias->pluck('id')->toArray();
        $librosRecomendados = \App\Models\Libro::whereHas('categorias', function($q) use ($categoriaIds) {
                $q->whereIn('categorias.id', $categoriaIds);
            })
            ->where('id', '!=', $libro->id)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        if($librosRecomendados->count() < 4) {
            $faltantes = 4 - $librosRecomendados->count();
            $idsExistentes = $librosRecomendados->pluck('id')->merge([$libro->id])->toArray();
            $relleno = \App\Models\Libro::whereNotIn('id', $idsExistentes)->inRandomOrder()->take($faltantes)->get();
            $librosRecomendados = $librosRecomendados->merge($relleno);
        }
    @endphp

    @if($librosRecomendados->count() > 0)
        <div class="bg-white p-4 p-lg-5 rounded-4 shadow-sm border-0 mt-5">
            <div class="d-flex align-items-center mb-4">
                <h4 class="text-primary fw-bold text-uppercase mb-0" style="letter-spacing: 0.5px; font-size: 1.25rem;">
                    <span class="text-warning me-2"><i class="bi bi-stars"></i></span> Recomendados para vos
                </h4>
                <div class="flex-grow-1 ms-3 border-bottom opacity-10"></div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach($librosRecomendados as $recLibro)
                    <div class="col">
                        <div class="card h-100 producto-card shadow-sm border-0 bg-white rounded-3 overflow-hidden position-relative">
                            
                            <div class="p-3 text-center bg-light position-relative" style="height: 240px; display: flex; align-items: center; justify-content: center;">
                                
                                @if(!empty($recLibro->caracteristicas) && isset($recLibro->caracteristicas['Etiqueta']))
                                    @php 
                                        $recTag = trim($recLibro->caracteristicas['Etiqueta']);
                                        $recColor = 'bg-primary'; 
                                        if (strcasecmp($recTag, 'Novedad') === 0 || strcasecmp($recTag, 'Novedades') === 0) { $recColor = 'bg-danger text-white'; }
                                        elseif (strcasecmp($recTag, 'Próximamente') === 0 || strcasecmp($recTag, 'Proximamente') === 0) { $recColor = 'bg-warning text-dark fw-bold'; }
                                        elseif (strcasecmp($recTag, 'Oferta') === 0) { $recColor = 'bg-primary text-white'; }
                                        elseif (strcasecmp($recTag, 'Agotado') === 0) { $recColor = 'bg-dark text-white fw-bold'; }
                                    @endphp
                                    <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                                        <span class="badge {{ $recColor }} shadow-sm px-2 py-1 rounded-2 text-uppercase" style="font-size: 0.6rem;">
                                            {{ $recTag }}
                                        </span>
                                    </div>
                                @endif

                                <img src="{{ asset('storage/libros/' . ($recLibro->imagenes->first()->ruta_imagen ?? 'default.jpg')) }}" 
                                     class="img-fluid rounded-2" 
                                     style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                     alt="{{ $recLibro->titulo }}">
                            </div>

                            <div class="card-body d-flex flex-column justify-content-between text-center p-3">
                                <h6 class="fw-bold text-dark text-uppercase mb-2 text-truncate-2" style="font-size: 0.85rem; line-height: 1.3; height: 36px;">
                                    {{ $recLibro->titulo }}
                                </h6>
                                <div class="mt-2">
                                    <a href="{{ route('libro.detalle', $recLibro->id) }}" class="btn btn-outline-primary btn-sm fw-bold w-100 py-2 text-uppercase stretched-link" style="font-size: 0.75rem;">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true" style="background-color: rgba(0,0,0,0.92);">
    <button type="button" class="btn btn-cerrar-lightbox-custom" data-bs-dismiss="modal" aria-label="Cerrar" title="Cerrar ventana">
        <i class="bi bi-x-circle-fill"></i>
    </button>

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 position-relative">
            
            <div class="modal-body p-0 position-relative text-center d-flex align-items-center justify-content-center" style="min-height: 400px;">
                
                @if($libro->imagenes->count() > 1)
                    <button class="btn btn-lightbox-nav btn-anterior position-absolute start-0 m-2 rounded-circle" onclick="navegarPasadaLightbox(-1)" title="Foto Anterior">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                @endif

                <img src="" id="img-lightbox-target" class="img-fluid rounded shadow-lg animate-fade-in" style="max-height: 85vh; object-fit: contain; transition: all 0.2s ease;">

                @if($libro->imagenes->count() > 1)
                    <button class="btn btn-lightbox-nav btn-siguiente position-absolute end-0 m-2 rounded-circle" onclick="navegarPasadaLightbox(1)" title="Foto Siguiente">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                @endif
                
                @if($libro->imagenes->count() > 1)
                    <div class="position-absolute bottom-0 mb-3 bg-dark bg-opacity-70 text-white rounded-pill px-3 py-1 small" id="contador-lightbox-fotos" style="z-index: 1050;">
                        1 / {{ $libro->imagenes->count() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script>
let indiceImagenActiva = 0;
let arrayRutasFotos = [];

@foreach($libro->imagenes as $img)
    arrayRutasFotos.push("{{ asset('storage/libros/' . $img->ruta_imagen) }}");
@endforeach

if(arrayRutasFotos.length === 0) {
    arrayRutasFotos.push("{{ asset('storage/libros/default.jpg') }}");
}

function cambiarImagenPrincipal(elementoWrapper, srcNueva) {
    const imgPrincipal = document.getElementById('vista-principal-libro');
    imgPrincipal.style.opacity = '0.3';
    setTimeout(() => {
        imgPrincipal.src = srcNueva;
        imgPrincipal.style.opacity = '1';
    }, 120);

    document.querySelectorAll('.miniatura-click-wrapper').forEach(wrapper => {
        wrapper.classList.remove('miniatura-activa');
    });
    elementoWrapper.classList.add('miniatura-activa');

    const indexLeido = parseInt(elementoWrapper.getAttribute('data-index')) || 0;
    indiceImagenActiva = indexLeido;
}

function abrirLightbox() {
    const srcActual = document.getElementById('vista-principal-libro').src;
    let indexEncontrado = arrayRutasFotos.indexOf(srcActual);
    if(indexEncontrado === -1) indexEncontrado = 0;
    
    indiceImagenActiva = indexEncontrado;
    actualizarImagenEnLightbox();
    
    const myModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    myModal.show();
}

function navegarPasadaLightbox(direccion) {
    indiceImagenActiva += direccion;
    if(indiceImagenActiva >= arrayRutasFotos.length) { indiceImagenActiva = 0; } 
    else if(indiceImagenActiva < 0) { indiceImagenActiva = arrayRutasFotos.length - 1; }
    actualizarImagenEnLightbox();
    sincronizarMiniaturaDesdeLightbox(indiceImagenActiva);
}

function actualizarImagenEnLightbox() {
    const targetImg = document.getElementById('img-lightbox-target');
    targetImg.style.transform = 'scale(0.95)'; targetImg.style.opacity = '0.5';
    setTimeout(() => {
        targetImg.src = arrayRutasFotos[indiceImagenActiva];
        targetImg.style.transform = 'scale(1)'; targetImg.style.opacity = '1';
        const contador = document.getElementById('contador-lightbox-fotos');
        if(contador) { contador.innerText = (indiceImagenActiva + 1) + " / " + arrayRutasFotos.length; }
    }, 100);
}

function sincronizarMiniaturaDesdeLightbox(idx) {
    const miniaturaTarget = document.querySelector(`.miniatura-click-wrapper[data-index="${idx}"]`);
    if(miniaturaTarget) {
        document.getElementById('vista-principal-libro').src = arrayRutasFotos[idx];
        document.querySelectorAll('.miniatura-click-wrapper').forEach(w => w.classList.remove('miniatura-activa'));
        miniaturaTarget.classList.add('miniatura-activa');
    }
}

document.addEventListener('keydown', function(e) {
    const modalEl = document.getElementById('lightboxModal');
    if(!modalEl || !modalEl.classList.contains('show')) return;
    if(e.key === 'ArrowRight' || e.key === 'Right') { navegarPasadaLightbox(1); } 
    else if(e.key === 'ArrowLeft' || e.key === 'Left') { navegarPasadaLightbox(-1); }
});

function irAtrasConservandoTodo() {
    if (document.referrer && document.referrer.includes(window.location.host)) { history.back(); } 
    else { window.location.href = "/catalogo"; }
}

function drawImageProp(ctx, img, x, y, w, h) {
    const imgRatio = img.width / img.height; const targetRatio = w / h; let dWidth, dHeight, dx, dy;
    if (imgRatio > targetRatio) { dWidth = w; dHeight = w / imgRatio; dx = x; dy = y + (h - dHeight) / 2; } 
    else { dHeight = h; dWidth = h * imgRatio; dx = x + (w - dWidth) / 2; dy = y; }
    ctx.drawImage(img, dx, dy, dWidth, dHeight);
}

function ajustarTextoCanvas(ctx, texto, maxWidth) {
    const lineasCompletas = [];
    const parrafos = (texto || '').split(/\r?\n/);
    
    parrafos.forEach((parrafo) => {
        if(parrafo.trim() === '') {
            lineasCompletas.push('');
            return;
        }
        const palabras = parrafo.split(' ');
        let lineaActual = '';
        palabras.forEach((palabra) => {
            const testLinea = lineaActual + palabra + ' ';
            if (ctx.measureText(testLinea).width > maxWidth && lineaActual !== '') {
                lineasCompletas.push(lineaActual.trim());
                lineaActual = palabra + ' ';
            } else {
                lineaActual = testLinea;
            }
        });
        if (lineaActual.trim() !== '') lineasCompletas.push(lineaActual.trim());
    });
    return lineasCompletas;
}

function resolverLinkParaQR(url) {
    if (!url) return '';
    try {
        let cleanUrl = url.trim();
        if (cleanUrl.includes('127.0.0.1') || cleanUrl.includes('localhost')) {
            const urlObj = new URL(cleanUrl, window.location.origin);
            const dominioProduccion = document.getElementById('folleto-dominio-produccion').value.replace(/\/$/, '');
            cleanUrl = dominioProduccion + urlObj.pathname + urlObj.search;
        }
        return cleanUrl;
    } catch (e) { return url; }
}

function detonarDescarga(canvas, tituloText) {
    const link = document.createElement('a'); 
    link.download = tituloText.toLowerCase().replace(/[^a-z0-9]/g, '-') + '-ficha-tecnica.jpg';
    link.href = canvas.toDataURL('image/jpeg', 1.0); 
    link.click();
}

function descargarFolletoA4() {
    const tituloText = document.getElementById('folleto-titulo').innerText.trim();
    const subtituloEl = document.getElementById('folleto-subtitulo');
    const subtituloText = subtituloEl ? subtituloEl.innerText.trim() : '';
    
    const descripcionText = document.getElementById('folleto-descripcion').innerText.trim();
    const rawVideoUrl = document.getElementById('folleto-video-url-raw').value.trim();
    const categoriasBadges = Array.from(document.querySelectorAll('.folleto-tag-categoria')).map(el => el.innerText.trim().toUpperCase());
    const materialesBadges = Array.from(document.querySelectorAll('.folleto-tag-material')).map(el => el.innerText.trim().toUpperCase());
    
    const specsData = [];
    document.querySelectorAll('.data-spec-row').forEach((row) => {
        // 🌟 CORREGIDO: Cambiado el corchete roto ']' por paréntesis correcto ')'
        specsData.push({ clave: row.getAttribute('data-clave') || '', valor: row.getAttribute('data-valor') || '' });
    });
    
    const miniaturaPrimera = document.querySelector('.img-complementaria-folleto');
    const rutaFotoPortadaFija = miniaturaPrimera ? miniaturaPrimera.src : document.getElementById('vista-principal-libro').src;
    const miniaturasEls = Array.from(document.querySelectorAll('.img-complementaria-folleto'));
    const imagesSecundarias = miniaturasEls.slice(1).map(el => el.src);
    
    const FONT_FAMILY = 'system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif';
    const FONT_SERIF = 'Georgia, serif';

    const canvasMed = document.createElement('canvas'); 
    const ctxMed = canvasMed.getContext('2d');
    
    const yBadges = 95; 
    const yInicioTitulo = yBadges + 38; 

    ctxMed.font = 'bold 24px ' + FONT_FAMILY;
    const lineasTitulo = ajustarTextoCanvas(ctxMed, tituloText.toUpperCase(), 700);
    const alturaTituloCalculada = lineasTitulo.length * 32;
    
    const yDivisor = yInicioTitulo + alturaTituloCalculada + 12; 
    const yInicioContenido = yDivisor + 35; 

    const specsFiltrados = specsData.filter(s => s.clave !== 'Etiqueta' && s.valor !== '');
    const requiereDosFilasSpecs = specsFiltrados.length > 3;
    const alturaFichaTecnica = requiereDosFilasSpecs ? 130 : 85; 

    const parrafosRaw = descripcionText.split(/\r?\n/);

    const imgLibro = new Image(); 
    imgLibro.crossOrigin = 'anonymous'; 
    imgLibro.src = rutaFotoPortadaFija; 
    
    imgLibro.onload = function () {
        const esImagenVertical = imgLibro.height / imgLibro.width > 1.1;
        
        let anchoImagenPortada, alturaImagenPortada, xImagenPortada;
        let lineasSubtitulo = [];
        let lineasDescripcion = [];
        let yInicioDescripcion;
        let yFinContenidoPrincipal;
        
        ctxMed.font = 'bold 16px ' + FONT_SERIF;
        const anchoMaxText = esImagenVertical ? 700 : 340;
        if (subtituloText !== '') {
            lineasSubtitulo = ajustarTextoCanvas(ctxMed, subtituloText, anchoMaxText);
        }

        if (esImagenVertical) {
            anchoImagenPortada = 340; alturaImagenPortada = 440;
            xImagenPortada = (800 - anchoImagenPortada) / 2;
            yInicioDescripcion = yInicioContenido + alturaImagenPortada + 40;
            
            ctxMed.font = '15px ' + FONT_SERIF;
            parrafosRaw.forEach((parrafo) => {
                if (parrafo.trim() === '') { lineasDescripcion.push(''); } 
                else { const lineas = ajustarTextoCanvas(ctxMed, parrafo, 700); lineas.forEach(l => lineasDescripcion.push(l)); }
            });
            
            const alturaSubtitulo = lineasSubtitulo.length * 26;
            const alturaDescripcion = lineasDescripcion.length * 25;
            yFinContenidoPrincipal = yInicioDescripcion + alturaSubtitulo + (subtituloText !== '' ? 12 : 0) + alturaDescripcion;
            
        } else {
            anchoImagenPortada = 340; alturaImagenPortada = 340;
            xImagenPortada = 50; 
            
            ctxMed.font = '15px ' + FONT_SERIF;
            parrafosRaw.forEach((parrafo) => {
                if (parrafo.trim() === '') { lineasDescripcion.push(''); } 
                else { const lineas = ajustarTextoCanvas(ctxMed, parrafo, 340); lineas.forEach(l => lineasDescripcion.push(l)); }
            });
            
            const alturaSubtitulo = lineasSubtitulo.length * 26;
            const alturaDescripcion = lineasDescripcion.length * 25;
            const alturaTotalTextoRight = alturaSubtitulo + (subtituloText !== '' ? 12 : 0) + alturaDescripcion;
            
            if (alturaTotalTextoRight < alturaImagenPortada) {
                const espacioExcedente = alturaImagenPortada - alturaTotalTextoRight;
                yInicioDescripcion = yInicioContenido + (espacioExcedente / 2);
            } else {
                yInicioDescripcion = yInicioContenido + 8;
            }
            
            yFinContenidoPrincipal = yInicioContenido + Math.max(alturaImagenPortada, alturaTotalTextoRight + 15);
        }

        const hayImagenesSecundarias = imagesSecundarias.length > 0; 
        const yCajaSecundarias = yFinContenidoPrincipal + 35; 
        const alturaCajaSecundarias = hayImagenesSecundarias ? 250 : 0; 
        const yFinCajaSecundarias = yCajaSecundarias + alturaCajaSecundarias;

        const yFichaTecnicaHeader = hayImagenesSecundarias ? (yFinCajaSecundarias + 35) : (yFinContenidoPrincipal + 35);
        const yPieDivisor = yFichaTecnicaHeader + alturaFichaTecnica + 35; 
        const yQR = yPieDivisor + 30; 
        const alturaBLoqueQR = rawVideoUrl !== '' ? 140 : 60;
        
        const alturaTotalBase = Math.max(yQR + alturaBLoqueQR + 70, 1350); 
        const scale = 3; 
        
        const canvas = document.createElement('canvas'); 
        const ctx = canvas.getContext('2d');
        canvas.width = 800 * scale; canvas.height = Math.round(alturaTotalBase) * scale; 
        ctx.imageSmoothingEnabled = true; ctx.imageSmoothingQuality = 'high'; ctx.scale(scale, scale);
        
        ctx.fillStyle = '#ffffff'; ctx.fillRect(0, 0, 800, alturaTotalBase); 
        
        // --- A. ENCABEZADO ---
        const gradient = ctx.createLinearGradient(0, 0, 800, 0); 
        gradient.addColorStop(0, '#0f172a'); gradient.addColorStop(1, '#1e3a8a'); 
        ctx.fillStyle = gradient; ctx.fillRect(0, 0, 800, 60); 
        ctx.fillStyle = '#ffffff'; ctx.font = 'bold 12px ' + FONT_FAMILY; ctx.textAlign = 'center'; 
        ctx.fillText('• DISTRIBUCIÓN EXCLUSIVA •', 400, 36); ctx.textAlign = 'left';
        
        // --- B. DIBUJAR BADGES ---
        let currentX = 50; ctx.font = 'bold 11px ' + FONT_FAMILY;
        categoriasBadges.forEach((texto) => {
            const textWidth = ctx.measureText(texto).width; const btnWidth = textWidth + 24; ctx.fillStyle = '#2563eb'; 
            ctx.beginPath(); ctx.roundRect(currentX, yBadges, btnWidth, 26, 6); ctx.fill(); 
            ctx.fillStyle = '#ffffff'; ctx.fillText(texto, currentX + 12, yBadges + 17); currentX += btnWidth + 10;
        });
        materialesBadges.forEach((texto) => {
            const textWidth = ctx.measureText(texto).width; const btnWidth = textWidth + 24; ctx.fillStyle = '#f1f5f9'; 
            ctx.beginPath(); ctx.roundRect(currentX, yBadges, btnWidth, 26, 6); ctx.fill(); 
            ctx.strokeStyle = '#cbd5e1'; ctx.lineWidth = 1; ctx.stroke(); 
            ctx.fillStyle = '#334155'; ctx.fillText(texto, currentX + 12, yBadges + 17); currentX += btnWidth + 10;
        });

        // --- C. DIBUJAR TÍTULO ---
        ctx.fillStyle = '#1e293b'; ctx.font = 'bold 24px ' + FONT_FAMILY;
        let yLineaTitulo = yInicioTitulo + 22;
        lineasTitulo.forEach((linea) => {
            ctx.fillText(linea, 50, yLineaTitulo); yLineaTitulo += 30;
        });
        
        ctx.strokeStyle = '#e2e8f0'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(50, yDivisor); ctx.lineTo(750, yDivisor); ctx.stroke();
        
        drawImageProp(ctx, imgLibro, xImagenPortada, yInicioContenido, anchoImagenPortada, alturaImagenPortada); 
        
        // --- D. RENDERIZADO DE TEXTO O PÁRRAFOS ---
        ctx.textAlign = 'left';
        let yCursorText = yInicioDescripcion;
        const xTexto = esImagenVertical ? 50 : 410;

        if (subtituloText !== '') {
            ctx.fillStyle = '#0f172a'; 
            ctx.font = 'bold 16px ' + FONT_SERIF;
            lineasSubtitulo.forEach((lineaSub) => {
                ctx.fillText(lineaSub, xTexto, yCursorText);
                yCursorText += 26;
            });
            yCursorText += 12; 
        }

        ctx.fillStyle = '#334155'; 
        ctx.font = '15px ' + FONT_SERIF;
        
        lineasDescripcion.forEach((linea) => {
            // 🌟 CORREGIDO: Cambiado 'yLinea' huérfano por 'yCursorText' para evitar congelamientos en párrafos largos
            if (linea === '') { yCursorText += 14; return; } 
            ctx.fillText(linea, xTexto, yCursorText); 
            yCursorText += 25;
        });
        
        // --- E. CAJA DE IMÁGENES SECUNDARIAS ---
        if (hayImagenesSecundarias) {
            ctx.fillStyle = '#f8fafc'; ctx.beginPath(); ctx.roundRect(50, yCajaSecundarias, 700, alturaCajaSecundarias, 12); ctx.fill();
            ctx.strokeStyle = '#e2e8f0'; ctx.lineWidth = 1; ctx.stroke();
            
            const cantidad = imagesSecundarias.length; const paddingCaja = 24; 
            const thumbW = Math.min(180, (700 - (paddingCaja * 2) - (20 * (cantidad - 1))) / cantidad); 
            const thumbH = alturaCajaSecundarias - 48; 
            
            const gap = 20;
            const anchoTotalBloqueThumbs = (thumbW * cantidad) + (gap * (cantidad - 1));
            let xThumb = 50 + (700 - anchoTotalBloqueThumbs) / 2; 
            const yThumb = yCajaSecundarias + 24;
            let cargadas = 0;
            
            imagesSecundarias.forEach((srcImg) => {
                const imgThumb = new Image(); imgThumb.crossOrigin = 'anonymous'; imgThumb.src = srcImg; 
                const posX = xThumb; xThumb += thumbW + gap;
                
                imgThumb.onload = function () {
                    ctx.fillStyle = '#ffffff'; ctx.beginPath(); ctx.roundRect(posX, yThumb, thumbW, thumbH, 8); ctx.fill();
                    ctx.strokeStyle = '#cbd5e1'; ctx.lineWidth = 1; ctx.stroke();
                    
                    const imgRatio = imgThumb.width / imgThumb.height; const targetRatio = (thumbW - 16) / (thumbH - 16); 
                    let dWidth, dHeight, dx, dy;
                    if (imgRatio > targetRatio) { dWidth = thumbW - 16; dHeight = (thumbW - 16) / imgRatio; dx = posX + 8; dy = yThumb + 8 + ((thumbH - 16) - dHeight) / 2; } 
                    else { dHeight = thumbH - 16; dWidth = (thumbH - 16) * imgRatio; dx = posX + 8 + ((thumbW - 16) - dWidth) / 2; dy = yThumb + 8; }
                    ctx.drawImage(imgThumb, dx, dy, dWidth, dHeight);
                    
                    cargadas++; 
                    if (cargadas === cantidad) { dibujarSeccionFinalFinal(); }
                };
                imgThumb.onerror = function () { 
                    cargadas++; 
                    if (cargadas === cantidad) { dibujarSeccionFinalFinal(); } 
                };
            });
        } else {
            dibujarSeccionFinalFinal();
        }

        function dibujarSeccionFinalFinal() {
            ctx.fillStyle = '#f8fafc'; ctx.beginPath(); ctx.roundRect(50, yFichaTecnicaHeader, 700, alturaFichaTecnica, 12); ctx.fill();
            ctx.strokeStyle = '#e2e8f0'; ctx.lineWidth = 1; ctx.stroke();
            
            ctx.fillStyle = '#1e3a8a'; ctx.font = 'bold 12px ' + FONT_FAMILY;
            ctx.fillText('FICHA TÉCNICA DE PRODUCTO', 70, yFichaTecnicaHeader + 26);
            
            const maxColumnasPorFila = requiereDosFilasSpecs ? 3 : specsFiltrados.length;
            const anchoColumnaSpec = 660 / maxColumnasPorFila;
            
            let colActiva = 0; let specX = 70; let specY = yFichaTecnicaHeader + 52;
            
            specsFiltrados.forEach((spec) => {
                ctx.fillStyle = '#64748b'; ctx.font = 'bold 9px ' + FONT_FAMILY; ctx.fillText(spec.clave.toUpperCase(), specX, specY);
                ctx.fillStyle = '#0f172a'; ctx.font = '500 12px ' + FONT_FAMILY;
                let valorLimpio = spec.valor; if (valorLimpio.length > 32) valorLimpio = valorLimpio.substring(0, 29) + '...';
                ctx.fillText(valorLimpio, specX, specY + 16);
                
                colActiva++;
                if (requiereDosFilasSpecs && colActiva >= 3) { specX = 70; specY += 45; colActiva = 0; } 
                else { specX += anchoColumnaSpec; }
            });

            ctx.strokeStyle = '#cbd5e1'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(50, yPieDivisor); ctx.lineTo(750, yPieDivisor); ctx.stroke();
            
            if (rawVideoUrl !== '') {
                const linkFinalQR = resolverLinkParaQR(rawVideoUrl); 
                const imgQR = new Image(); imgQR.crossOrigin = 'anonymous'; 
                imgQR.src = 'https://quickchart.io/qr?size=220&margin=1&text=' + encodeURIComponent(linkFinalQR);
                
                imgQR.onload = function () {
                    ctx.drawImage(imgQR, 50, yQR, 110, 110); 
                    ctx.fillStyle = '#1e3a8a'; ctx.font = 'bold 13px ' + FONT_FAMILY; ctx.fillText('¡CONTENIDO MULTIMEDIA INTERACTIVO!', 185, yQR + 30); 
                    ctx.fillStyle = '#475569'; ctx.font = '13px ' + FONT_FAMILY; 
                    const lineasQR = ajustarTextoCanvas(ctx, 'Escaneá el código QR con tu teléfono celular para ver el video de presentación completo, muestras dinámicas del material interior y estrategias de distribución comercial de la obra.', 540); 
                    let yQRTexto = yQR + 55; lineasQR.forEach((linea) => { ctx.fillText(linea, 185, yQRTexto); yQRTexto += 20; }); 
                    detonarDescarga(canvas, tituloText);
                };
                imgQR.onerror = function () { detonarDescarga(canvas, tituloText); };
            } else { 
                ctx.fillStyle = '#64748b'; ctx.font = 'italic 13px ' + FONT_FAMILY; 
                ctx.fillText('Material exclusivo para distribución mayorista y canales de preventa institucional de Editorial Nova Rhoman.', 50, yQR + 30); 
                detonarDescarga(canvas, tituloText); 
            }
        }
    };
    
    imgLibro.onerror = function () { 
        const canvasFallback = document.createElement('canvas');
        canvasFallback.width = 800 * 3; canvasFallback.height = 1350 * 3;
        detonarDescarga(canvasFallback, tituloText); 
    };
}
</script>

<style>
.folleto-tag-categoria {
    font-size: 0.72rem; 
    letter-spacing: 0.3px; 
    padding: 0.45rem 0.65rem; 
    background-color: #1b3d81; 
    border-radius: 6px;
    user-select: none;
}
.folleto-tag-material {
    font-size: 0.72rem; 
    letter-spacing: 0.3px; 
    padding: 0.45rem 0.65rem; 
    background-color: #e8effe; 
    border: 1px solid #ceddfa; 
    border-radius: 6px;
    user-select: none;
}
.btn-cerrar-lightbox-custom {
    position: fixed;
    top: 25px;
    right: 25px;
    background: transparent !important;
    border: none !important;
    color: rgba(255, 255, 255, 0.75) !important;
    font-size: 2.3rem;
    line-height: 1;
    padding: 0;
    cursor: pointer;
    transition: color 0.2s ease, transform 0.2s ease;
    z-index: 1070;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
}
.btn-cerrar-lightbox-custom:hover {
    color: #ffffff !important;
    transform: scale(1.1);
}
.btn-lightbox-nav {
    background-color: rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: all 0.2s ease-in-out;
    z-index: 1055;
}
.btn-lightbox-nav:hover {
    background-color: #1b3d81 !important; 
    border-color: #1b3d81 !important;
    transform: scale(1.1);
}
.galeria-principal-wrapper:hover .img-principal-animada { transform: scale(1.025); }
.galeria-principal-wrapper:hover .visual-zoom-icon { opacity: 1 !important; }
.miniatura-click-wrapper { border: 2px solid #e2e8f0 !important; opacity: 0.7; }
.miniatura-click-wrapper:hover { opacity: 1; border-color: #3182ce !important; }
.miniatura-activa { opacity: 1 !important; border-color: #1b3d81 !important; box-shadow: 0 0 0 3px rgba(27, 61, 129, 0.15); }
#vista-principal-libro { transition: opacity 0.15s ease-in-out, transform 0.3s ease; }
.no-select-box { -webkit-touch-callout: none !important; -webkit-user-select: none !important; user-select: none !important; pointer-events: none !important; }
.style-btn-volver:hover { background-color: #64748b !important; color: #fff !important; }
.text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.producto-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.producto-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important; }
</style>
@endsection