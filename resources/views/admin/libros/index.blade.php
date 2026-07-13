@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm text-dark mb-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h2 class="fw-bold text-primary text-uppercase mb-0" style="font-size: 1.5rem;">
                <i class="bi bi-gear-fill me-2"></i>Panel de Administración
            </h2>
            <p class="text-muted small mb-0">Gestioná el catálogo de libros, stock, imágenes y características JSON.</p>
        </div>
        <a href="{{ route('admin.libros.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-circle-fill me-2"></i>Cargar Nuevo Libro
        </a>
    </div>

    <ul class="nav nav-tabs border-bottom mb-4" id="panelAdminTabs" role="tablist" style="font-size: 0.95rem;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold text-primary" id="libros-tab" data-bs-toggle="tab" data-bs-target="#libros-content" type="button" role="tab">
                <i class="bi bi-book-half me-2"></i>Libros Cargados
            </button>
        </li>

        <li class="nav-item">
            <a class="nav-link text-secondary card-filter-item border-0" href="/admin/banners">
                <i class="bi bi-images me-2"></i>Banners del Carrusel
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-secondary card-filter-item border-0" href="/admin/categorias">
                <i class="bi bi-tags-fill me-2"></i>Gestionar Categorías
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.catalogos.*') ? 'active fw-bold text-primary bg-white' : 'text-secondary border-0' }}" href="{{ route('admin.catalogos.index') }}">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Catálogos Físicos
            </a>
        </li>
    </ul>

    @if(session('exito'))
        <div class="alert alert-success alert-dismissible fade show fw-bold mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('exito') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="tab-content" id="panelAdminTabsContent">
        
        <div class="tab-pane fade show active" id="libros-content" role="tabpanel">
            <form action="{{ route('admin.libros.index') }}" method="GET" class="mb-4 backend-search-form">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="buscar" class="form-control border-start-0" placeholder="Buscar por título o palabra clave..." value="{{ request('buscar') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <select name="categoria_id" class="form-select text-muted">
                            <option value="">-- Filtrar por Colección/Obra --</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-bold w-100"><i class="bi bi-filter me-1"></i>Filtrar</button>
                        @if(request()->filled('buscar') || request()->filled('categoria_id'))
                            <a href="{{ route('admin.libros.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-top">
                    <thead class="table-light text-muted" style="font-size: 0.85rem;">
                        <tr>
                            <th style="width: 80px;">Portada</th>
                            <th>Título</th>
                            <th>Categorías / Formatos</th>
                            <th>Especificaciones (JSON)</th>
                            <th class="text-end" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem;">
                        @forelse($libros as $libro)
                            <tr>
                                <td class="align-middle text-center">
                                    @if($libro->imagenes->isNotEmpty())
                                        <a href="{{ route('libro.detalle', $libro->id) }}" target="_blank" title="Ver detalle del producto en la web">
                                            <img src="{{ asset('storage/libros/' . $libro->imagenes->first()->ruta_imagen) }}" 
                                                class="rounded border img-link-hover" 
                                                style="width: 50px; height: 60px; object-fit: contain; cursor: pointer;">
                                        </a>
                                    @else
                                        <img src="https://placehold.co/50x60?text=Sin+Foto" class="rounded border">
                                    @endif
                                </td>
                                
                                <td class="align-middle">
                                    <span class="fw-bold text-dark">{{ $libro->titulo }}</span>
                                    <small class="d-block text-muted">ID: #{{ $libro->id }}</small>
                                </td>
                                
                                <td class="align-middle" style="max-width: 180px;">
                                    @foreach($libro->categories ?? $libro->categorias as $cat)
                                        <span class="badge bg-secondary mb-1" style="font-size: 0.72rem;">{{ $cat->nombre }}</span>
                                    @endforeach

                                    @if($libro->materiales && $libro->materiales->isNotEmpty())
                                        @foreach($libro->materiales as $mat)
                                            <span class="badge bg-primary mb-1 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.3px;">
                                                {{ $mat->nombre }}
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                
                                <td class="align-middle">
                                    <small class="text-muted d-block text-truncate" style="max-width: 350px;">
                                        @if(!empty($libro->caracteristicas))
                                            @foreach($libro->caracteristicas as $clave => $val)
                                                <strong>{{ $clave }}:</strong> {{ $val }} |
                                            @endforeach
                                        @else
                                            <span class="text-warning">Sin especificar</span>
                                        @endif
                                    </small>
                                </td>
                                
                                <td class="text-end align-middle">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.libros.edit', $libro->id) }}" class="btn btn-sm btn-outline-primary" title="Editar Libro">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('admin.libros.destroy', $libro->id) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar este libro de forma definitiva?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar Libro">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-open display-4"></i>
                                    <p class="mt-2 mb-0">No hay libros cargados en el sistema actualmente.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $libros->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="destacados-content" role="tabpanel">
            <div class="p-3 bg-white rounded border mt-2">
                <div class="border-bottom pb-2 mb-4">
                    <h4 class="fw-bold text-primary mb-0" style="font-size: 1.15rem;">
                        <i class="bi bi-sliders me-2"></i>ADMINISTRADOR DE POSICIONES EN PORTADA
                    </h4>
                    <p class="text-muted small mb-0">Asigná y ordená los libros que se lucirán en las secciones destacadas del inicio de la web.</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 border-end">
                        <div class="p-3 bg-light rounded-3 border">
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">
                                <i class="bi bi-images text-primary me-2"></i>1. Carrusel Principal (Arriba)
                            </h5>
                            
                            <div class="input-group input-group-sm mb-3">
                                <select id="select-add-carrusel" class="form-select"></select>
                                <button class="btn btn-primary fw-bold" onclick="agregarItemHome('carrusel')">
                                    <i class="bi bi-plus-lg"></i> Añadir
                                </button>
                            </div>

                            <ul class="list-group shadow-2xs" id="lista-carrusel-home"></ul>
                            <button class="btn btn-success btn-sm fw-bold w-100 mt-3 text-uppercase" onclick="guardarCambiosHome('carrusel')">
                                <i class="bi bi-check-circle-fill me-1"></i> Guardar Orden Carrusel
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <h5 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">
                                <i class="bi bi-grid-3x3-gap-fill text-primary me-2"></i>2. Grilla de Novedades y Ofertas (Abajo)
                            </h5>
                            
                            <div class="input-group input-group-sm mb-3">
                                <select id="select-add-grilla" class="form-select"></select>
                                <button class="btn btn-primary fw-bold" onclick="agregarItemHome('grilla')">
                                    <i class="bi bi-plus-lg"></i> Añadir
                                </button>
                            </div>

                            <ul class="list-group shadow-2xs" id="lista-grilla-home"></ul>
                            <button class="btn btn-success btn-sm fw-bold w-100 mt-3 text-uppercase" onclick="guardarCambiosHome('grilla')">
                                <i class="bi bi-check-circle-fill me-1"></i> Guardar Orden Grilla
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .nav-tabs .nav-link {
        transition: all 0.2s ease;
        padding: 0.75rem 1.25rem;
    }
    .nav-tabs .nav-link:not(.active):hover {
        background-color: #f8f9fa !important;
        color: #1b3d81 !important;
        border-radius: 0.5rem 0.5rem 0 0;
    }
</style>


@endsection