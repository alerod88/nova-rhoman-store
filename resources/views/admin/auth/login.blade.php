@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center text-dark" style="min-height: 60vh;">
    <div class="col-md-5">
        <div class="card border-0 shadow rounded p-4 bg-white">
            <div class="text-center mb-4">
                <h4 class="fw-bold text-primary text-uppercase mb-1" style="letter-spacing: 1px;">Ingreso Administrativo</h4>
                <p class="text-muted small">Panel de Gestión - Nova Rhoman</p>
            </div>

            <form action="{{ route('login.conectar') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted mb-1"><small>Correo Electrónico</small></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control border-start-0" required placeholder="" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <small class="text-danger d-block mt-1 fw-bold">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-muted mb-1"><small>Contraseña de Acceso</small></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" required placeholder="">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm mb-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>
                
                <a href="{{ route('home') }}" class="btn btn-link btn-sm w-100 text-muted text-decoration-none">Volver al Sitio Público</a>
            </form>
        </div>
    </div>
</div>
@endsection