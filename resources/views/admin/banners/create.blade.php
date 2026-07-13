@extends('layouts.app')

@section('content')
<div class="container py-4 text-dark">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-primary text-uppercase mb-1" style="font-size: 1.3rem;">
                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Banner para el Carrusel
            </h4>
            <p class="text-muted small mb-0">Configurá un nuevo elemento visual para la página de inicio de la plataforma.</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold">
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

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100 border">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase text-secondary small mb-3" style="letter-spacing: 0.5px;">
                            <i class="bi bi-images me-1 text-primary"></i> Archivos del Banner
                        </h6>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-muted">Seleccionar Imagen Panorámica</label>
                            <input type="file" name="ruta_imagen" class="form-control form-control-sm @error('ruta_imagen') is-invalid @enderror" required accept="image/*">
                            <div class="form-text text-muted x-small mt-2">
                                <i class="bi bi-info-circle me-1"></i> Se recomiendan imágenes apaisadas (ej: 1920x600px).
                            </div>
                            @error('ruta_imagen') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-muted">Seleccionar Imagen para Celulares</label>
                            <input type="file" name="ruta_imagen_mobile" class="form-control form-control-sm @error('ruta_imagen_mobile') is-invalid @enderror" accept="image/*">
                            <div class="form-text text-muted x-small mt-2">
                                <i class="bi bi-phone me-1"></i> Diseños cuadrados o verticales recomendados (ej: 800x800px).
                            </div>
                            @error('ruta_imagen_mobile') <div class="invalid-feedback fw-bold">{{ $message }}</div> @enderror
                        </div>

                        <hr class="opacity-10 my-4">

                        <h6 class="fw-bold text-uppercase text-secondary small mb-3" style="letter-spacing: 0.5px;">
                            <i class="bi bi-sort-numeric-down me-1 text-primary"></i> Orden de Aparición
                        </h6>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold text-muted">Posición / Prioridad</label>
                            <div class="input-group input-group-sm" style="max-width: 170px;">
                                <span class="input-group-text bg-light"><i class="bi bi-sliders"></i></span>
                                <input type="number" name="orden" class="form-control text-center fw-bold" placeholder="Auto (Final)" min="0" value="{{ old('orden') }}">
                            </div>
                            <div class="form-text text-muted x-small mt-2">Dejalo vacío para que el sistema calcule la prioridad al final del carrusel de forma automática.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100 border">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-uppercase text-secondary small mb-3" style="letter-spacing: 0.5px;">
                            <i class="bi bi-type me-1 text-primary"></i> Textos Superpuestos (Opcionales)
                        </h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Título Principal</label>
                                <input type="text" name="titulo" class="form-control form-control-sm" placeholder="Ej: Los Tres Deseos" value="{{ old('titulo') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Subtítulo o Descripción</label>
                                <input type="text" name="subtitulo" class="form-control form-control-sm" placeholder="Ej: ¡Libro mágico de agua!" value="{{ old('subtitulo') }}">
                            </div>
                        </div>

                        <hr class="opacity-10 my-4">

                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-uppercase text-primary small mb-2 d-flex align-items-center" style="letter-spacing: 0.5px;">
                                <i class="bi bi-link-45deg me-1" style="font-size: 1.1rem;"></i> Acción del Botón (Enlace)
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted mb-1">Opción A: Enlace URL Personalizado (Colecciones, búsquedas, etc.)</label>
                                <input type="text" name="enlace_personalizado" class="form-control form-control-sm bg-white shadow-sm" 
                                    placeholder="Ej: /catalogo?buscar=Docentes en el aula" value="{{ old('enlace_personalizado') }}">
                                <div class="form-text text-muted" style="font-size: 0.75rem;">Si pegás un enlace acá, se ignorará el buscador de libros de abajo.</div>
                            </div>

                            <div class="text-center my-2 text-muted fw-bold small">- O BIEN -</div>

                            <div>
                                <label class="form-label small fw-semibold text-muted mb-1">Opción B: Vincular a un Libro Específico</label>
                                <div class="position-relative">
                                    <div class="input-group input-group-sm shadow-sm rounded-3">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                        <input type="text" id="buscador-libro-input" class="form-control border-start-0 bg-white" 
                                            value="-- Banner estático (No redirige a ningún lado) --" 
                                            placeholder="Escribí para buscar un libro..." autocomplete="off">
                                        <button class="btn btn-outline-secondary" type="button" id="btn-limpiar-seleccion"><i class="bi bi-x-lg"></i></button>
                                    </div>

                                    <div id="dropdown-sugerencias" class="position-absolute w-100 bg-white border rounded-3 shadow mt-1" style="max-height: 200px; overflow-y: auto; z-index: 1000; display: none;">
                                        <div class="list-group list-group-flush small">
                                            <a href="#" class="list-group-item list-group-item-action fw-bold text-primary opcion-libro" data-id="estatico" data-texto="-- Banner estático (No redirige a ningún lado) --">
                                                -- Dejar como Banner Estático --
                                            </a>
                                            @foreach($libros as $lib)
                                                <a href="#" class="list-group-item list-group-item-action opcion-libro" data-id="{{ $lib->id }}" data-texto="{{ $lib->titulo }} (ID: #{{ $lib->id }})">
                                                    {{ $lib->titulo }} <span class="text-muted text-xs float-end">#{{ $lib->id }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="libro_id" id="libro_id_hidden" value="estatico">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 rounded-3 text-uppercase shadow-sm" style="font-size: 0.85rem;">
                <i class="bi bi-check-circle-fill me-2"></i>Guardar y Publicar Banner
            </button>
        </div>
    </form>
</div>

<style>
    .x-small { font-size: 0.75rem; }
</style>

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