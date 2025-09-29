@extends('layouts.app')

@section('title', 'Detalhes da Obra - Mapa Obras V2')
@section('description', 'Informações detalhadas sobre a obra')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('landing.index') }}">Início</a></li>
            <li class="breadcrumb-item"><a href="{{ route('obras.index') }}">Obras</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
        </ol>
    </nav>

    @if(isset($obra))
        <!-- Header Section -->
        <div class="hero-section">
            <img src="/images/markers/logocgeatualizada2023.png" alt="Logo CGE" class="hero-logo img-fluid">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h1 class="display-5 fw-bold mb-3">{{ $obra->nome_projeto ?? 'Nome não informado' }}</h1>
                        <p class="lead mb-4">{{ $obra->objeto ?? 'Objeto não informado' }}</p>
                        <div class="d-flex gap-3">
                            <span class="badge bg-{{ $obra->situacao_obra == 'C' ? 'success' : ($obra->situacao_obra == 'A' ? 'primary' : ($obra->situacao_obra == 'I' ? 'warning' : 'secondary')) }} fs-6">
                                {{ handler_situation_work($obra->situacao_obra) ?? 'Não informado' }}
                            </span>
                            @if($obra->municipio)
                                <span class="badge bg-light text-dark fs-6">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $obra->municipio }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mt-4">
            <div class="row">
                <!-- Informações Principais -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informações Principais
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID do Projeto:</strong></td>
                                            <td>{{ $obra->id_projeto ?? 'Não informado' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Área Temática:</strong></td>
                                            <td>{{ $obra->area_tematica ?? 'Não informado' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Data de Início:</strong></td>
                                            <td>{{ $obra->data_de_inicio_ou_previsao ? date('d/m/Y', strtotime($obra->data_de_inicio_ou_previsao)) : 'Não informado' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Data Prevista Conclusão:</strong></td>
                                            <td>{{ $obra->data_prevista_conclusao ? date('d/m/Y', strtotime($obra->data_prevista_conclusao)) : 'Não informado' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Valor Pago:</strong></td>
                                            <td>R$ {{ number_format($obra->valor_pago ?? 0, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saldo a Pagar:</strong></td>
                                            <td>R$ {{ number_format($obra->saldo_a_pagar ?? 0, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Valor Empenhado:</strong></td>
                                            <td>R$ {{ number_format($obra->valorEmpenhado ?? 0, 2, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Valor Liquidado:</strong></td>
                                            <td>R$ {{ number_format($obra->valorLiquidado ?? 0, 2, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progresso da Execução -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Progresso da Execução
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="progress mb-3" style="height: 30px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                             role="progressbar"
                                             style="width: {{ $obra->estagio_execucao_percentual ?? 0 }}%"
                                             aria-valuenow="{{ $obra->estagio_execucao_percentual ?? 0 }}"
                                             aria-valuemin="0" aria-valuemax="100">
                                            {{ $obra->estagio_execucao_percentual ?? 0 }}%
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <h4 class="text-primary mb-0">{{ $obra->estagio_execucao_percentual ?? 0 }}%</h4>
                                    <small class="text-muted">Executado</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações de Parceria -->
                    @if($obra->envolve_parceria_captacao_de_recursos)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-handshake me-2"></i>Informações de Parceria
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Tipo de Instrumento:</strong></td>
                                                <td>{{ $obra->tipo_de_instrumento ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Número do Instrumento:</strong></td>
                                                <td>{{ $obra->numero_do_instrumento ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Parceiro Concedente:</strong></td>
                                                <td>{{ $obra->nome_do_parceiro_concedente ?? 'Não informado' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Início Vigência:</strong></td>
                                                <td>{{ $obra->início_vig_instrumento ? date('d/m/Y', strtotime($obra->início_vig_instrumento)) : 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Final Vigência:</strong></td>
                                                <td>{{ $obra->final_vig_Instrumento ? date('d/m/Y', strtotime($obra->final_vig_Instrumento)) : 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Valor Recurso Parceiro:</strong></td>
                                                <td>R$ {{ number_format($obra->valor_recurso_parceiro ?? 0, 2, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Informações de Paralisação -->
                    @if($obra->situacao_obra == 'I' && $obra->motivo_da_paralisacao)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Informações de Paralisação
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Data de Paralisação:</strong></td>
                                                <td>{{ $obra->data_de_paralisacao ? date('d/m/Y', strtotime($obra->data_de_paralisacao)) : 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Data Prevista Retomada:</strong></td>
                                                <td>{{ $obra->data_previa_de_retomada ? date('d/m/Y', strtotime($obra->data_previa_de_retomada)) : 'Não informado' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Tempo de Paralisação:</strong></td>
                                                <td>{{ $obra->Tempo_de_paralisacao ?? 'Não informado' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Responsável pela Inexecução:</strong></td>
                                                <td>{{ $obra->responsavel_pela_inexecucao ?? 'Não informado' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <strong>Motivo da Paralisação:</strong>
                                    <p class="mt-2">{{ $obra->motivo_da_paralisacao }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Mapa -->
                    @if($obra->latitude && $obra->longitude)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-map me-2"></i>Localização
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div id="map" style="height: 300px; width: 100%;"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Documentos -->
                    @if($obra->nomeDocumentoEmpreita || $obra->linkDocumentoEmpreita)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-file-alt me-2"></i>Documentos
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($obra->nomeDocumentoEmpreita)
                                    <p><strong>Documento:</strong> {{ $obra->nomeDocumentoEmpreita }}</p>
                                @endif
                                @if($obra->linkDocumentoEmpreita)
                                    <a href="{{ $obra->linkDocumentoEmpreita }}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-external-link-alt me-1"></i>Abrir Documento
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Ações -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cog me-2"></i>Ações
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('obras.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Voltar à Lista
                                </a>
                                <a href="{{ route('obras.detalhe-mapas', $obra->id) }}" class="btn btn-primary">
                                    <i class="fas fa-map me-1"></i>Ver no Mapa
                                </a>
                                <button class="btn btn-success" onclick="exportarObra()">
                                    <i class="fas fa-download me-1"></i>Exportar Dados
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Erro -->
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Obra não encontrada</h5>
                            <p class="card-text">A obra solicitada não foi encontrada ou não existe.</p>
                            <a href="{{ route('obras.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-1"></i>Voltar à Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if(isset($obra) && $obra->latitude && $obra->longitude)
<script>
    // Inicializar mapa
    let map;

    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });

    function initMap() {
        map = L.map('map').setView([{{ $obra->latitude }}, {{ $obra->longitude }}], 15);

        // Adicionar camada de tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Adicionar marcador
        L.marker([{{ $obra->latitude }}, {{ $obra->longitude }}])
            .addTo(map)
            .bindPopup(`
                <div class="popup-content">
                    <h6 class="fw-bold">{{ $obra->nome_projeto ?? 'Nome não informado' }}</h6>
                    <p class="mb-2"><strong>Objeto:</strong> {{ $obra->objeto ?? 'Não informado' }}</p>
                    <p class="mb-2"><strong>Situação:</strong> {{ handler_situation_work($obra->situacao_obra) ?? 'Não informado' }}</p>
                    <p class="mb-2"><strong>Município:</strong> {{ $obra->municipio ?? 'Não informado' }}</p>
                </div>
            `);
    }

    function exportarObra() {
        const obra = @json($obra);
        const dataStr = JSON.stringify(obra, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        const url = URL.createObjectURL(dataBlob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'obra_{{ $obra->id }}_' + new Date().toISOString().split('T')[0] + '.json';
        link.click();
    }
</script>
@endif
@endsection

