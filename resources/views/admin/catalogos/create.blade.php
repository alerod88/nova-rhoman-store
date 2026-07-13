@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <div class="card border-0 shadow-sm p-4 bg-white">
        
        <div class="d-flex align-items-center border-bottom pb-3 mb-4">
            <h5 class="fw-bold m-0 text-secondary">
                <i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i>Subir Catálogo Físico
            </h5>
        </div>
        
        <form action="{{ route('admin.catalogos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Título del Catálogo</label>
                <input type="text" name="titulo" class="form-control form-control-sm" placeholder="Ej: Catálogo Infantil 2026" required>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Subtítulo u Observación (Opcional)</label>
                <input type="text" name="subtitulo" class="form-control form-control-sm" placeholder="Ej: Versión completa física">
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Imagen de la Portada (JPG, PNG, WEBP)</label>
                <input type="file" name="ruta_portada" class="form-control form-control-sm" accept="image/*" required>
                <div class="form-text text-muted" style="font-size: 0.75rem;">Esta imagen se mostrará como tapa interactiva en la web.</div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">Documento PDF del Catálogo</label>
                <input type="file" name="ruta_pdf" class="form-control form-control-sm" accept=".pdf" required>
                <div class="form-text text-muted" style="font-size: 0.75rem;">Soporta archivos de gran tamaño. Se optimizará su visualización por streaming.</div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('admin.catalogos.index') }}" class="btn btn-sm btn-light border fw-semibold px-3">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-sm btn-primary fw-bold px-4 shadow-2xs">
                    Guardar Catálogo
                </button>
            </div>
        </form>

    </div>
</div>
@endsection