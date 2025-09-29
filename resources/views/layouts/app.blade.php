<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mapa Obras V2')</title>
    <meta name="description" content="@yield('description', 'Sistema de gestão e mapeamento de obras')">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        .hero-section {
            background: url('/images/markers/header-bg.png') center center/cover no-repeat;
            color: white;
            padding: 1rem 0;
            margin-bottom: 1rem;
            position: relative;
        }
        .hero-section h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .hero-section p {
            font-size: 1rem;
            margin-bottom: 0;
        }
        .hero-logo {
            position: absolute;
            top: 10px;
            left: 20px;
            z-index: 10;
            max-height: 60px;
        }
        .card-hover {
            transition: transform 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem 0;
        }
        .main-footer {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 0;
            margin-top: auto;
            border-top: 1px solid #34495e;
        }
        .main-footer a {
            color: #3498db;
            text-decoration: none;
        }
        .main-footer a:hover {
            color: #5dade2;
            text-decoration: underline;
        }
        .main-footer .float-right {
            float: right;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing.index') }}">
                <i class="fas fa-map-marked-alt"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.index') }}">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.obras-maps') }}">Mapa de Obras</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('obras.index') }}">Lista de Obras</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.bi') }}">BI</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.saiba-mais') }}">Saiba Mais</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <strong>Copyright &copy; {{ date('Y') }} <a href="https://www.seinfra.go.gov.br/">STI - SEINFRA</a>.</strong>
                    Todos os direitos reservados.
                    <div class="float-right d-none d-sm-inline-block">
                        @if (config('app.env') !== 'production')
                        <b>Core</b> {{ phpversion() }} | <b>Framework</b> {{ app()->version() }} | <b>Template</b> 3.2.0
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

    @yield('scripts')
</body>
</html>

