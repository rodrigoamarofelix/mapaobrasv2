<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municípios Importados - Mapa Obras V2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 2em;
            color: #e74c3c;
            margin-bottom: 10px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #e74c3c;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #e74c3c;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
        .actions {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #c0392b;
        }
        .btn-secondary {
            background: #3498db;
        }
        .btn-secondary:hover {
            background: #2980b9;
        }
        .municipios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .municipio-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #3498db;
            transition: transform 0.2s;
        }
        .municipio-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .municipio-nome {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .municipio-uf {
            color: #e74c3c;
            font-size: 0.9em;
        }
        .pagination {
            text-align: center;
            margin-top: 30px;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a:hover {
            background: #c0392b;
        }
        .pagination .current {
            background: #95a5a6;
            cursor: default;
        }
        .error {
            background: #e74c3c;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .uf-stats {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .uf-stats h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .uf-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }
        .uf-item {
            background: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .uf-sigla {
            font-weight: bold;
            color: #e74c3c;
        }
        .uf-count {
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏗️ Mapa Obras V2</div>
            <h1>Municípios Importados</h1>
            <p>Sistema de Gestão de Obras - Dados do IBGE</p>
        </div>

        @if(isset($erro))
            <div class="error">
                <strong>Erro:</strong> {{ $erro }}
            </div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number">{{ $total ?? 0 }}</div>
                <div class="stat-label">Total de Municípios</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $porUf->count() ?? 0 }}</div>
                <div class="stat-label">Estados/UF</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $municipios->count() ?? 0 }}</div>
                <div class="stat-label">Exibindo</div>
            </div>
        </div>

        @if(isset($porUf) && $porUf->count() > 0)
        <div class="uf-stats">
            <h3>📊 Distribuição por UF</h3>
            <div class="uf-list">
                @foreach($porUf as $uf)
                <div class="uf-item">
                    <div class="uf-sigla">{{ $uf->uf }}</div>
                    <div class="uf-count">{{ $uf->total }} municípios</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="actions">
            <a href="{{ route('obras.import-municipios') }}" class="btn" onclick="return confirm('Isso irá substituir todos os municípios existentes. Continuar?')">
                🔄 Reimportar Municípios
            </a>
            <a href="{{ route('obras.index') }}" class="btn btn-secondary">
                📋 Voltar para Obras
            </a>
        </div>

        @if(isset($municipios) && $municipios->count() > 0)
        <div class="municipios-grid">
            @foreach($municipios as $municipio)
            <div class="municipio-card">
                <div class="municipio-nome">{{ $municipio->nome }}</div>
                <div class="municipio-uf">📍 {{ $municipio->uf }}</div>
            </div>
            @endforeach
        </div>

        @if($municipios->hasPages())
        <div class="pagination">
            {{ $municipios->links() }}
        </div>
        @endif
        @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <h3>Nenhum município encontrado</h3>
            <p>Clique em "Reimportar Municípios" para buscar dados do IBGE.</p>
        </div>
        @endif
    </div>
</body>
</html>
