@extends('layouts.app')

@section('title', 'Detalhes do Projeto - Mapa Obras V2')
@section('description', 'Lista detalhada de projetos de obras públicas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row bg-light p-3 mb-4">
        <div class="col-md-6">
            <h1 class="text-success">PAINEL DE OBRAS (FONTE GOMAP)</h1>
        </div>
        <div class="col-md-6 text-end">
            <small class="text-success">SEINFRA Secretaria de Estado da Infraestrutura</small><br>
            <small class="text-success">GOIÁS O ESTADO QUE DÁ CERTO</small>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            @include('components.sidebar-menu')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Content Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-success">DETALHES DO PROJETO</h2>
                <div>
                    <button class="btn btn-success me-2">
                        <i class="fas fa-sync-alt me-1"></i>EDITAR COLUNAS
                    </button>
                    <button class="btn btn-success me-2">
                        <i class="fas fa-filter me-1"></i>FILTROS
                    </button>
                    <button class="btn btn-success">
                        RESPONSÁVEIS - CONTRATO
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-2">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="card-title text-muted">TOTAL DE PROJETOS</h6>
                            <h4 class="text-success">{{ number_format(isset($estatisticas->total_projetos) ? $estatisticas->total_projetos : 0, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="card-title text-muted">VALOR TOTAL</h6>
                            <h4 class="text-success">R${{ number_format((isset($estatisticas->valor_total) ? $estatisticas->valor_total : 0) / 1000000000, 2, ',', '.') }} Bi</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="card-title text-muted">VALOR EMPENHADO</h6>
                            <h4 class="text-success">R${{ number_format((isset($estatisticas->valor_empenhado) ? $estatisticas->valor_empenhado : 0) / 1000000000, 2, ',', '.') }} Bi</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="card-title text-muted">VALOR LIQUIDADO</h6>
                            <h4 class="text-success">R${{ number_format((isset($estatisticas->valor_liquidado) ? $estatisticas->valor_liquidado : 0) / 1000000000, 2, ',', '.') }} Bi</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="card-title text-muted">VALOR PAGO</h6>
                            <h4 class="text-success">R${{ number_format((isset($estatisticas->valor_pago) ? $estatisticas->valor_pago : 0) / 1000000000, 2, ',', '.') }} Bi</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="card-title text-muted">SALDO A PAGAR</h6>
                            <h4 class="text-success">R${{ number_format((isset($estatisticas->saldo_pagar) ? $estatisticas->saldo_pagar : 0) / 1000000000, 2, ',', '.') }} Bi</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debug Section -->
            @if(isset($debug))
            <div class="alert alert-info mb-4">
                <h5>🔍 Debug - Diagnóstico</h5>
                <div class="row">
                    <div class="col-md-3">
                        <strong>Total Registros:</strong> {{ $debug['total_registros'] }}
                    </div>
                    <div class="col-md-3">
                        <strong>Projetos Retornados:</strong> {{ count($projetos) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Consulta Completa:</strong> {{ $debug['consulta_completa_count'] }}
                    </div>
                    <div class="col-md-3">
                        <strong>Erro:</strong> {{ $debug['erro_consulta'] ?? 'Nenhum' }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Projects Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID do Projeto</th>
                            <th>Nome do Projeto</th>
                            <th>Objeto</th>
                            <th>Situação</th>
                            <th>Município</th>
                            <th>Valor Total</th>
                            <th>Execução</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projetos as $projeto)
                        <tr>
                            <td>{{ $projeto->id_projeto ?? $projeto->id }}</td>
                            <td><strong>{{ $projeto->nome_projeto ?? 'Nome não informado' }}</strong></td>
                            <td>{{ Str::limit($projeto->objeto ?? 'Não informado', 50) }}</td>
                            <td>
                                <span class="badge bg-{{ $projeto->situacao_obra == 'C' ? 'success' : ($projeto->situacao_obra == 'A' ? 'primary' : ($projeto->situacao_obra == 'I' ? 'warning' : 'secondary')) }}">
                                    {{ $projeto->situacao_obra == 'P' ? 'Planejada' : ($projeto->situacao_obra == 'A' ? 'Em Andamento' : ($projeto->situacao_obra == 'I' ? 'Interrompida' : 'Concluída')) }}
                                </span>
                            </td>
                            <td>{{ $projeto->municipio ?? 'Não informado' }}</td>
                            <td>R$ {{ number_format($projeto->valor_total_do_projeto ?? 0, 2, ',', '.') }}</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $projeto->estagio_execucao_percentual ?? 0 }}%"
                                        aria-valuenow="{{ $projeto->estagio_execucao_percentual ?? 0 }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                        {{ $projeto->estagio_execucao_percentual ?? 0 }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('obras.show', $projeto->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-info-circle text-muted"></i>
                                Nenhum projeto encontrado
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    <nav aria-label="Navegação de página de projetos">
                        {{ $projetos->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection