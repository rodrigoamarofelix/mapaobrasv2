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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing.bi2') }}">BI2</a>
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

    <!-- Botão de Acessibilidade -->
    <div id="accessibility-btn" class="accessibility-button">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Círculo externo -->
            <circle cx="16" cy="16" r="14" stroke="white" stroke-width="2" fill="none"/>

            <!-- Círculo central superior (cabeça) -->
            <circle cx="16" cy="10" r="3" fill="white"/>

            <!-- Linhas curvas conectando cabeça aos círculos laterais -->
            <path d="M 13 10 Q 8 14 8 18" stroke="white" stroke-width="2" fill="none"/>
            <path d="M 19 10 Q 24 14 24 18" stroke="white" stroke-width="2" fill="none"/>

            <!-- Círculos laterais superiores -->
            <circle cx="8" cy="18" r="2" fill="white"/>
            <circle cx="24" cy="18" r="2" fill="white"/>

            <!-- Linhas diagonais do centro para os círculos laterais -->
            <line x1="16" y1="13" x2="8" y2="18" stroke="white" stroke-width="2"/>
            <line x1="16" y1="13" x2="24" y2="18" stroke="white" stroke-width="2"/>

            <!-- Linhas diagonais dos círculos laterais para os inferiores -->
            <line x1="8" y1="18" x2="8" y2="24" stroke="white" stroke-width="2"/>
            <line x1="24" y1="18" x2="24" y2="24" stroke="white" stroke-width="2"/>

            <!-- Círculos inferiores -->
            <circle cx="8" cy="24" r="2" fill="white"/>
            <circle cx="24" cy="24" r="2" fill="white"/>

            <!-- Linha conectando os círculos inferiores -->
            <line x1="8" y1="24" x2="24" y2="24" stroke="white" stroke-width="2"/>
        </svg>
    </div>

    <!-- Menu de Acessibilidade -->
    <div id="accessibility-menu" class="accessibility-menu">
        <div class="accessibility-header">
            <h5>Acessibilidade</h5>
            <button id="close-accessibility" class="btn-close"></button>
        </div>
        <div class="accessibility-content">
            <!-- Tamanho da Fonte -->
            <div class="accessibility-item">
                <label>Tamanho da Fonte</label>
                <div class="btn-group">
                    <button id="decrease-font" class="btn btn-sm">A-</button>
                    <button id="reset-font" class="btn btn-sm">A</button>
                    <button id="increase-font" class="btn btn-sm">A+</button>
                </div>
            </div>

            <!-- Alto Contraste -->
            <div class="accessibility-item">
                <label>Alto Contraste</label>
                <button id="toggle-contrast" class="btn btn-sm">Ativar</button>
            </div>

            <!-- Escala de Cinza -->
            <div class="accessibility-item">
                <label>Escala de Cinza</label>
                <button id="toggle-grayscale" class="btn btn-sm">Ativar</button>
            </div>

            <!-- Destacar Links -->
            <div class="accessibility-item">
                <label>Destacar Links</label>
                <button id="toggle-links" class="btn btn-sm">Ativar</button>
            </div>

            <!-- Resetar Tudo -->
            <div class="accessibility-item">
                <button id="reset-all" class="btn btn-sm btn-danger">Resetar Tudo</button>
            </div>
        </div>
    </div>

    <!-- Estilos CSS do componente de acessibilidade -->
    <style>
        /* Botão flutuante de acessibilidade */
        .accessibility-button {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            background-color: #1976d2;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            z-index: 9999;
            transition: all 0.3s ease;
        }

        .accessibility-button:hover {
            background-color: #1565c0;
            transform: translateY(-50%) scale(1.1);
        }

        .accessibility-button i {
            font-size: 28px;
        }

        .accessibility-button svg {
            width: 32px;
            height: 32px;
        }

        /* Menu de acessibilidade */
        .accessibility-menu {
            position: fixed;
            right: -350px;
            top: 50%;
            transform: translateY(-50%);
            width: 320px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            transition: right 0.3s ease;
        }

        .accessibility-menu.active {
            right: 20px;
        }

        .accessibility-header {
            padding: 15px 20px;
            background-color: #1976d2;
            color: white;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .accessibility-content {
            padding: 20px;
        }

        .accessibility-item {
            margin-bottom: 20px;
        }

        .accessibility-item label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        /* Classes de acessibilidade */
        .high-contrast {
            filter: contrast(1.5);
        }

        .grayscale {
            filter: grayscale(100%);
        }

        .highlight-links a {
            background-color: yellow !important;
            color: black !important;
            text-decoration: underline !important;
        }
    </style>

    <!-- JavaScript do componente de acessibilidade -->
    <script>
        // Variáveis de estado
        let fontSize = 100;
        let contrastEnabled = false;
        let grayscaleEnabled = false;
        let linksHighlighted = false;

        // Carregar configurações do localStorage
        function loadAccessibilitySettings() {
            const settings = JSON.parse(localStorage.getItem('accessibilitySettings') || '{}');
            fontSize = settings.fontSize || 100;
            contrastEnabled = settings.contrast || false;
            grayscaleEnabled = settings.grayscale || false;
            linksHighlighted = settings.links || false;

            applySettings();
        }

        // Salvar configurações no localStorage
        function saveSettings() {
            localStorage.setItem('accessibilitySettings', JSON.stringify({
                fontSize,
                contrast: contrastEnabled,
                grayscale: grayscaleEnabled,
                links: linksHighlighted
            }));
        }

        // Aplicar configurações
        function applySettings() {
            document.documentElement.style.fontSize = fontSize + '%';

            if (contrastEnabled) {
                document.body.classList.add('high-contrast');
            } else {
                document.body.classList.remove('high-contrast');
            }

            if (grayscaleEnabled) {
                document.body.classList.add('grayscale');
            } else {
                document.body.classList.remove('grayscale');
            }

            if (linksHighlighted) {
                document.body.classList.add('highlight-links');
            } else {
                document.body.classList.remove('highlight-links');
            }

            updateButtonStates();
        }

        // Atualizar estado dos botões
        function updateButtonStates() {
            document.getElementById('toggle-contrast').textContent = contrastEnabled ? 'Desativar' : 'Ativar';
            document.getElementById('toggle-grayscale').textContent = grayscaleEnabled ? 'Desativar' : 'Ativar';
            document.getElementById('toggle-links').textContent = linksHighlighted ? 'Desativar' : 'Ativar';
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            loadAccessibilitySettings();

            // Toggle menu
            document.getElementById('accessibility-btn').addEventListener('click', function() {
                document.getElementById('accessibility-menu').classList.toggle('active');
            });

            document.getElementById('close-accessibility').addEventListener('click', function() {
                document.getElementById('accessibility-menu').classList.remove('active');
            });

            // Fonte
            document.getElementById('increase-font').addEventListener('click', function() {
                if (fontSize < 150) {
                    fontSize += 10;
                    applySettings();
                    saveSettings();
                }
            });

            document.getElementById('decrease-font').addEventListener('click', function() {
                if (fontSize > 80) {
                    fontSize -= 10;
                    applySettings();
                    saveSettings();
                }
            });

            document.getElementById('reset-font').addEventListener('click', function() {
                fontSize = 100;
                applySettings();
                saveSettings();
            });

            // Alto contraste
            document.getElementById('toggle-contrast').addEventListener('click', function() {
                contrastEnabled = !contrastEnabled;
                applySettings();
                saveSettings();
            });

            // Escala de cinza
            document.getElementById('toggle-grayscale').addEventListener('click', function() {
                grayscaleEnabled = !grayscaleEnabled;
                applySettings();
                saveSettings();
            });

            // Destacar links
            document.getElementById('toggle-links').addEventListener('click', function() {
                linksHighlighted = !linksHighlighted;
                applySettings();
                saveSettings();
            });

            // Resetar tudo
            document.getElementById('reset-all').addEventListener('click', function() {
                fontSize = 100;
                contrastEnabled = false;
                grayscaleEnabled = false;
                linksHighlighted = false;
                applySettings();
                saveSettings();
            });
        });
    </script>
</body>
</html>

