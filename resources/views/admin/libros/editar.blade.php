@extends('layouts.app')

@section('content')
<div class="row justify-content-center text-dark">
    <div class="col-md-9">
        <div class="bg-white p-4 rounded shadow-sm border mb-4">
            <div class="border-bottom pb-3 mb-4">
                <h2 class="fw-bold text-primary text-uppercase mb-0" style="font-size: 1.3rem;">
                    <i class="bi bi-pencil-square me-2"></i>Editar Libro: {{ $libro->titulo }}
                </h2>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger fw-bold">
                    <h6><i class="bi bi-exclamation-triangle-fill me-2"></i> Errores al guardar:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.libros.update', $libro->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-secondary mb-1"><small>Título del Libro</small></label>
                        <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $libro->titulo) }}" required placeholder="Ej: La Granja de Zenón - Aventuras">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-primary mb-1"><small><i class="bi bi-bookmark-star-fill me-1"></i>Etiqueta Destacada (Opcional)</small></label>
                        @php
                            // Verificamos si el libro ya tiene una etiqueta guardada en su JSON técnico
                            $etiquetaActual = !empty($libro->caracteristicas) && isset($libro->caracteristicas['Etiqueta']) ? $libro->caracteristicas['Etiqueta'] : '';
                        @endphp
                        <select name="json_valores_etiqueta" class="form-select fw-bold border-primary text-primary text-uppercase" style="font-size: 0.85rem;">
                            <option value="" {{ $etiquetaActual == '' ? 'selected' : '' }}>-- Sin Etiqueta / Normal --</option>
                            <option value="Novedad" {{ $etiquetaActual == 'Novedad' ? 'selected' : '' }}>🔴 Novedad</option>
                            <option value="Próximamente" {{ $etiquetaActual == 'Próximamente' ? 'selected' : '' }}>🟡 Próximamente</option>
                            <option value="Oferta" {{ $etiquetaActual == 'Oferta' ? 'selected' : '' }}>🟢 Oferta</option>
                            <option value="Agotado" {{ $etiquetaActual == 'Agotado' ? 'selected' : '' }}>⚫ Agotado</option>
                        </select>
                        <input type="hidden" name="json_claves_etiqueta" value="Etiqueta">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary mb-1"><small>Subtítulo / Mención Comercial (Opcional)</small></label>
                    <input type="text" name="subtitulo" class="form-control" value="{{ old('subtitulo', $libro->subtitulo) }}" placeholder="Ej: SET DE 8 CUBOS / CLÁSICOS INFANTILES / OBRAS DIDÁCTICAS">
                    <div class="form-text mt-1 text-muted">
                        <i class="bi bi-info-circle"></i> Este texto se usará como copete destacado en la ficha técnica e imágenes del folleto.
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary mb-1"><small>Descripción / Sinopsis</small></label>
                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Breve reseña...">{{ old('descripcion', $libro->descripcion) }}</textarea>
                </div>

                <div class="mb-4 p-3 bg-white rounded border shadow-sm">
                    <label class="form-label fw-bold text-primary mb-3 d-block border-bottom pb-1">
                        <i class="bi bi-tags-fill me-1"></i> Clasificación del Libro
                    </label>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="badge bg-primary text-uppercase mb-2" style="font-size: 0.75rem;">Colecciones y Obras</span>
                            <div class="d-flex flex-column gap-2 px-2 py-2 border rounded bg-light" style="max-height: 140px; overflow-y: auto;">
                                @foreach($obras as $cat)
                                    <div class="form-check class-contenedor-check">
                                        <input class="form-check-input check-categoria" type="checkbox" name="categorias[]" value="{{ $cat->id }}" id="cat-{{ $cat->id }}"
                                        {{ $libro->categorias->contains($cat->id) ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark small" for="cat-{{ $cat->id }}">{{ $cat->nombre }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6">
                            <span class="badge bg-secondary text-uppercase mb-2" style="font-size: 0.75rem;">Materiales y Atributos</span>
                            <div class="d-flex flex-column gap-2 px-2 py-2 border rounded bg-light" style="max-height: 140px; overflow-y: auto;">
                                @foreach($materiales as $mat)
                                    <div class="form-check class-contenedor-check">
                                        <input class="form-check-input check-material" type="checkbox" name="materiales[]" value="{{ $mat->id }}" id="mat-{{ $mat->id }}"
                                        {{ $libro->materiales->contains($mat->id) ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark small" for="mat-{{ $mat->id }}">{{ $mat->nombre }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 border-top pt-3">
                        <div class="input-group input-group-sm" style="max-width: 450px;">
                            <input type="text" id="nuevo-nombre-categoria" class="form-control" placeholder="Buscar o agregar otra opción...">
                            <select id="selector-tipo-categoria" class="form-select bg-light fw-bold" style="max-width: 120px;">
                                <option value="obra">Obra</option>
                                <option value="material">Material</option>
                            </select>
                            <button class="btn btn-primary fw-bold" type="button" id="btn-crear-categoria"><i class="bi bi-plus-lg"></i></button>
                        </div>
                        <div id="msg-error-categoria" class="small mt-1 d-none fw-bold"></div>
                    </div>
                </div>

                <div class="mb-4 p-3 border rounded bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-1">
                        <label class="form-label fw-bold text-primary mb-0"><i class="bi bi-gear-wide-connected me-1"></i> Especificaciones Técnicas</label>
                        <button type="button" id="btn-agregar-especificacion" class="btn btn-xs btn-outline-primary fw-bold"><i class="bi bi-plus-lg me-1"></i>Añadir Más</button>
                    </div>
                    
                    <div id="contenedor-especificaciones" class="d-flex flex-column gap-2">
                        @if(!empty($libro->caracteristicas) && is_array($libro->caracteristicas))
                            @foreach($libro->caracteristicas as $clave => $valor)
                                <div class="row g-2 fila-especificacion align-items-center">
                                    <div class="col-4 col-sm-3">
                                        <input type="text" name="json_claves[]" class="form-control form-control-sm bg-light fw-bold text-secondary" value="{{ $clave }}" readonly>
                                    </div>
                                    <div class="col-7 col-sm-8">
                                        <input type="text" name="json_valores[]" class="form-control form-control-sm" value="{{ $valor }}" placeholder="Especificar...">
                                    </div>
                                    <div class="col-1 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-fila w-100"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach(['Tomos', 'Formato', 'Páginas', 'Estuche'] as $campoFijo)
                                <div class="row g-2 fila-especificacion align-items-center">
                                    <div class="col-4 col-sm-3">
                                        <input type="text" name="json_claves[]" class="form-control form-control-sm bg-light fw-bold text-secondary" value="{{ $campoFijo }}" readonly>
                                    </div>
                                    <div class="col-7 col-sm-8">
                                        <input type="text" name="json_valores[]" class="form-control form-control-sm" placeholder="Especificar...">
                                    </div>
                                    <div class="col-1 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-fila w-100"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="mb-4 p-3 bg-white rounded border shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
                        <label class="form-label fw-bold text-primary mb-0"><i class="bi bi-images me-1"></i> Imágenes de Muestra</label>
                        <button type="button" id="btn-agregar-imagen" class="btn btn-xs btn-outline-primary fw-bold"><i class="bi bi-plus-lg me-1"></i>Nueva Casilla</button>
                    </div>
                    
                    <div class="row row-cols-2 row-cols-sm-4 g-3 mb-3">
                        @foreach($libro->imagenes as $img)
                            <div class="col position-relative foto-existente-contenedor">
                                <div class="card p-1 bg-light text-center">
                                    <img src="{{ asset('storage/libros/' . $img->ruta_imagen) }}" class="img-fluid rounded" style="max-height: 100px; object-fit: contain;">
                                    <button type="button" class="btn btn-xs btn-danger btn-borrar-foto-guardada position-absolute top-0 end-0 m-1 p-1 py-0" data-id="{{ $img->id }}">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="contenedor-fotos-borradas"></div>

                    <div id="contenedor-imagenes" class="d-flex flex-column gap-2">
                    </div>
                </div>

                <div class="row g-3 mt-3 border-top pt-3">
                    <h5 class="fw-bold text-secondary mb-2 text-uppercase"><i class="bi bi-play-btn-fill me-2"></i>Contenido Multimedia (Video)</h5>
                    <div class="form-text mb-2">
                        <i class="bi bi-info-circle-fill text-primary me-1"></i>
                        Cargá <strong>uno solo</strong> de los dos: si pegás un link, se ignora el archivo, y viceversa. No se pueden combinar los dos para el mismo libro.
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted mb-1"><small>Enlace del Video (Link URL)</small></label>
                        <input type="url" name="video_url" id="input-video-url" class="form-control" value="{{ old('video_url', $libro->video_url) }}" placeholder="Ej: https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted mb-1"><small>Subir Archivo de Video</small></label>
                        <input type="file" name="video_archivo" id="input-video-archivo" class="form-control" accept="video/mp4,video/x-m4v,video/*">
                        @if($libro->video_archivo)
                            <div class="form-text text-success fw-bold"><i class="bi bi-file-play-fill"></i> Video actual guardado: {{ $libro->video_archivo }}</div>
                        @endif
                    </div>
                    @if($libro->video_url || $libro->video_archivo)
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="quitar_video" value="1" id="check-quitar-video">
                                <label class="form-check-label text-danger fw-bold small" for="check-quitar-video">
                                    <i class="bi bi-trash me-1"></i>Quitar el video actual de este libro (link y/o archivo)
                                </label>
                            </div>
                            <div class="form-text">
                                Tildá esto si el libro tiene un video viejo/de prueba cargado y querés dejarlo sin video, o si vas a cargar uno nuevo y el anterior no se está reemplazando solo.
                            </div>
                        </div>
                    @endif
                </div>

                <div class="border-top pt-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.libros.index') }}" class="btn btn-light fw-bold">Cancelar</a>
                    <button type="submit" class="btn btn-primary fw-bold px-5">Actualizar Libro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputCat = document.getElementById('nuevo-nombre-categoria');
    const btnCrearCat = document.getElementById('btn-crear-categoria');
    const selectorTipo = document.getElementById('selector-tipo-categoria');
    const errorCat = document.getElementById('msg-error-categoria');

    // Manejo de eliminación visual de fotos viejas
    document.querySelectorAll('.btn-borrar-foto-guardada').forEach(btn => {
        btn.addEventListener('click', function() {
            const fotoId = this.getAttribute('data-id');
            const contenedor = this.closest('.foto-existente-contenedor');
            
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'fotos_borradas[]';
            hiddenInput.value = fotoId;
            document.getElementById('contenedor-fotos-borradas').appendChild(hiddenInput);
            
            contenedor.remove();
        });
    });

    // Guardar dinámico AJAX atajo rápido
    btnCrearCat.addEventListener('click', function () {
        const nombre = inputCat.value.trim();
        const tipo = selectorTipo.value;
        if (!nombre) return;

        fetch("{{ route('admin.categorias.rapido') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ nombre: nombre, tipo: tipo })
        })
        .then(res => res.json())
        .then(data => {
            if(data.exito) {
                location.reload(); 
            }
        });
    });

    // --- ESPECIFICACIONES ADICIONALES ---
    // CAMBIAR 'contenedor-specifications' POR 'contenedor-especificaciones'
    const contenedorSpecs = document.getElementById('contenedor-especificaciones'); 

    document.getElementById('btn-agregar-especificacion').addEventListener('click', function () {
        const div = document.createElement('div');
        div.className = 'row g-2 fila-especificacion mt-1';
        div.innerHTML = `
            <div class="col-4 col-sm-3"><input type="text" name="json_claves[]" class="form-control form-control-sm" placeholder="Clave"></div>
            <div class="col-7 col-sm-8"><input type="text" name="json_valores[]" class="form-control form-control-sm" placeholder="Valor..."></div>
            <div class="col-1 text-end"><button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-fila w-100"><i class="bi bi-trash"></i></button></div>
        `;
        contenedorSpecs.appendChild(div);
    });

    contenedorSpecs.addEventListener('click', function (e) {
        if (e.target.closest('.btn-eliminar-fila')) e.target.closest('.fila-especificacion').remove();
    });

    // --- CARGA MULTIPLE IMÁGENES ---
    const contenedorImgs = document.getElementById('contenedor-imagenes');
    document.getElementById('btn-agregar-imagen').addEventListener('click', function () {
        const div = document.createElement('div');
        div.className = 'card p-2 bg-light border fila-imagen mt-2';
        div.innerHTML = `
            <div class="d-flex gap-2 align-items-center">
                <input type="file" name="imagenes[]" class="form-control form-control-sm input-foto" accept="image/*" required>
                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-foto"><i class="bi bi-trash"></i></button>
            </div>
            <div class="text-center mt-2 d-none contenedor-preview"><img src="" class="img-fluid rounded img-preview" style="max-height: 90px; object-fit: contain;"></div>
        `;
        contenedorImgs.appendChild(div);
    });
    contenedorImgs.addEventListener('click', function (e) {
        if (e.target.closest('.btn-eliminar-foto')) e.target.closest('.fila-imagen').remove();
    });
    // --- VIDEO: EXCLUSIVIDAD ENTRE LINK Y ARCHIVO ---
    const inputVideoUrl = document.getElementById('input-video-url');
    const inputVideoArchivo = document.getElementById('input-video-archivo');

    inputVideoUrl.addEventListener('input', function () {
        if (this.value.trim() !== '') {
            inputVideoArchivo.value = '';
        }
    });
    inputVideoArchivo.addEventListener('change', function () {
        if (this.files.length > 0) {
            inputVideoUrl.value = '';
        }
    });
});
</script>
@endsection