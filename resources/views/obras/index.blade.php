@extends('layouts.app')

@section('title', 'Lista de Obras - Mapa Obras V2')
@section('description', 'Lista completa de todas as obras públicas')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="hero-section">
        <img src="/images/markers/logocgeatualizada2023.png" alt="Logo CGE" class="hero-logo img-fluid">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="display-4 fw-bold mb-4">Lista de Obras</h1>
                    <p class="lead mb-4">Visualize todas as obras públicas em uma lista organizada com filtros avançados.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filtros
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('obras.filter') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="nr_projeto" class="form-label">Número do Projeto</label>
                            <input type="number" class="form-control" id="nr_projeto" name="nr_projeto" placeholder="Ex: 123">
                        </div>
                        <div class="col-md-2">
                            <label for="situacao" class="form-label">Situação</label>
                            <select class="form-select" id="situacao" name="situacao">
                                <option value="">Todas as situações</option>
                                <option value="P">{{ handler_situation_work('P') }}</option>
                                <option value="A">{{ handler_situation_work('A') }}</option>
                                <option value="I">{{ handler_situation_work('I') }}</option>
                                <option value="C">{{ handler_situation_work('C') }}</option>
                                <option value="D">{{ handler_situation_work('D') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="municipio" class="form-label">Município</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" placeholder="Digite o município">
                        </div>
                        <div class="col-md-3">
                            <label for="area_tematica" class="form-label">Área Temática</label>
                            <input type="text" class="form-control" id="area_tematica" name="area_tematica" placeholder="Digite a área temática">
                        </div>
                        <div class="col-md-3">
                            <label for="nome_obra" class="form-label">Nome da Obra</label>
                            <input type="text" class="form-control" id="nome_obra" name="nome_obra" placeholder="Digite o nome da obra">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Filtrar
                            </button>
                            <a href="{{ route('obras.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Limpar
                            </a>
                            <a href="{{ route('obras.export') }}" class="btn btn-success">
                                <i class="fas fa-download me-2"></i>Exportar JSON
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Resultados ({{ count($obras ?? []) }} obras encontradas)
                </h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleView('table')">
                        <i class="fas fa-table"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleView('cards')">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if(isset($erro))
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $erro }}
                    </div>
                @elseif(empty($obras))
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhuma obra encontrada</h5>
                        <p class="text-muted">Tente ajustar os filtros para encontrar obras.</p>
                    </div>
                @else
                    <!-- Table View -->
                    <div id="tableView">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
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
                                    @foreach($obras as $obra)
                                        <tr>
                                            <td>{{ $obra->id }}</td>
                                            <td>
                                                <strong>{{ $obra->nome_projeto ?? 'Nome não informado' }}</strong>
                                            </td>
                                            <td>{{ Str::limit($obra->objeto ?? 'Não informado', 50) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $obra->situacao_obra == 'C' ? 'success' : ($obra->situacao_obra == 'A' ? 'primary' : ($obra->situacao_obra == 'I' ? 'warning' : 'secondary')) }}">
                                                    {{ $obra->situacao_obra == 'P' ? 'Planejada' : ($obra->situacao_obra == 'A' ? 'Em Andamento' : ($obra->situacao_obra == 'I' ? 'Interrompida' : 'Concluída')) }}
                                                </span>
                                            </td>
                                            <td>{{ $obra->municipio ?? 'Não informado' }}</td>
                                            <td>R$ {{ number_format($obra->valor_total_do_projeto ?? 0, 2, ',', '.') }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $obra->estagio_execucao_percentual ?? 0 }}%"
                                                         aria-valuenow="{{ $obra->estagio_execucao_percentual ?? 0 }}"
                                                         aria-valuemin="0" aria-valuemax="100">
                                                        {{ $obra->estagio_execucao_percentual ?? 0 }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('obras.show', $obra->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Cards View -->
                    <div id="cardsView" style="display: none;">
                        <div class="row">
                            @foreach($obras as $obra)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card card-hover h-100">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <small class="text-muted">ID: {{ $obra->id }}</small>
                                            <span class="badge bg-{{ $obra->situacao_obra == 'C' ? 'success' : ($obra->situacao_obra == 'A' ? 'primary' : ($obra->situacao_obra == 'I' ? 'warning' : 'secondary')) }}">
                                                {{ $obra->situacao_obra == 'P' ? 'Planejada' : ($obra->situacao_obra == 'A' ? 'Em Andamento' : ($obra->situacao_obra == 'I' ? 'Interrompida' : 'Concluída')) }}
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $obra->nome_projeto ?? 'Nome não informado' }}</h6>
                                            <p class="card-text">{{ Str::limit($obra->objeto ?? 'Não informado', 100) }}</p>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $obra->municipio ?? 'Não informado' }}
                                                </small>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-dollar-sign me-1"></i>R$ {{ number_format($obra->valor_total_do_projeto ?? 0, 2, ',', '.') }}
                                                </small>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted">Execução:</small>
                                                <div class="progress" style="height: 15px;">
                                                    <div class="progress-bar" role="progressbar"
                                                         style="width: {{ $obra->estagio_execucao_percentual ?? 0 }}%"
                                                         aria-valuenow="{{ $obra->estagio_execucao_percentual ?? 0 }}"
                                                         aria-valuemin="0" aria-valuemax="100">
                                                        {{ $obra->estagio_execucao_percentual ?? 0 }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('obras.show', $obra->id) }}" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-eye me-1"></i>Ver Detalhes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleView(view) {
        if (view === 'table') {
            document.getElementById('tableView').style.display = 'block';
            document.getElementById('cardsView').style.display = 'none';
        } else {
            document.getElementById('tableView').style.display = 'none';
            document.getElementById('cardsView').style.display = 'block';
        }
    }
</script>
@endsection

