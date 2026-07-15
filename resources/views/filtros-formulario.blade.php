<form action="{{ route('catalogo') }}" method="GET" class="d-flex flex-column gap-3">
    
    <div>
        <label class="form-label fw-bold text-secondary small text-uppercase mb-1" style="font-size: 0.75rem;"><small>¿Qué estás buscando?</small></label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control bg-light border-start-0" placeholder="Título, colección, palabra clave...">
        </div>
    </div>

    <div>
        <label class="form-label fw-bold text-secondary small text-uppercase mb-1" style="font-size: 0.75rem;"><small>Por Tema / Colección</small></label>
        <select name="tema" class="form-select bg-light fw-semibold text-dark" style="font-size: 0.9rem;" onchange="this.form.submit()">
            <option value="">Todos los temas</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->slug }}" {{ request('tema') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->nombre }} ({{ $cat->libros_count ?? $cat->libros->count() }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="form-label fw-bold text-secondary small text-uppercase mb-1" style="font-size: 0.75rem;"><small>Por Material</small></label>
        <select name="material" class="form-select bg-light fw-semibold text-dark" style="font-size: 0.9rem;" onchange="this.form.submit()">
            <option value="">Todos los materiales</option>
            @foreach($materiales as $mat)
                <option value="{{ $mat->slug }}" {{ request('material') == $mat->slug ? 'selected' : '' }}>
                    {{ $mat->nombre }} ({{ $mat->libros_count ?? $mat->libros->count() }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="d-flex gap-2 mt-2">
        @if(request('buscar') || request('tema') || request('material'))
            <a href="{{ route('catalogo') }}" class="btn btn-light border flex-grow-1 py-2 fw-bold small text-uppercase text-danger" style="font-size: 0.75rem;">
                Limpiar
            </a>
        @endif
        <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-bold small text-uppercase" style="font-size: 0.75rem; background-color: #1b3d81; border-color: #1b3d81;">
            Buscar
        </button>
    </div>
</form>