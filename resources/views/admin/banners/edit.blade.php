@extends('layouts.app')

@section('content')
<div class="row justify-content-center text-dark">
    <div class="col-md-10">
        <div class="bg-white p-4 rounded shadow-sm border mb-4" style="border-radius: 1rem !important;">
            
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h2 class="fw-bold text-primary text-uppercase mb-0" style="font-size: 1.3rem;">
                        <i class="bi bi-pencil-square me-2"></i>Editar Banner #{{ $banner->id }}
                    </h2>
                    <p class="text-muted small mb-0">Modificá los textos, el orden o reemplazá las imágenes del carrusel principal.</p>
                </div>
                <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-outline-secondary fw-bold rounded-2 px-3">
                    <i class="bi bi-arrow-left me-1"></i> Volver al listado
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger fw-bold mb-4">
                    <h6><i class="bi bi-exclamation-triangle-fill me-2"></i> Errores al guardar:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    
                    <div class="col-md-5 border-end">
                        <div class="p-2">
                            <h6 class="fw-bold text-uppercase text-secondary small mb-3" style="letter-spacing: 0.5px;">
                                <i class="bi bg-light bi-images text-primary me-1"></i> Archivos del Banner
                            </h6>
                            
                            <div class="card p-2 bg-light text-center border mb-2 rounded-3">
                                <small class="text-muted d-block mb-1 fw-semibold" style="font-size: 0.75rem;">Miniatura Escritorio Actual:</small>
                                <img src="{{ asset('storage/banners/' . $banner->ruta_imagen) }}" class="img-fluid rounded shadow-2xs border mx-auto" style="max-height: 90px; object-fit: contain;">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted small mb-1">Reemplazar Imagen Panorámica</label>
                                <input type="file" name="ruta_imagen" class="form-control form-control-sm @error('ruta_imagen') is-invalid @enderror" accept="image/*">
                                <div class="form-text small text-muted" style="font-size: 0.75rem;">Dejá vacío si querés mantener la misma panorámica horizontal.</div>
                                @error('ruta_imagen') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <hr class="opacity-10 my-3">

                            <div class="card p-2 bg-light text-center border mb-2 rounded-3">
                                <small class="text-muted d-block mb-1 fw-semibold" style="font-size: 0.75rem;">Miniatura Celular Actual:</small>
                                @if(!empty($banner->ruta_imagen_mobile) && file_exists(public_path('storage/banners/' . $banner->ruta_imagen_mobile)))
                                    <img src="{{ asset('storage/banners/' . $banner->ruta_imagen_mobile) }}" class="img-fluid rounded shadow-2xs border mx-auto" style="max-height: 90px; object-fit: contain;">
                                @else
                                    <div class="py-2 text-muted bg-white rounded border small"><i class="bi bi-phone-vibrate me-1"></i> Sin imagen mobile cargada</div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted small mb-1">Reemplazar Imagen Celulares</label>
                                <input type="file" name="ruta_imagen_mobile" class="form-control form-control-sm @error('ruta_imagen_mobile') is-invalid @enderror" accept="image/*">
                                <div class="form-text small text-muted" style="font-size: 0.75rem;">Formatos cuadrados/verticales recomendados (ej: 800x800px).</div>
                                @error('ruta_imagen_mobile') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                            </div>

                            <hr class="opacity-10 my-3">

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small mb-1"><i class="bi bi-sort-numeric-down me-1"></i> Orden de Aparición</label>
                                <div class="input-group input-group-sm" style="max-width: 140px;">
                                    <span class="input-group-text bg-light"><i class="bi bi-sliders"></i></span>
                                    <input type="number" name="orden" class="form-control text-center fw-bold" value="{{ old('orden', $banner->orden) }}" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="p-2">
                            <h6 class="fw-bold text-uppercase text-secondary small mb-3" style="letter-spacing: 0.5px;">
                                <i class="bi bi-type me-1 text-primary"></i> Textos Superpuestos (Opcionales)
                            </h6>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold text-muted small mb-1">Título Principal</label>
                                    <input type="text" name="titulo" class="form-control form-control-sm" value="{{ old('titulo', $banner->titulo) }}" placeholder="Ej: ¡Novedades Imperdibles!">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label fw-bold text-muted small mb-1">Subtítulo</label>
                                    <input type="text" name="subtitulo" class="form-control form-control-sm" value="{{ old('subtitulo', $banner->subtitulo) }}" placeholder="Ej: Conseguilo en cuotas sin interés">
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-3 border">
                                <label class="form-label fw-bold text-primary small mb-2 d-flex align-items-center">
                                    <i class="bi bi-link-45deg me-1" style="font-size: 1.1rem;"></i> Acción del Botón (Enlace)
                                </label>
                                <p class="text-muted mb-2" style="font-size: 0.8rem;">Configurá a dónde querés que lleve este banner:</p>
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-muted mb-1">Opción A: Enlace URL Personalizado (Colecciones, búsquedas, etc.)</label>
                                    <input type="text" name="enlace_personalizado" class="form-control form-control-sm bg-white shadow-sm" 
                                           placeholder="Ej: /catalogo?buscar=Docentes en el aula" value="{{ old('enlace_personalizado', $banner->enlace_personalizado) }}">
                                    <div class="form-text text-muted" style="font-size: 0.75rem;">Si hay un texto acá, el sistema ignorará el buscador de libros de abajo.</div>
                                </div>

                                <div class="text-center my-2 text-muted fw-bold small">- O BIEN -</div>

                                <div>
                                    <label class="form-label small fw-semibold text-muted mb-1">Opción B: Vincular a un Libro Específico</label>
                                    <div class="position-relative">
                                        <div class="input-group input-group-sm shadow-sm rounded-3">
                                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bg-light bi-search"></i></span>
                                            <input type="text" id="buscador-libro-input" class="form-control border-start-0 bg-white" 
                                                value="{{ $banner->libro ? $banner->libro->titulo . ' (ID: #' . $banner->libro->id . ')' : '-- Banner estático (No redirige a ningún lado) --' }}" 
                                                placeholder="Escribí para buscar un libro..." autocomplete="off">
                                            <button class="btn btn-outline-secondary" type="button" id="btn-limpiar-seleccion"><i class="bi bi-x-lg"></i></button>
                                        </div>

                                        <div id="dropdown-sugerencias" class="position-absolute w-100 bg-white border rounded-3 shadow mt-1" style="max-height: 200px; overflow-y: auto; z-index: 1000; display: none;">
                                            <div class="list-group list-group-flush small">
                                                <a href="#" class="list-group-item list-group-item-action fw-bold text-primary opcion-libro" data-id="estatico" data-texto="-- Banner estático (No redirige a ningún lado) --">
                                                    -- Dejar como Banner Estático --
                                                </a>
                                                @foreach($libros as $lib)
                                                    <a href="#" class="list-group-item list-group-item-action opcion-libro" 
                                                    data-id="{{ $lib->id }}" 
                                                    data-texto="{{ $lib->titulo }} (ID: #{{ $lib->id }})">
                                                        {{ $lib->titulo }} <span class="text-muted text-xs float-end">#{{ $lib->id }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="libro_id" id="libro_id_hidden" value="{{ $banner->libro_id ?? 'estatico' }}">
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="border-top pt-3 mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-light fw-semibold text-secondary px-4">Cancelar</a>
                    <button type="submit" class="btn btn-warning fw-bold px-4 text-uppercase shadow-sm">
                        <i class="bi bi-arrow-clockwise me-1"></i> Actualizar Banner
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputBuscador = document.getElementById('buscador-libro-input');
    const dropdown = document.getElementById('dropdown-sugerencias');
    const hiddenInput = document.getElementById('libro_id_hidden');
    const btnLimpiar = document.getElementById('btn-limpiar-seleccion');
    const opciones = document.querySelectorAll('.opcion-libro');

    inputBuscador.addEventListener('focus', function() {
        dropdown.style.display = 'block';
    });

    inputBuscador.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        dropdown.style.display = 'block';

        opciones.forEach(opcion => {
            const texto = opcion.getAttribute('data-texto').toLowerCase();
            if (texto.includes(query) || opcion.getAttribute('data-id') === 'estatico') {
                opcion.style.setProperty('display', 'block', 'important');
            } else {
                opcion.style.setProperty('display', 'none', 'important');
            }
        });
    });

    dropdown.addEventListener('click', function(e) {
        const item = e.target.closest('.opcion-libro');
        if (!item) return;
        
        e.preventDefault();

        const id = item.getAttribute('data-id');
        const texto = item.getAttribute('data-texto');

        inputBuscador.value = texto;
        hiddenInput.value = id;
        dropdown.style.display = 'none';
    });

    document.addEventListener('click', function(e) {
        if (!inputBuscador.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    btnLimpiar.addEventListener('click', function() {
        inputBuscador.value = '-- Banner estático (No redirige a ningún lado) --';
        hiddenInput.value = 'estatico';
        dropdown.style.display = 'none';
    });
});
</script>
@endsection