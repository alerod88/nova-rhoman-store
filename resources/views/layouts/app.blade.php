<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="{{ asset('storage/logos/logonav.png') }}">
    <title>@yield('meta_title', 'Nova Rhoman - Libros para Siempre')</title>
    <meta name="description" content="@yield('meta_description', 'Explorá nuestro catálogo institucional de libros y material educativo. Distribución oficial a todo el país.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --azul-oscuro: #1b3d81;
            --azul-clarito: #2c5ebd;
        }
        .navbar, footer { background-color: var(--azul-oscuro); }
        .btn-primary { background-color: var(--azul-clarito); border: none; }
        .btn-primary:hover { background-color: var(--azul-oscuro); }

        /* ===================================================
           🎨 LOGOS ESTRUCTURALES Y ANIMACIÓN DE SCROLL (SHRINK)
           =================================================== */
        .navbar {
            padding-top: 14px;
            padding-bottom: 14px;
            min-height: 80px;
            transition: padding-top 0.25s ease, padding-bottom 0.25s ease, min-height 0.25s ease, background-color 0.25s ease;
        }

        .navbar-brand img { 
            height: 52px; 
            width: auto;
            object-fit: contain;
            transition: height 0.25s ease;
        }

        .navbar-nav {
            --bs-navbar-nav-link-padding-x: 0.85rem; /* Menos separación entre ítems */
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85) !important; /* Legible aunque no esté activo */
        }

        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus {
            color: #ffffff !important;
            font-weight: bold !important;
        }

        /* Estado activado por JavaScript al bajar la pantalla (más de 120px) */
        .navbar.scrolled {
            padding-top: 6px;
            padding-bottom: 6px;
            min-height: 60px;
            background-color: rgba(27, 61, 129, 0.96) !important; 
            backdrop-filter: blur(6px); 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
        }

        .navbar.scrolled .navbar-brand img { 
            height: 38px; 
        }

        /* Logo fijo del footer */
        .footer-brand-logo {
            height: 42px; 
            width: auto;
            object-fit: contain;
        }

        /* Componentes de Tarjetas y Desplegables */
        .producto-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
            border: 1px solid rgba(0,0,0,0.06) !important;
        }
        .producto-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(27, 61, 129, 0.12) !important;
            border-color: var(--azul-clarito) !important;
        }

        .custom-dropdown {
            background-color: #ffffff !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 0 !important;
        }

        .custom-dropdown .dropdown-item {
            color: #333333 !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            padding: 0.6rem 1.2rem !important;
            transition: all 0.2s ease-in-out !important;
        }

        .custom-dropdown .dropdown-item:hover {
            background-color: #f0f4f8 !important;
            color: var(--azul-clarito) !important;
            padding-left: 1.4rem !important;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('storage/logos/logo.png') }}" alt="Nova Rhoman Editorial">
            </a>
            <button class="navbar-dark navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center">
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                            Inicio
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('nosotros') ? 'active fw-bold' : '' }}" href="{{ route('nosotros') }}">
                            Nosotros
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('catalogo') || request()->routeIs('descargar-catalogo') ? 'active fw-bold text-primary-subtle' : '' }}" href="#" id="navbarDropdownCat" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Catálogo
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2" aria-labelledby="navbarDropdownCat" style="font-size: 0.95rem;">
                            
                            <li>
                                <a class="dropdown-menu-item dropdown-item py-2 fw-bold text-secondary" href="{{ route('catalogo') }}">
                                    <i class="bi bi-grid-fill me-2 text-primary"></i>Ver Todo
                                </a>
                            </li>
                            <li><hr class="dropdown-divider opacity-10"></li>

                            @foreach($categorias as $cat)
                                @if($cat->slug !== 'descargar-catalogo' && $cat->slug !== 'material')
                                    <li>
                                        <a class="dropdown-item py-2 {{ request('tema') === $cat->slug ? 'bg-primary text-white fw-bold rounded' : 'text-dark' }}" href="{{ route('catalogo', ['tema' => $cat->slug]) }}">
                                            {{ $cat->nombre }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach

                            <li><hr class="dropdown-divider opacity-10"></li>
                            <li>
                                <a class="dropdown-item py-2 text-primary fw-bold" href="{{ url('/descargar-catalogo') }}">
                                    <i class="bi bi-cloud-arrow-down-fill me-2"></i>Descargar Catálogo
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contacto') ? 'active fw-bold' : '' }}" href="{{ route('contacto') }}">
                            Contacto
                        </a>
                    </li>

                </ul>

                @auth
                    <div class="d-flex align-items-center ms-lg-3 my-2 my-lg-0">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold d-flex align-items-center gap-1">
                                <i class="bi bi-box-arrow-left"></i>
                                <span>Salir</span>
                            </button>
                        </form>
                    </div>
                @endauth

                <form action="{{ route('catalogo') }}" method="GET" class="d-flex ms-lg-3 mt-2 mt-lg-0">
                    <div class="input-group input-group-sm">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar libro, tema..." value="{{ request('buscar') }}">
                        <button class="btn btn-light text-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 container my-4">
        @yield('content')
    </main>

    <footer class="text-white pt-5 pb-3 mt-auto" style="border-top: 4px solid var(--azul-clarito);">
        <div class="container">
            <div class="row g-4 text-center text-md-start mb-4">
                <div class="col-md-4">
                    <div class="mb-3">
                        <img src="{{ asset('storage/logos/logo.png') }}" class="footer-brand-logo" alt="Nova Rhoman">
                    </div>
                    <p class="small text-white-50" style="max-width: 300px;">
                        Libros para Siempre. Diseñando materiales resistentes, interactivos y de alta calidad para perdurar en el tiempo.
                    </p>
                </div>
                
                <div class="col-md-4">
                    <h5 class="fw-bold text-uppercase tracking-wider mb-3" style="color: #cbd5e1; font-size: 1.1rem;">
                        <i class="bi bi-clock me-2"></i>Horarios
                    </h5>
                    <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                        <li><i class="bi bi-calendar-check me-2"></i>Lunes a Viernes</li>
                        <li><i class="bi bi-hourglass-split me-2"></i>9:00 Hs. a 17:00 Hs.</li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h5 class="fw-bold text-uppercase tracking-wider mb-3" style="color: #cbd5e1; font-size: 1.1rem;">
                        <i class="bi bi-geo-alt me-2"></i>Contacto
                    </h5>
                    <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                        <li>Av. Castañares 5874</li>
                        <li>Buenos Aires, Argentina</li>
                    </ul>
                </div>
            </div>

            <div class="border-top pt-3 border-secondary d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="small text-white-50 mb-0">
                    &copy; {{ date('Y') }} Nova Rhoman. Todos los derechos reservados.
                </p>
                
                <div>
                    @guest
                        <a href="{{ route('login') }}" class="text-white-50 text-decoration-none hover-admin-link" style="font-size: 0.75rem; opacity: 0.3; transition: opacity 0.2s;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </a>
                    @endguest
                    @auth
                        <a href="{{ route('admin.libros.index') }}" class="btn btn-xs btn-success text-white fw-bold tracking-wide border-0 px-2 py-1 shadow-sm opacity-75" style="font-size: 0.65rem; transition: all 0.2s; letter-spacing: 0.5px;">
                            <span class="badge bg-success small text-uppercase" style="font-size: 0.7rem;">Sesión Activa</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');

            // Umbrales distintos para activar y desactivar (histéresis).
            // Esto evita que el cambio de alto del navbar (sticky) altere
            // el scrollY y dispare un bucle de agregar/quitar la clase
            // (que era la causa del "temblor").
            const UMBRAL_ACTIVAR = 120;
            const UMBRAL_DESACTIVAR = 90;

            let ticking = false;

            function actualizarNavbar() {
                const y = window.scrollY;
                if (!navbar.classList.contains('scrolled') && y > UMBRAL_ACTIVAR) {
                    navbar.classList.add('scrolled');
                } else if (navbar.classList.contains('scrolled') && y < UMBRAL_DESACTIVAR) {
                    navbar.classList.remove('scrolled');
                }
                ticking = false;
            }

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(actualizarNavbar);
                    ticking = true;
                }
            });
        });
    </script>
</body>
</html>