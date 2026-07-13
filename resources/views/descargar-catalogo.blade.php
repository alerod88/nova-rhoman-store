@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary text-uppercase" style="letter-spacing: 1px;">Nuestros Catálogos Físicos</h2>
        <p class="text-muted fs-6">Seleccioná un catálogo para explorarlo de forma interactiva o descargarlo en PDF.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 justify-content-center">
        
        @forelse($catalogos as $cat)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden card-catalogo" style="transition: all 0.3s ease;">
                    
                    <div class="position-relative bg-light d-flex align-items-center justify-content-center cursor-pointer btn-abrir-visor"
                        style="height: 350px; overflow: hidden; background-color: #f8f9fa;"
                        data-titulo="{{ $cat->titulo }}"
                        data-visor="{{ asset('storage/catalogos/' . $cat->ruta_pdf) }}"
                        data-descarga="{{ asset('storage/catalogos/' . $cat->ruta_pdf) }}"
                        data-bs-toggle="modal" data-bs-target="#visorCatalogoModal">
                        
                        <img src="{{ asset('storage/catalogos/' . $cat->ruta_portada) }}" 
                            class="img-fluid" 
                            style="max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.3s ease; padding: 10px;" 
                            alt="{{ $cat->titulo }}">
                        
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-10 opacity-0 hover-overlay" style="transition: opacity 0.3s ease;"></div>
                    </div>

                    <div class="card-body p-3 d-flex flex-column justify-content-between bg-white border-top border-light">
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $cat->titulo }}</h6>
                            <small class="text-muted d-block text-truncate">{{ $cat->subtitulo ?? '' }}</small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary fw-semibold btn-abrir-visor py-1.5"
                                    data-titulo="{{ $cat->titulo }}"
                                    data-visor="https://docs.google.com/gview?url={{ asset('storage/catalogos/' . $cat->ruta_pdf) }}&embedded=true"
                                    data-descarga="{{ asset('storage/catalogos/' . $cat->ruta_pdf) }}"
                                    data-bs-toggle="modal" data-bs-target="#visorCatalogoModal">
                                <i class="bi bi-eye-fill me-1"></i> Hojeá en línea
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-file-earmark-pdf text-muted opacity-50" style="font-size: 3rem;"></i>
                <p class="text-muted fs-5 mt-2">Próximamente cargaremos nuestros catálogos interactivos.</p>
            </div>
        @endforelse

    </div>
</div>

<div class="modal fade" id="visorCatalogoModal" tabindex="-1" aria-labelledby="visorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-title fw-bold" id="visorModalLabel">Visualizando Catálogo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-secondary bg-opacity-10" style="height: 75vh; min-height: 500px;">
                <iframe id="iframeVisor" src="" width="100%" height="100%" style="border: none;"></iframe>
            </div>
            <div class="modal-footer bg-white d-flex justify-content-between py-3">
                <span class="text-muted small d-none d-md-inline">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> Podés ampliar o imprimir directo desde la pantalla.
                </span>
                <div>
                    <button type="button" class="btn btn-sm btn-light border me-2 fw-semibold" data-bs-dismiss="modal">Cerrar</button>
                    <a id="btnDescargaReal" href="" download class="btn btn-sm btn-danger fw-bold px-3">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const botonesVisor = document.querySelectorAll('.btn-abrir-visor');
    const iframeVisor = document.getElementById('iframeVisor');
    const modalLabel = document.getElementById('visorModalLabel');
    const btnDescargaReal = document.getElementById('btnDescargaReal');

    botonesVisor.forEach(boton => {
        boton.addEventListener('click', function () {
            const titulo = this.getAttribute('data-titulo');
            const urlVisor = this.getAttribute('data-visor');
            const urlDescarga = this.getAttribute('data-descarga');

            modalLabel.textContent = titulo;
            
            // 🛠️ CAMBIO EN LOCAL: En vez de 'urlVisor' (que usa Google), 
            // le pasamos directo 'urlDescarga' para que tu navegador abra el PDF local.
            iframeVisor.src = urlDescarga; 
            
            btnDescargaReal.href = urlDescarga;
        });
    });

    const miModal = document.getElementById('visorCatalogoModal');
    miModal.addEventListener('hidden.bs.modal', function () {
        iframeVisor.src = '';
    });
});
</script>

<style>
    .card-catalogo:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    .card-catalogo:hover .hover-overlay { opacity: 1 !important; }
    .card-catalogo:hover img { transform: scale(1.03); }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection