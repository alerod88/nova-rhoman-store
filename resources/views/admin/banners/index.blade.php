@extends('layouts.app')

@section('content')
<div class="bg-white p-4 rounded shadow-sm text-dark mb-4">
    
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <h2 class="fw-bold text-primary text-uppercase mb-0" style="font-size: 1.5rem;">
                <i class="bi bi-images me-2"></i>Panel de Administración
            </h2>
            <p class="text-muted small mb-0">Gestioná los elementos visuales e informativos del carrusel de la página de inicio.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-sm btn-primary fw-bold px-3 py-1.5 rounded-2 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Banner
        </a>
    </div>

    <ul class="nav nav-tabs border-bottom mb-4" style="font-size: 0.95rem;">
        <li class="nav-item">
            <a class="nav-link text-secondary card-filter-item border-0" href="{{ route('admin.libros.index') }}">
                <i class="bi bi-book-half me-2"></i>Libros Cargados
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active fw-bold text-primary border-bottom-0 bg-white" href="{{ route('admin.banners.index') }}">
                <i class="bi bi-images me-2"></i>Banners del Carrusel
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-secondary card-filter-item border-0" href="{{ route('admin.categorias.index') }}">
                <i class="bi bi-tags-fill me-2"></i>Clasificaciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.catalogos.*') ? 'active fw-bold text-primary bg-white' : 'text-secondary border-0' }}" href="{{ route('admin.catalogos.index') }}">
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

    <div class="mb-3 text-end">
        <button type="button" class="btn btn-success fw-bold btn-sm text-uppercase px-4 shadow-2xs" onclick="guardarOrdenBannersReal()">
            <i class="bi bi-check-circle-fill me-1"></i> Guardar Nuevo Orden
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border-top">
            <thead class="table-light text-muted small">
                <tr>
                    <th style="width: 60px;">Orden</th>
                    <th style="width: 180px;">Miniatura</th>
                    <th>Textos / Detalles</th>
                    <th>Destino del Clic (Enlace)</th>
                    <th class="text-end" style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody id="contenedor-banners-real" style="font-size: 0.85rem;">
                @forelse($banners as $banner)
                    <tr data-id="{{ $banner->id }}" style="cursor: move;" class="fila-banner-interactiva">
                        <td class="text-center">
                            <span class="badge bg-light text-dark border fw-bold p-2 indicador-orden-visual" style="font-size: 0.85rem;">
                                #{{ $banner->orden }}
                            </span>
                        </td>
                        
                        <td>
                            <div class="p-1 bg-light border rounded text-center shadow-sm" style="max-width: 160px;">
                                <img src="{{ asset('storage/banners/' . $banner->ruta_imagen) }}" class="img-fluid rounded" style="max-height: 55px; object-fit: contain;">
                            </div>
                        </td>

                        <td>
                            <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $banner->titulo ?? '(Sin título)' }}</div>
                            <div class="text-muted small">{{ $banner->subtitulo ?? '(Sin descripción superpuesta)' }}</div>
                        </td>

                        <td>
                            @if($banner->libro)
                                <span class="badge bg-primary px-2.5 py-1.5" style="font-size: 0.75rem;">
                                    <i class="bi bi-link-45deg me-1"></i> Redirige a: {{ $banner->libro->titulo }}
                                </span>
                            @else
                                <span class="badge bg-secondary px-2.5 py-1.5 text-light opacity-75" style="font-size: 0.75rem;">
                                    <i class="bi bi-eye-slash me-1"></i> Banner estático
                                </span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-outline-primary border-0">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('¿Seguro querés eliminar este banner por completo del carrusel?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-images d-block mb-2 text-secondary fs-3 opacity-50"></i>
                            No hay ningún banner cargado para el carrusel principal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contenedorBanners = document.getElementById('contenedor-banners-real');
    if (!contenedorBanners) return;

    // Inicializamos Sortable sobre las filas de tu tabla real
    new Sortable(contenedorBanners, {
        animation: 150,
        handle: '.fila-banner-interactiva', // Permite arrastrar haciendo clic en cualquier parte de la fila
        ghostClass: 'bg-primary-subtle',    // Le da un sombreado azul sutil al mover las filas
        onEnd: function() {
            console.log("Nuevo orden posicionado visualmente en el DOM.");
        }
    });

    // Función síncrona para empaquetar y despachar el nuevo orden secuencial al servidor
    window.guardarOrdenBannersReal = function() {
        const filas = contenedorBanners.querySelectorAll('tr[data-id]');
        const listaIds = Array.from(filas).map(f => f.getAttribute('data-id'));

        if(listaIds.length === 0) return;

        // Capturamos el token de seguridad CSRF nativo de Laravel
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

        fetch("{{ route('admin.banners.guardar-orden') }}", { // Usa el alias real de Laravel
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({ items: listaIds })
        })
        .then(res => {
            if (!res.ok) throw new Error("Error interno del servidor en la transacción.");
            return res.json();
        })
        .then(data => {
            if(data.status === 'success') {
                alert("¡El carrusel de banners se reordenó y guardó con éxito!");
                location.reload(); // Recarga limpia para actualizar los números fijos de #orden
            } else {
                alert("No se pudieron guardar los cambios: " + data.message);
            }
        })
        .catch(err => {
            console.error("Falla en el envío fetch de banners:", err);
            alert("Ocurrió un error al intentar conectar con el servidor.");
        });
    }
});
</script>
@endsection