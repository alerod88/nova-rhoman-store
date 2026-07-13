@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm text-dark mb-4">
    
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h2 class="fw-bold text-primary text-uppercase mb-0" style="font-size: 1.5rem;">
                <i class="bi bi-tags-fill me-2"></i>Panel de Administración
            </h2>
            <p class="text-muted small mb-0">Gestioná las clasificaciones de la plataforma: temas y materiales de los libros.</p>
        </div>
    </div>

    <ul class="nav nav-tabs border-bottom mb-4" style="font-size: 0.95rem;">
        <li class="nav-item">
            <a class="nav-link text-secondary card-filter-item border-0" href="{{ route('admin.libros.index') }}">
                <i class="bi bi-book-half me-2"></i>Libros Cargados
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-secondary card-filter-item border-0" href="{{ route('admin.banners.index') }}">
                <i class="bi bi-images me-2"></i>Banners del Carrusel
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active fw-bold text-primary border-bottom-0 bg-white" href="{{ route('admin.categorias.index') }}">
                <i class="bi bi-tags-fill me-2"></i>Clasificaciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.catalogos.*') ? 'active fw-bold text-primary bg-white' : 'text-secondary border-0' }}" href="{{ route('admin.catalogos.index') }}">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Catálogos Físicos
            </a>
        </li>
    </ul>

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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show fw-bold mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show fw-bold mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        
        <div class="col-md-6 border-end">
            <div class="p-2">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-secondary mb-0">
                        <i class="bi bi-bookmark-star me-2 text-warning"></i> Temas / Categorías
                    </h5>
                    <button type="button" class="btn btn-sm btn-outline-primary fw-bold px-2.5 py-1 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoTema" style="font-size: 0.8rem;">
                        <i class="bi bi-plus-lg me-1"></i> Agregar
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Nombre del Tema</th>
                                <th class="text-center" style="width: 140px;">Vinculados</th>
                                <th class="text-end" style="width: 80px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.85rem;">
                            @forelse($categorias as $categoria)
                                <tr>
                                    <td><small class="text-muted">#{{ $categoria->id }}</small></td>
                                    <td><span class="fw-bold text-dark">{{ $categoria->nombre }}</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary px-2.5 py-1.5" style="font-size: 0.75rem;">
                                            {{ $categoria->libros_count ?? 0 }} libros
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.categorias.destroy', $categoria->id) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar el tema {{ $categoria->nombre }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted small">Sin temas cargados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-2">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-secondary mb-0">
                        <i class="bi bi-layers me-2 text-info"></i> Materiales / Formatos
                    </h5>
                    <button type="button" class="btn btn-sm btn-primary fw-bold px-2.5 py-1 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoMaterial" style="font-size: 0.8rem;">
                        <i class="bi bi-plus-lg me-1"></i> Agregar
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Nombre del Material</th>
                                <th class="text-center" style="width: 140px;">Vinculados</th>
                                <th class="text-end" style="width: 80px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.85rem;">
                            @forelse($materiales as $material)
                                <tr>
                                    <td><small class="text-muted">#{{ $material->id }}</small></td>
                                    <td><span class="fw-bold text-dark">{{ $material->nombre }}</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary px-2.5 py-1.5" style="font-size: 0.75rem;">
                                            {{ $material->libros_count ?? 0 }} libros
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.materiales.destroy', $material->id) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar el material {{ $material->nombre }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted small">Sin materiales cargados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalNuevoTema" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-tag-fill text-primary me-2"></i>Nuevo Tema / Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categorias.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label for="nombre_tema" class="form-label small fw-semibold text-muted">Nombre</label>
                    <input type="text" name="nombre" id="nombre_tema" class="form-control bg-light border-0 rounded-3" placeholder="Ej: Infantiles, Educativos..." required autocomplete="off">
                </div>
                <div class="modal-footer border-top border-light p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light border-0 fw-semibold px-4 rounded-3 text-secondary small" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3 text-uppercase small shadow-sm">Guardar Tema</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoMaterial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-layers-fill text-primary me-2"></i>Nuevo Material / Formato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.materiales.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label for="nombre_material" class="form-label small fw-semibold text-muted">Nombre del Material</label>
                    <input type="text" name="nombre" id="nombre_material" class="form-control bg-light border-0 rounded-3" placeholder="Ej: Tapa Dura, Rústica, Digital..." required autocomplete="off">
                </div>
                <div class="modal-footer border-top border-light p-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light border-0 fw-semibold px-4 rounded-3 text-secondary small" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3 text-uppercase small shadow-sm">Guardar Material</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection