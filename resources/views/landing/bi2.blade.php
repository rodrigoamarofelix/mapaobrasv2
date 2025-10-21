@extends('layouts.app')

@section('title', 'Painel de Obras - Mapa Obras V2')
@section('description', 'Painel de controle das obras públicas do estado de Goiás')

@section('content')
<div class="bi2-container">
    <!-- Modal de Filtros Avançados -->
    <div class="modal fade" id="filtrosModal" tabindex="-1" aria-labelledby="filtrosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filtrosModalLabel">
                        <i class="fas fa-filter me-2"></i>Filtros Avançados
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <form id="formFiltrosAvancados" method="GET" action="{{ route('landing.bi2') }}">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <label class="form-label">ID PROJETO</label>
                                <select class="form-select" name="id_projeto">
                                    <option value="">Todos</option>
                                    @foreach($ids_projeto ?? [] as $proj)
                                    <option value="{{ is_object($proj) ? ($proj->id_projeto ?? $proj->id) : $proj }}">{{ is_object($proj) ? ($proj->id_projeto ?? $proj->id) : $proj }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ÁREA TEMÁTICA</label>
                                <select class="form-select" name="area_tematica">
                                    <option value="">Todos</option>
                                    @foreach($areaTematica ?? [] as $area)
                                    <option value="{{ is_object($area) ? ($area->area_tematica ?? $area) : $area }}">{{ is_object($area) ? ($area->area_tematica ?? $area) : $area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">MUNICÍPIO</label>
                                <select class="form-select" name="municipio">
                                    <option value="">Todos</option>
                                    @foreach($municipios ?? [] as $mun)
                                    <option value="{{ is_object($mun) ? ($mun->nome ?? $mun->municipio ?? $mun) : $mun }}">{{ is_object($mun) ? ($mun->nome ?? $mun->municipio ?? $mun) : $mun }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ÓRGÃO</label>
                                <select class="form-select" name="orgao">
                                    <option value="">Todos</option>
                                    @foreach($orgao ?? [] as $org)
                                    <option value="{{ is_object($org) ? ($org->orgao ?? $org) : $org }}">{{ is_object($org) ? ($org->orgao ?? $org) : $org }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">SITUAÇÃO</label>
                                <select class="form-select" name="situacao">
                                    <option value="">Todos</option>
                                    @foreach($situacoes ?? [] as $sit)
                                    <option value="{{ is_object($sit) ? ($sit->situacao_obra ?? $sit) : $sit }}">{{ is_object($sit) ? ($sit->situacao_obra ?? $sit) : $sit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">NOME OBRA</label>
                                <select class="form-select" name="nome_obra">
                                    <option value="">Todos</option>
                                    @foreach($nome_obras ?? [] as $obra)
                                    <option value="{{ is_object($obra) ? ($obra->nome_projeto ?? $obra) : $obra }}">{{ is_object($obra) ? ($obra->nome_projeto ?? $obra) : $obra }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">OBJETO</label>
                                <select class="form-select" name="objeto">
                                    <option value="">Todos</option>
                                    @foreach($objetos ?? [] as $obj)
                                    <option value="{{ is_object($obj) ? ($obj->objeto ?? $obj) : $obj }}">{{ is_object($obj) ? ($obj->objeto ?? $obj) : $obj }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">TIPO INSTRUMENTO</label>
                                <select class="form-select" name="tipo_instrumento">
                                    <option value="">Todos</option>
                                    @foreach($tipos_instrumento ?? [] as $tipo)
                                    <option value="{{ is_object($tipo) ? ($tipo->tipo_de_instrumento ?? $tipo) : $tipo }}">{{ is_object($tipo) ? ($tipo->tipo_de_instrumento ?? $tipo) : $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">PARCEIRO CONCEDENTE</label>
                                <select class="form-select" name="parceiro_concedente">
                                    <option value="">Todos</option>
                                    @foreach($parceiros_concedentes ?? [] as $parc)
                                    <option value="{{ is_object($parc) ? ($parc->nome_do_parceiro_concedente ?? $parc) : $parc }}">{{ is_object($parc) ? ($parc->nome_do_parceiro_concedente ?? $parc) : $parc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">MOTIVO PARALISAÇÃO</label>
                                <select class="form-select" name="motivo_paralisacao">
                                    <option value="">Todos</option>
                                    @foreach($motivos_paralisacao ?? [] as $mot)
                                    <option value="{{ is_object($mot) ? ($mot->motivo_da_paralisacao ?? $mot) : $mot }}">{{ is_object($mot) ? ($mot->motivo_da_paralisacao ?? $mot) : $mot }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">RESPONSÁVEL INEXECUÇÃO</label>
                                <select class="form-select" name="responsavel_inexecucao">
                                    <option value="">Todos</option>
                                    @foreach($responsaveis_inexecucao ?? [] as $resp)
                                    <option value="{{ is_object($resp) ? ($resp->responsavel_pela_inexecucao ?? $resp) : $resp }}">{{ is_object($resp) ? ($resp->responsavel_pela_inexecucao ?? $resp) : $resp }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nº CONTRATO</label>
                                <select class="form-select" name="numero_contrato">
                                    <option value="">Todos</option>
                                    @foreach($numeros_contrato ?? [] as $contr)
                                    <option value="{{ is_object($contr) ? ($contr->numero_contrato ?? $contr) : $contr }}">{{ is_object($contr) ? ($contr->numero_contrato ?? $contr) : $contr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nº EMENDA</label>
                                <select class="form-select" name="numero_emenda">
                                    <option value="">Todos</option>
                                    @foreach($numeros_emenda ?? [] as $emenda)
                                    <option value="{{ is_object($emenda) ? ($emenda->numero_da_emenda ?? $emenda) : $emenda }}">{{ is_object($emenda) ? ($emenda->numero_da_emenda ?? $emenda) : $emenda }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Nº PROCESSO</label>
                                <select class="form-select" name="numero_processo">
                                    <option value="">Todos</option>
                                    @foreach($numeros_processo ?? [] as $proc)
                                    <option value="{{ is_object($proc) ? ($proc->numeroprocessosei ?? $proc) : $proc }}">{{ is_object($proc) ? ($proc->numeroprocessosei ?? $proc) : $proc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                    </form>
                                        </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('formFiltrosAvancados').submit()">
                        <i class="fas fa-search me-2"></i>Aplicar Filtros
                                </button>
                            </div>
                        </div>
                </div>
            </div>

    <div class="bi2-layout">
        <!-- Sidebar -->
        <div class="bi2-sidebar">
            <div class="sidebar-header">
                <h3>MAPA DE OBRAS</h3>
        </div>
            <div class="sidebar-menu">
                <a href="#" class="menu-item active">
                    <i class="fas fa-chart-bar"></i>
                    OBRAS - GOMAP
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-search"></i>
                    DETALHES DO PROJETO
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-pause"></i>
                    OBRAS PARALISADAS
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-handshake"></i>
                    OBRAS COM CONVÊNIOS E OUTRAS PARCERIAS
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-file-contract"></i>
                    INFORMAÇÕES CONTRATUAIS E ANEXOS
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-info"></i>
                    SOBRE
                </a>
    </div>
        </div>

        <!-- Main Content -->
        <div class="bi2-content">
            <!-- Content Header -->
            <div class="content-header">
                <h2>OBRAS - GOMAP</h2>
                <button type="button" class="btn btn-success btn-lg d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#filtrosModal" style="font-weight: 600; letter-spacing: 1px;">
                    <i class="fas fa-filter me-2"></i>
                    FILTROS
                </button>
                <button type="button" class="btn btn-secondary btn-lg d-flex align-items-center gap-2 ms-2" id="restoreButton" onclick="restoreOriginalCardValues()" style="font-weight: 600; letter-spacing: 1px;">
                    <i class="fas fa-undo me-2"></i>
                    RESTAURAR VALORES
                </button>
            </div>

            <!-- Metrics Section -->
            <div class="metrics-section">
                <!-- Financial Resources -->
                <div class="metrics-row">
                    <div class="metric-card" data-card="recursos-captacao-externa">
                        <div class="metric-icon">
                            <i class="fas fa-coins"></i>
                    </div>
                        <div class="metric-content">
                            <h3>RECURSOS DE CAPTAÇÃO EXTERNA</h3>
                            <p class="metric-value">R$ {{ number_format(($recursos_financeiros->recursos_captacao_externa ?? 0) / 1000000, 2, ',', '.') }} Mi</p>
                    </div>
                    </div>
                    <div class="metric-card" data-card="recursos-estado">
                        <div class="metric-icon">
                            <i class="fas fa-plus"></i>
                </div>
                        <div class="metric-content">
                            <h3>RECURSOS DO ESTADO</h3>
                            <p class="metric-value">R$ {{ number_format(($recursos_financeiros->recursos_estado ?? 0) / 1000000000, 2, ',', '.') }} Bi</p>
                    </div>
                    </div>
                    <div class="metric-card" data-card="recursos-totais">
                        <div class="metric-icon">
                            <i class="fas fa-equals"></i>
                    </div>
                        <div class="metric-content">
                            <h3>RECURSOS TOTAIS</h3>
                            <p class="metric-value">R$ {{ number_format(($recursos_financeiros->recursos_totais ?? 0) / 1000000000, 2, ',', '.') }} Bi</p>
                    </div>
                </div>
            </div>

                <!-- Status Works -->
                <div class="metrics-row">
                    <div class="metric-card" data-card="obras-concluidas">
                        <div class="metric-icon">
                            <i class="fas fa-check-circle"></i>
                                </div>
                        <div class="metric-content">
                            <h3>TOTAL EM OBRAS CONCLUÍDAS</h3>
                            <p class="metric-value">R$ {{ number_format(($status_obras->obras_concluidas ?? 0) / 1000000000, 2, ',', '.') }} Bi</p>
                            </div>
                        </div>
                    <div class="metric-card" data-card="obras-andamento">
                        <div class="metric-icon">
                            <i class="fas fa-pause-circle"></i>
                    </div>
                        <div class="metric-content">
                            <h3>TOTAL EM OBRAS PARALISADAS</h3>
                            <p class="metric-value">R$ {{ number_format(($status_obras->obras_paralisadas ?? 0) / 1000000, 2, ',', '.') }} Mi</p>
                </div>
                                </div>
                    <div class="metric-card" data-card="total-projetos">
                        <div class="metric-icon">
                            <i class="fas fa-play-circle"></i>
                            </div>
                        <div class="metric-content">
                            <h3>TOTAL OBRAS EM ANDAMENTO</h3>
                            <p class="metric-value">R$ {{ number_format(($status_obras->obras_andamento ?? 0) / 1000000000, 2, ',', '.') }} Bi</p>
                        </div>
                    </div>
                    <div class="metric-card" data-card="projetos-parceria">
                        <div class="metric-icon">
                            <i class="fas fa-project-diagram"></i>
                </div>
                        <div class="metric-content">
                            <h3>TOTAL DE PROJETOS</h3>
                            <p class="metric-value">{{ number_format($total_projetos ?? 0, 0, ',', '.') }}</p>
            </div>
                    </div>
                        </div>
                        </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <div class="charts-row">
                    <!-- Investment by Municipality Chart -->
                    <div class="chart-container">
                        <div class="chart-card">
                            <h4>TOTAL DE INVESTIMENTOS POR MUNICÍPIOS</h4>
                            <div class="chart-wrapper">
                                <canvas id="investimentosMunicipiosChart"></canvas>
                    </div>
                </div>
            </div>

                    <!-- Projects by Municipality Chart -->
                    <div class="chart-container">
                        <div class="chart-card">
                            <h4>TOTAL DE PROJETOS POR MUNICÍPIOS</h4>
                            <div class="chart-wrapper">
                                <canvas id="projetosMunicipiosChart"></canvas>
                                        </div>
                                    </div>
                </div>
            </div>

                <!-- Partnership Chart -->
                <div class="charts-row">
                    <div class="chart-container-center">
                        <div class="chart-card">
                            <h4>PROJETOS COM CAPTAÇÃO DE RECURSOS X PROJETOS COM RECURSOS DO ESTADO</h4>
                            <div class="chart-wrapper">
                                <canvas id="projetosParceriaChart"></canvas>
        </div>
    </div>
</div>
    </div>
    </div>
</div>
</div>
            </div>

<!-- Debug Section -->
@if(isset($debug_info))
<div style="background: #f8f9fa; padding: 20px; margin: 20px; border-radius: 8px; font-family: monospace; font-size: 12px;">
    <h4>🔍 Debug Info:</h4>
    <pre>{{ json_encode($debug_info, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif

<style>
    .bi2-container {
        min-height: 100vh;
    background: #f8f9fa;
    padding: 0;
    }

.bi2-layout {
        display: flex;
    gap: 0;
    max-width: 100%;
        margin: 0;
    min-height: 100vh;
}

.bi2-sidebar {
    width: 250px;
    flex-shrink: 0;
    background: #e9ecef;
    padding: 20px;
    border-right: 1px solid #dee2e6;
}

.sidebar-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid #dee2e6;
}

.sidebar-header h3 {
    color: #495057;
    font-weight: 700;
    margin: 0;
    font-size: 18px;
    }

    .sidebar-menu {
        display: flex;
    flex-direction: column;
    gap: 5px;
    }

    .menu-item {
        display: flex;
        align-items: center;
    gap: 12px;
    padding: 12px 15px;
    border-radius: 5px;
    text-decoration: none;
    color: #495057;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 14px;
    }

    .menu-item:hover {
    background: #dee2e6;
    color: #495057;
    }

    .menu-item.active {
    background: #28a745;
    color: white;
}

.menu-item.active::before {
    content: '';
    width: 4px;
    height: 20px;
    background: #28a745;
    border-radius: 2px;
    margin-right: 8px;
    }

    .menu-item i {
    font-size: 16px;
        width: 20px;
}

    .bi2-content {
        flex: 1;
    background: white;
        padding: 30px;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
    }

    .content-header h2 {
    color: #2c3e50;
    font-weight: 700;
        margin: 0;
        font-size: 28px;
}

    .metrics-section {
        margin-bottom: 40px;
    }

    .metrics-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

.metrics-row:last-child {
    grid-template-columns: repeat(4, 1fr);
    }

    .metric-card {
        background: white;
    color: #495057;
        padding: 20px;
        border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    transition: transform 0.3s ease;
    min-height: 80px;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.metric-icon {
    font-size: 2rem;
    color: #28a745;
    flex-shrink: 0;
}

.metric-content h3 {
        font-size: 12px;
    font-weight: 600;
    margin: 0 0 5px 0;
    color: #6c757d;
    letter-spacing: 0.5px;
    line-height: 1.2;
    }

    .metric-value {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    color: #2c3e50;
}

    .charts-section {
    margin-top: 40px;
}

.charts-row {
    display: flex;
    gap: 20px;
        margin-bottom: 20px;
    }

    .chart-container {
    flex: 1;
}

.chart-container-center {
        display: flex;
    justify-content: center;
    width: 100%;
}

.chart-container-center .chart-card {
    max-width: 400px;
    width: 100%;
}

.chart-container-center .chart-wrapper {
    height: 300px;
    overflow: visible;
}

.chart-card {
        background: white;
        border-radius: 8px;
    padding: 25px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
        height: 100%;
}

.chart-card h4 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
    font-size: 16px;
}

.chart-container-center .chart-card h4 {
        font-size: 14px;
    margin-bottom: 15px;
}

.chart-wrapper {
    height: 300px;
    position: relative;
    overflow-y: auto;
    overflow-x: hidden;
    border: 1px solid #e9ecef;
    border-radius: 4px;
        background: white;
}

.chart-wrapper::-webkit-scrollbar {
    width: 8px;
}

.chart-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.chart-wrapper::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.chart-wrapper::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.chart-wrapper canvas {
    display: block;
    width: 100% !important;
    height: auto !important;
}

.modal-xl {
    max-width: 90%;
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
    font-size: 12px;
    margin-bottom: 5px;
}

.form-select, .form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    font-size: 14px;
}

.form-select:focus, .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
        background: #28a745;
    border: none;
    border-radius: 8px;
    padding: 12px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-primary {
        background: #007bff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
}

.btn-secondary {
    background: #6c757d;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
}

    @media (max-width: 1200px) {
        .metrics-row {
        grid-template-columns: repeat(2, 1fr);
        }

    .metrics-row:last-child {
        grid-template-columns: repeat(2, 1fr);
        }

    .charts-row {
        flex-direction: column;
        }
    }

    @media (max-width: 768px) {
    .bi2-layout {
            flex-direction: column;
        }

        .bi2-sidebar {
            width: 100%;
        }

    .metrics-row {
        grid-template-columns: 1fr;
    }

    .metrics-row:last-child {
        grid-template-columns: 1fr;
    }

    .content-header {
            flex-direction: column;
        gap: 15px;
        text-align: center;
        }

    .charts-row {
            flex-direction: column;
    }

    .chart-wrapper {
        height: 300px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dados dos gráficos
    const investimentosData = @json($investimentos_municipios ?? []);
    const projetosData = @json($projetos_municipios ?? []);

    // Variáveis para controle de seleção
    let selectedMunicipio = null;
    let selectedPartnershipType = null; // 'com_parceria' ou 'sem_parceria'
    let investimentosChart = null;
    let projetosChart = null;
    let parceriasChart = null;

    // Cores para estados
    const colors = {
        selected: '#28a745',      // Verde normal
        unselected: '#90EE90',    // Verde claro
        hover: '#20c997'          // Verde médio
    };

    // Função para atualizar cores dos gráficos de barras
    function updateChartColors(chart, selectedIndex) {
        const backgroundColor = chart.data.labels.map((label, index) => {
            return index === selectedIndex ? colors.selected : colors.unselected;
        });

        const borderColor = chart.data.labels.map((label, index) => {
            return index === selectedIndex ? colors.selected : colors.unselected;
        });

        chart.data.datasets[0].backgroundColor = backgroundColor;
        chart.data.datasets[0].borderColor = borderColor;
        chart.update();
    }

    // Função para filtrar municípios por tipo de parceria
    function filterMunicipiosByPartnership(partnershipType) {
        // Esta função seria implementada com dados reais do banco
        // Por enquanto, vamos simular com dados hardcoded
        const municipiosComParceria = ['GOIÂNIA', 'APARECIDA DE GOIÂNIA', 'ANÁPOLIS'];
        const municipiosSemParceria = ['ITUMBIARA', 'LUZIÂNIA', 'FORMOSA', 'CATALÃO'];

        let municipiosFiltrados = [];
        if (partnershipType === 'com_parceria') {
            municipiosFiltrados = municipiosComParceria;
        } else if (partnershipType === 'sem_parceria') {
            municipiosFiltrados = municipiosSemParceria;
        }

        // Atualizar cores dos gráficos de barras baseado no filtro
        updateChartColorsByMunicipios(municipiosFiltrados);

        // Atualizar cards com valores agregados dos municípios filtrados
        updateCardsWithFilteredMunicipios(municipiosFiltrados);
    }

    // Função para atualizar cards com valores agregados dos municípios filtrados
    function updateCardsWithFilteredMunicipios(municipiosFiltrados) {
        // Calcular valores agregados dos municípios filtrados
        let totalInvestimento = 0;
        let totalProjetos = 0;

        municipiosFiltrados.forEach(municipio => {
            const investimentoData = investimentosData.find(item => item.municipio === municipio);
            const projetoData = projetosData.find(item => item.municipio === municipio);

            if (investimentoData) {
                totalInvestimento += investimentoData.valor_investimento || 0;
            }
            if (projetoData) {
                totalProjetos += projetoData.total_projetos || 0;
            }
        });

        // Atualizar cards com valores agregados
        updateCardValue('recursos-captacao-externa', totalInvestimento);
        updateCardValue('recursos-estado', totalInvestimento);
        updateCardValue('recursos-totais', totalInvestimento);
        updateCardValue('obras-concluidas', totalInvestimento);
        updateCardValue('obras-andamento', totalInvestimento);
        updateCardValue('total-projetos', totalProjetos);
        updateCardValue('projetos-parceria', totalProjetos);
    }

    // Função para atualizar cores dos gráficos baseado em lista de municípios
    function updateChartColorsByMunicipios(municipiosFiltrados) {
        if (investimentosChart) {
            const backgroundColor = investimentosChart.data.labels.map((label) => {
                return municipiosFiltrados.includes(label) ? colors.selected : colors.unselected;
            });
            const borderColor = investimentosChart.data.labels.map((label) => {
                return municipiosFiltrados.includes(label) ? colors.selected : colors.unselected;
            });
            investimentosChart.data.datasets[0].backgroundColor = backgroundColor;
            investimentosChart.data.datasets[0].borderColor = borderColor;
            investimentosChart.update();
        }
        
        if (projetosChart) {
            const backgroundColor = projetosChart.data.labels.map((label) => {
                return municipiosFiltrados.includes(label) ? colors.selected : colors.unselected;
            });
            const borderColor = projetosChart.data.labels.map((label) => {
                return municipiosFiltrados.includes(label) ? colors.selected : colors.unselected;
            });
            projetosChart.data.datasets[0].backgroundColor = backgroundColor;
            projetosChart.data.datasets[0].borderColor = borderColor;
            projetosChart.update();
        }
    }

    // Função para atualizar gráfico de parcerias com dados de um município específico
    function updatePartnershipChartForMunicipio(municipio) {
        if (!parceriasChart) return;

        // Simular dados específicos do município
        // Em uma implementação real, você buscaria esses dados do banco
        let comParceria = 0;
        let semParceria = 0;

        // Simulação baseada no município
        if (['GOIÂNIA', 'APARECIDA DE GOIÂNIA', 'ANÁPOLIS'].includes(municipio)) {
            comParceria = 80;
            semParceria = 20;
        } else {
            comParceria = 10;
            semParceria = 90;
        }

        parceriasChart.data.datasets[0].data = [semParceria, comParceria];
        parceriasChart.update();
    }

    // Função para atualizar cards com dados do município selecionado
    function updateCardsWithMunicipioData(municipio) {
        // Encontrar dados do município selecionado
        const investimentoData = investimentosData.find(item => item.municipio === municipio);
        const projetoData = projetosData.find(item => item.municipio === municipio);

        if (investimentoData && projetoData) {
            // Atualizar cards com valores específicos do município
            updateCardValue('recursos-captacao-externa', investimentoData.valor_investimento);
            updateCardValue('recursos-estado', investimentoData.valor_investimento);
            updateCardValue('recursos-totais', investimentoData.valor_investimento);
            updateCardValue('obras-concluidas', investimentoData.valor_investimento);
            updateCardValue('obras-andamento', investimentoData.valor_investimento);
            updateCardValue('total-projetos', projetoData.total_projetos);
            updateCardValue('projetos-parceria', projetoData.total_projetos);
        }
    }

    // Função para atualizar valor de um card específico
    function updateCardValue(cardId, value) {
        const cardElement = document.querySelector(`[data-card="${cardId}"] .metric-value`);
        if (cardElement) {
            if (cardId.includes('projetos')) {
                // Para projetos, mostrar número inteiro
                cardElement.textContent = value.toLocaleString('pt-BR');
            } else {
                // Para valores monetários, mostrar em milhões
                cardElement.textContent = 'R$ ' + (value / 1000000).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' Mi';
            }
        }
    }

    // Função para restaurar valores originais dos cards
    function restoreOriginalCardValues() {
        // Restaurar valores originais (você pode ajustar estes valores conforme necessário)
        updateCardValue('recursos-captacao-externa', {{ $recursos_financeiros->recursos_captacao_externa ?? 0 }});
        updateCardValue('recursos-estado', {{ $recursos_financeiros->recursos_estado ?? 0 }});
        updateCardValue('recursos-totais', {{ $recursos_financeiros->recursos_totais ?? 0 }});
        updateCardValue('obras-concluidas', {{ $status_obras->obras_concluidas ?? 0 }});
        updateCardValue('obras-andamento', {{ $status_obras->obras_andamento ?? 0 }});
        updateCardValue('total-projetos', {{ $total_projetos ?? 0 }});
        updateCardValue('projetos-parceria', {{ $projetos_parceria->total ?? 0 }});

        // Restaurar cores dos gráficos
        selectedMunicipio = null;

        // Restaurar gráficos de barras
        if (investimentosChart) {
            investimentosChart.data.datasets[0].backgroundColor = investimentosChart.data.labels.map(() => colors.selected);
            investimentosChart.data.datasets[0].borderColor = investimentosChart.data.labels.map(() => colors.selected);
            investimentosChart.update();
        }

        if (projetosChart) {
            projetosChart.data.datasets[0].backgroundColor = projetosChart.data.labels.map(() => colors.selected);
            projetosChart.data.datasets[0].borderColor = projetosChart.data.labels.map(() => colors.selected);
            projetosChart.update();
        }

        // Restaurar gráfico de parcerias
        if (parceriasChart) {
            const parceriaData = @json($projetos_parceria ?? (object)['com_parceria' => 0, 'sem_parceria' => 0]);
            parceriasChart.data.datasets[0].data = [parceriaData.sem_parceria || 0, parceriaData.com_parceria || 0];
            parceriasChart.update();
        }
    }

    // Calcular altura interna do gráfico baseada no número de municípios
    const minHeightPerItem = 35; // altura mínima por município
    const chartHeight = Math.max(300, investimentosData.length * minHeightPerItem);

    // Investment by Municipality Chart - Horizontal Bar Chart
    const investimentosCanvas = document.getElementById('investimentosMunicipiosChart');
    investimentosCanvas.width = investimentosCanvas.parentElement.clientWidth;
    investimentosCanvas.height = chartHeight;
    const investimentosCtx = investimentosCanvas.getContext('2d');

    investimentosChart = new Chart(investimentosCtx, {
        type: 'bar',
        data: {
            labels: investimentosData.map(item => item.municipio || 'N/A'),
            datasets: [{
                label: 'Investimento',
                data: investimentosData.map(item => item.valor_investimento || 0),
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            indexAxis: 'y',
            animation: {
                duration: 0
            },
            layout: {
                padding: {
                    right: 20
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'R$ ' + context.parsed.x.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                },
                datalabels: {
                    display: true,
                    anchor: 'end',
                    align: 'right',
                    formatter: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    },
                    font: {
                        size: 10,
                        weight: 'bold'
                    },
                    color: '#000'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    display: false
                },
                y: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        },
        plugins: [{
            id: 'datalabels',
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((element, index) => {
                        const data = dataset.data[index];
                        const x = element.x;
                        const y = element.y;

                        ctx.save();
                        ctx.textAlign = 'left';
                        ctx.textBaseline = 'middle';
                        ctx.font = 'bold 10px Arial';
                        ctx.fillStyle = '#000';
                        ctx.fillText('R$ ' + data.toLocaleString('pt-BR', {minimumFractionDigits: 2}), x + 5, y);
                        ctx.restore();
                    });
                });
            }
        }]
    });

    // Adicionar evento de clique no gráfico de investimentos
    investimentosChart.canvas.addEventListener('click', function(event) {
        const points = investimentosChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);

        if (points.length > 0) {
            const firstPoint = points[0];
            const selectedIndex = firstPoint.index;
            const selectedMunicipioName = investimentosChart.data.labels[selectedIndex];

            // Atualizar seleção
            selectedMunicipio = selectedMunicipioName;

            // Atualizar cores dos gráficos de barras
            updateChartColors(investimentosChart, selectedIndex);
            updateChartColors(projetosChart, selectedIndex);

            // Atualizar gráfico de parcerias com dados do município
            updatePartnershipChartForMunicipio(selectedMunicipioName);

            // Atualizar cards com dados do município selecionado
            updateCardsWithMunicipioData(selectedMunicipioName);
        }
    });

    // Projects by Municipality Chart - Horizontal Bar Chart
    const projetosCanvas = document.getElementById('projetosMunicipiosChart');
    projetosCanvas.width = projetosCanvas.parentElement.clientWidth;
    projetosCanvas.height = chartHeight;
    const projetosCtx = projetosCanvas.getContext('2d');

    projetosChart = new Chart(projetosCtx, {
        type: 'bar',
        data: {
            labels: projetosData.map(item => item.municipio || 'N/A'),
            datasets: [{
                label: 'Projetos',
                data: projetosData.map(item => item.total_projetos || 0),
                backgroundColor: '#28a745',
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            indexAxis: 'y',
            animation: {
                duration: 0
            },
            layout: {
                padding: {
                    right: 20
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    display: false
                },
                y: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        },
        plugins: [{
            id: 'datalabels',
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((element, index) => {
                        const data = dataset.data[index];
                        const x = element.x;
                        const y = element.y;

                        ctx.save();
                        ctx.textAlign = 'left';
                        ctx.textBaseline = 'middle';
                        ctx.font = 'bold 10px Arial';
                        ctx.fillStyle = '#000';
                        ctx.fillText(data.toString(), x + 5, y);
                        ctx.restore();
                    });
                });
            }
        }]
    });

    // Adicionar evento de clique no gráfico de projetos
    projetosChart.canvas.addEventListener('click', function(event) {
        const points = projetosChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);

        if (points.length > 0) {
            const firstPoint = points[0];
            const selectedIndex = firstPoint.index;
            const selectedMunicipioName = projetosChart.data.labels[selectedIndex];

            // Atualizar seleção
            selectedMunicipio = selectedMunicipioName;

            // Atualizar cores dos gráficos de barras
            updateChartColors(investimentosChart, selectedIndex);
            updateChartColors(projetosChart, selectedIndex);

            // Atualizar gráfico de parcerias com dados do município
            updatePartnershipChartForMunicipio(selectedMunicipioName);

            // Atualizar cards com dados do município selecionado
            updateCardsWithMunicipioData(selectedMunicipioName);
        }
    });

    // Partnership Chart - Pie Chart
    const parceriaCanvas = document.getElementById('projetosParceriaChart');
    parceriaCanvas.width = 300;
    parceriaCanvas.height = 250;
    const parceriaCtx = parceriaCanvas.getContext('2d');
    const parceriaData = @json($projetos_parceria ?? (object)['com_parceria' => 0, 'sem_parceria' => 0]);

    parceriasChart = new Chart(parceriaCtx, {
        type: 'pie',
        data: {
            labels: ['NÃO POSSUI PARCERIA', 'POSSUI PARCERIA'],
            datasets: [{
                data: [parceriaData.sem_parceria || 0, parceriaData.com_parceria || 0],
                backgroundColor: ['#343a40', '#007bff'],
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 10,
                        font: {
                            size: 10
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(2);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Adicionar evento de clique no gráfico de parcerias
    parceriasChart.canvas.addEventListener('click', function(event) {
        const points = parceriasChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);

        if (points.length > 0) {
            const firstPoint = points[0];
            const selectedIndex = firstPoint.index;

            // Determinar tipo de parceria baseado no índice
            let partnershipType = null;
            if (selectedIndex === 0) {
                partnershipType = 'sem_parceria'; // NÃO POSSUI PARCERIA
            } else if (selectedIndex === 1) {
                partnershipType = 'com_parceria'; // POSSUI PARCERIA
            }

            if (partnershipType) {
                // Atualizar seleção
                selectedPartnershipType = partnershipType;
                selectedMunicipio = null; // Limpar seleção de município

                // Filtrar municípios por tipo de parceria
                filterMunicipiosByPartnership(partnershipType);
            }
        }
    });
});
</script>
@endsection