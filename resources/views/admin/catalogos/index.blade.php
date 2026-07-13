@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm text-dark mb-4">
    
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h2 class="fw-bold text-primary text-uppercase mb-0" style="font-size: 1.5rem;">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Panel de Administración
            </h2>
            <p class="text-muted small mb-0">Gestioná los catálogos físicos en formato PDF para visualización y descarga.</p>
        </div>
        <a href="{{ route('admin.catalogos.create') }}" class="btn btn-sm btn-primary fw-bold px-3 py-1.5 rounded-2 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Subir Nuevo Catálogo
        </a>
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
            <a class="nav-link text-secondary card-filter-item border-0" href="{{ route('admin.categorias.index') }}">
                <i class="bi bi-tags-fill me-2"></i>Clasificaciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active fw-bold text-primary border-bottom-0 bg-white" href="{{ route('admin.catalogos.index') }}">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Catálogos Físicos
            </a>
        </li>
    </ul>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show fw-bold mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle border-top">
            <thead class="table-light text-muted small">
                <tr>
                    <th style="width: 80px;">Portada</th>
                    <th>Título / Detalles</th>
                    <th>Archivo PDF</th>
                    <th class="text-end" style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody style="font-size: 0.85rem;">
                @forelse($catalogos as $cat)
                    <tr>
                        <td>
                            <div class="p-1 bg-light border rounded text-center shadow-sm" style="max-width: 60px;">
                                <img src="{{ asset('storage/catalogos/' . $cat->ruta_portada) }}" class="img-fluid rounded" style="max-height: 65px; object-fit: contain;">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $cat->titulo }}</div>
                            <div class="text-muted small">{{ $cat->subtitulo ?? '(Sin descripción o subtítulo)' }}</div>
                        </td>
                        <td>
                            <a href="{{ asset('storage/catalogos/' . $cat->ruta_pdf) }}" target="_blank" class="badge bg-danger text-decoration-none px-2.5 py-1.5" style="font-size: 0.75rem;">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Ver PDF subido
                            </a>
                        </td>
                        <td class="text-end">
                            <form action="{{ route('admin.catalogos.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar este catálogo por completo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-pdf d-block mb-2 text-secondary fs-3 opacity-50"></i>
                            No hay ningún catálogo físico cargado en el panel.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection