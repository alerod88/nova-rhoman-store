@extends('layouts.app')

@section('meta_title', 'Contacto Institucional - Nova Rhoman')
@section('meta_description', 'Ponete en contacto con nuestra editorial por consultas de catálogo, compras institucionales o distribución.')

@section('content')
<div class="container my-5">
    <div class="row g-4 text-dark">
        
        <div class="col-md-7">
            <div class="bg-white p-4 rounded shadow-sm h-100">
                <h4 class="fw-bold text-primary mb-4 text-uppercase">Dejanos tu Consulta</h4>

                @if(session('exito'))
                    <div class="alert alert-success alert-dismissible fade show fw-bold mb-3" style="font-size: 0.9rem;" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('exito') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('contacto.enviar') }}" method="POST">
                    @csrf
                    <div style="display: none !important; visibility: hidden !important;">
                        <input type="text" name="seguridad_validacion_check" value="">
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-bold text-muted mb-1"><small>Nombre Completo</small></label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej: Nombre y Apellido">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-bold text-muted mb-1"><small>Correo Electrónico</small></label>
                            <input type="email" name="email" class="form-control" required placeholder="nombre@correo.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted mb-1"><small>Teléfono</small></label>
                            <input type="text" name="telefono" class="form-control" required placeholder="Ej: 11 1234-5678">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted mb-1"><small>Asunto</small></label>
                            <input type="text" name="asunto" class="form-control" required 
                                value="{{ request()->has('libro') ? 'Consulta por el libro: ' . request()->get('libro') : 'Consulta general por catálogo' }}" 
                                placeholder="Ej: Consulta por Catálogo Infantil">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted mb-1"><small>Mensaje / Consulta</small></label>
                            <textarea name="mensaje" class="form-control" rows="5" required placeholder="Escribí acá los detalles de tu consulta..."></textarea>
                        </div>
                        <!-- 🌟 Casillero de Validación Humana Real -->
                        <div class="form-check mb-3 text-start">
                            <input class="form-check-input" type="checkbox" name="confirmacion_humana" id="checkHumano" required>
                            <label class="form-check-label small text-muted" for="checkHumano">
                                Confirmar que soy una persona humana y deseo enviar esta consulta.
                            </label>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                <i class="bi bi-send-fill me-2"></i>Enviar Mensaje
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="bg-white p-4 rounded shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="fw-bold text-primary mb-4 text-uppercase">Contacto</h4>
                    <p class="text-muted mb-4">Completa el formulario y nos comunicaremos contigo a la brevedad.</p>
                    
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt-fill text-primary fs-5 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Dirección</h6>
                            <small class="text-muted">Av. Castañares 5874, CABA, Argentina</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-clock-fill text-primary fs-5 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Horario de Atención</h6>
                            <small class="text-muted">Lunes a Viernes de 9:00 a 17:00 Hs.</small>
                        </div>
                    </div>

                    <!-- 📞 Teléfono Fijo de Línea -->
                    <div class="d-flex align-items-start mb-3">
                        <div class="text-primary fs-4 me-3 mt-1">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.1rem; color: #212529;">Teléfono de Línea</h5>
                            <p class="text-muted mb-0" style="font-size: 0.95rem;">54 11 4601-0365</p> <!-- Reemplazá acá con tu número fijo real -->
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <i class="bi bi-envelope-check-fill text-primary fs-5 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-0">Correo Electrónico</h6>
                            <small class="text-muted">ventas@novarhoman.com</small>
                        </div>
                    </div>
                </div>

                <div class="my-3 rounded-3 overflow-hidden border shadow-2xs">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3280.941641040439!2d-58.4842516!3d-34.6814324!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95bcc8f2881cf3b9%3A0x6b107e3a968bd0a5!2sAv.%20Casta%C3%B1ares%205874%2C%20C1439%20CABA!5e0!3m2!1ses-419!2sar!4v1720295000000!5m2!1ses-419!2sar" 
                        width="100%" 
                        height="230" 
                        style="border:0; display:block;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

                <div class="border-top pt-3 mt-2">
                    <span class="text-muted"><small>Distribución oficial a todo el país.</small></span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection