@extends('layouts.app')

@section('content')
<div class="container my-5">
    
    <!-- 🏛️ CARD PRINCIPAL INSTITUCIONAL -->
    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white rounded-3 mb-5">
        
        <!-- 1️⃣ ENCABEZADO: Bienvenidos (100% del ancho de la hoja) -->
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="fw-bold text-primary mb-3" style="font-size: 2.5rem; letter-spacing: -0.5px;">
                    Bienvenidos a Editorial Nova Rhoman
                </h1>
                <p class="fs-5 text-dark fw-semibold mb-0 border-start border-4 border-primary ps-3" style="line-height: 1.5; color: #475569 !important;">
                    Tu aliado estratégico en la venta de libros a crédito. Contamos con un catálogo compuesto por obras de sello propio y distribuciones exclusivas de primer nivel.
                </p>
            </div>
        </div>
        
        <!-- 2️⃣ CONTENIDO MEDIO: Historia (Izquierda) y Logos (Derecha) -->
        <div class="row g-4 align-items-start mb-5">
            
            <!-- Columna Izquierda (Ancho 7): Historia de la Editorial -->
            <div class="col-lg-7">
                <h3 class="fw-bold text-secondary mb-3" style="font-size: 1.3rem;">
                    <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Nuestra Historia: Tradición e Innovación
                </h3>
                <p class="text-muted justify-text mb-3" style="text-align: justify; line-height: 1.7;">
                    El origen de nuestra casa editorial se remonta a más de 40 años atrás, cuando Rubén Horacio Mancuso y su familia fundaron <strong>"RHOMAN"</strong>, logrando imponer importantes obras de interés general en el mercado.
                </p>
                <p class="text-muted justify-text mb-4" style="text-align: justify; line-height: 1.7;">
                    Actualmente, el legado se renueva bajo la dirección de Héctor J. Girardi, quien impulsó el relanzamiento de la empresa como <strong>NOVA RHOMAN</strong>. Esta evolución aporta una impronta moderna, nuevos proyectos pedagógicos y comerciales, y alianzas con editoriales de gran prestigio.
                </p>

                <h3 class="fw-bold text-secondary mt-4 mb-3" style="font-size: 1.3rem;">
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Líderes en el Mercado Editorial
                </h3>
                <p class="text-muted justify-text mb-0" style="text-align: justify; line-height: 1.7;">
                    Hoy en día, el esfuerzo y la dedicación nos posicionan como una de las distribuidoras y editoriales líderes del país. Trabajamos activamente para satisfacer las necesidades de nuestros clientes mediante:
                </p>
            </div>

            <!-- Columna Derecha (Ancho 5): Evolución de Identidad (Logos) -->
            <div class="col-lg-5 ps-lg-5 ">
                <div class="bg-light p-4 rounded-3 border border-light shadow-2xs text-center">
                    <h5 class="fw-bold text-uppercase text-secondary mb-4 small tracking-wider" style="letter-spacing: 1px;">
                        Evolución de Nuestra Identidad
                    </h5>
                    
                    <div class="mb-4 p-3 bg-white rounded border shadow-3xs">
                        <div class="d-flex align-items-center justify-content-center" style="height: 100px;">
                            <img src="{{ asset('storage/logos/logo-1.png') }}" class="img-fluid" style="max-height: 100%; object-fit: contain;" alt="Logo Nuevo Nova Rhoman">
                        </div>
                    </div>

                    <div class="p-3 bg-white rounded border shadow-3xs">
                        <div class="d-flex align-items-center justify-content-center" style="height: 100px;">
                            <img src="{{ asset('storage/logos/LOGO-RHOMAN2.jpg') }}" class="img-fluid" style="max-height: 100%; object-fit: contain;" alt="Logo Histórico Rhoman">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3️⃣ CONTENIDO INFERIOR: Características repartidas a lo ancho de la hoja (100% ancho) -->
        <div class="border-top pt-4">
            <div class="row g-4">
                <!-- Item 1: Stock Permanente -->
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-circle me-3 mt-1">
                            <i class="bi bi-box-seam-fill fs-6"></i>
                        </span>
                        <div>
                            <strong class="text-dark d-block mb-1">Stock Permanente</strong>
                            <span class="text-muted small lh-base d-block">Garantizamos disponibilidad inmediata en una gran variedad de títulos.</span>
                        </div>
                    </div>
                </div>
                
                <!-- Item 2: Catálogo Diversificado -->
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-circle me-3 mt-1">
                            <i class="bi bi-tags-fill fs-6"></i>
                        </span>
                        <div>
                            <strong class="text-dark d-block mb-1">Catálogo Diversificado</strong>
                            <span class="text-muted small lh-base d-block">Incorporamos novedades constantes en áreas clave como Hogar, Infantil, Estudiantil y Religión.</span>
                        </div>
                    </div>
                </div>
                
                <!-- Item 3: Logística de Excelencia -->
                <div class="col-md-4">
                    <div class="d-flex align-items-start">
                        <span class="badge bg-primary-subtle text-primary p-2 rounded-circle me-3 mt-1">
                            <i class="bi bi-truck fs-6"></i>
                        </span>
                        <div>
                            <strong class="text-dark d-block mb-1">Logística de Excelencia</strong>
                            <span class="text-muted small lh-base d-block">Contamos con un servicio de despacho diario que asegura entregas en tiempo y forma.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 🌟 SECCIÓN DE COMPROMISOS -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary text-uppercase" style="font-size: 1.3rem; letter-spacing: 1px;">Nuestros Compromisos</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <div class="col">
            <div class="card h-100 border-0 shadow-2xs p-4 bg-white rounded-3 card-compromiso">
                <div class="text-primary fs-2 mb-2"><i class="bi bi-book-half"></i></div>
                <h5 class="fw-bold text-dark mb-2">Libros para Siempre</h5>
                <p class="text-muted small mb-0" style="line-height: 1.6;">Hacemos honor a nuestro lema. Diseñamos materiales resistentes, interactivos y con excelente acabado para perdurar en el tiempo.</p>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow-2xs p-4 bg-white rounded-3 card-compromiso">
                <div class="text-primary fs-2 mb-2"><i class="bi bi-mortarboard-fill"></i></div>
                <h5 class="fw-bold text-dark mb-2">Atención Personalizada</h5>
                <p class="text-muted small mb-0" style="line-height: 1.6;">Contamos con personal capacitado y con vasta experiencia en el rubro editorial para asesorar de forma cordial en cada operation.</p>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 border-0 shadow-2xs p-4 bg-white rounded-3 card-compromiso">
                <div class="text-primary fs-2 mb-2"><i class="bi bi-truck"></i></div>
                <h5 class="fw-bold text-dark mb-2">Despacho Diario</h5>
                <p class="text-muted small mb-0" style="line-height: 1.6;">Garantizamos un servicio logístico ágil y continuo para que la mercadería llegue a destino en tiempo y forma a todo el país.</p>
            </div>
        </div>
    </div>

    <!-- 🛡️ AVISO INSTITUCIONAL Y CONDICIONES DE DISTRIBUCIÓN -->
    <div class="row my-5">
        <div class="col-12">
            <div class="p-4 rounded-4 border bg-light text-muted shadow-2xs" style="font-size: 0.9rem; line-height: 1.6;">
                <h5 class="fw-bold text-primary text-uppercase mb-3" style="font-size: 1rem; letter-spacing: 0.5px;">
                    <i class="bi bi-shield-check-fill me-2"></i>Condiciones de Distribución e Identidad Comercial
                </h5>
                
                <div class="row g-4 align-items-start">
                    <!-- Columna Izquierda: Canal Mayorista -->
                    <div class="col-md-5">
                        <p class="mb-0 text-justify">
                            <strong>Canal Exclusivo Mayorista:</strong> Editorial Nova Rhoman opera de forma estricta bajo la modalidad de venta y distribución mayorista dirigida a empresas y vendedores independientes dedicados a la comercialización de libros y materiales educativos a crédito. No realizamos venta directa minorista al público general, siendo cada distribuidor independiente responsable exclusivo de sus condiciones comerciales.
                        </p>
                    </div>
                    
                    <!-- Divisor Vertical Visual -->
                    <div class="col-md-1 d-none d-md-flex justify-content-center pt-1">
                        <div class="vr bg-secondary opacity-25" style="height: 110px;"></div>
                    </div>
                    
                    <!-- Columna Derecha: Seguridad Institucional -->
                    <div class="col-md-6 pt-1">
                        <p class="mb-0 text-justify">
                            <strong>Seguridad Institucional:</strong> Se aclara que la casa central de la editorial <u>no cuenta con cobradores ni promotores propios de contratación directa en la vía pública</u>. Toda apertura de cuentas mayoristas y acuerdos de distribución oficial se gestionan estrictamente a través de nuestros canales corporativos verificados.
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- 🚀 BANNER COMERCIAL: ACCESO AL CATÁLOGO -->
    <div class="w-100 rounded-3 overflow-hidden shadow-sm my-5 text-white position-relative entry-banner" 
         style="background-color: var(--azul-oscuro); min-height: 140px;">
            
        <a href="{{ route('catalogo') }}" class="text-decoration-none text-white d-block h-100">
            <div class="row g-0 align-items-center h-100">
                
                <div class="col-sm-6 d-none d-sm-flex align-items-center ps-4 py-2 position-relative overflow-hidden group-circles" style="height: 140px;">
                    <div class="rounded-circle bg-white shadow-sm position-absolute circle-item" 
                         style="width: 110px; height: 110px; left: 20px; z-index: 1; overflow: hidden; border: 3px solid #ffffff;">
                        <img src="{{ asset('storage/detallebanner/lostreschanchitos_full1.jpg') }}" class="w-100 h-100" style="object-fit: cover; object-position: center;" alt="Infantil">
                    </div>
                    
                    <div class="rounded-circle bg-white shadow-sm position-absolute circle-item" 
                         style="width: 110px; height: 110px; left: 100px; z-index: 2; overflow: hidden; border: 3px solid #ffffff;">
                        <img src="{{ asset('storage/detallebanner/principito_full1.jpg') }}" class="w-100 h-100" style="object-fit: cover; object-position: center; transform: scale(1.15);" alt="Infantil">
                    </div>
                    
                    <div class="rounded-circle bg-white shadow-sm position-absolute circle-item" 
                         style="width: 110px; height: 110px; left: 180px; z-index: 3; overflow: hidden; border: 3px solid #ffffff;">
                        <img src="{{ asset('storage/detallebanner/primerainfancia_full1.jpg') }}" class="w-100 h-100" style="object-fit: cover; object-position: center; transform: scale(1.15);" alt="Infantil">
                    </div>

                    <div class="rounded-circle bg-white shadow-sm position-absolute circle-item" 
                         style="width: 110px; height: 110px; left: 260px; z-index: 4; overflow: hidden; border: 3px solid #ffffff;">
                        <img src="{{ asset('storage/detallebanner/bolsa_zenon_mockups.jpg') }}" class="w-100 h-100" style="object-fit: cover; object-position: center; transform: scale(1.15);" alt="Infantil">
                    </div>
                </div>

                <div class="col-12 col-sm-6 text-center text-sm-end pe-sm-5 py-4 py-sm-0 container-text-action">
                    <div class="text-uppercase fw-bold text-action-banner" style="font-family: 'Arial Black', Gadget, sans-serif; letter-spacing: 0.5px; line-height: 1.2;">
                        <span class="d-block small-text" style="font-size: 1.1rem; color: rgba(255,255,255,0.85); font-weight: 700;">CLICK AQUÍ PARA</span>
                        <span class="d-block large-text" style="font-size: 2.1rem; color: #ffffff;">VER EL CATÁLOGO</span>
                    </div>
                </div>

            </div>
        </a>
    </div>

</div>

<style>
    .entry-banner:hover {
        background-color: #2c5ebd !important;
        transform: scale(1.005);
    }
    .entry-banner:hover .circle-item {
        transform: scale(1.05);
    }
    .card-compromiso {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-compromiso:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06) !important;
    }
    .style-badge {
        letter-spacing: 0.5px;
    }
    @media (min-width: 992px) {
        .border-start-lg {
            border-left: 1px solid #e2e8f0 !important;
        }
    }
</style>
@endsection