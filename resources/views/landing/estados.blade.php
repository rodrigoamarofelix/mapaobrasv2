@extends('layouts.app')

@section('title', 'Saiba Mais - Mapa Obras V2')
@section('description', 'Informações sobre o sistema Mapa Obras V2')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section">
        <img src="/images/markers/logocgeatualizada2023.png" alt="Logo CGE" class="hero-logo img-fluid">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="display-4 fw-bold mb-4">Saiba Mais</h1>
                    <p class="lead mb-4">Conheça mais sobre o sistema Mapa Obras V2 e suas funcionalidades.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Sobre o Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="lead">O Mapa Obras V2 é um sistema de gestão e mapeamento de obras públicas desenvolvido para proporcionar transparência e controle sobre os projetos em execução.</p>

                        <h6 class="mt-4">Principais Funcionalidades:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Visualização interativa de obras em mapa</li>
                            <li><i class="fas fa-check text-success me-2"></i>Lista detalhada de todas as obras</li>
                            <li><i class="fas fa-check text-success me-2"></i>Filtros avançados por situação, município e área temática</li>
                            <li><i class="fas fa-check text-success me-2"></i>Acompanhamento do progresso de execução</li>
                            <li><i class="fas fa-check text-success me-2"></i>Informações detalhadas sobre cada obra</li>
                            <li><i class="fas fa-check text-success me-2"></i>Exportação de dados em formato JSON</li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Como Funciona
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>1. Visualização no Mapa</h6>
                                <p>As obras são exibidas em um mapa interativo onde você pode:</p>
                                <ul>
                                    <li>Navegar pelo mapa</li>
                                    <li>Filtrar por diferentes critérios</li>
                                    <li>Visualizar detalhes ao clicar nos marcadores</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>2. Lista de Obras</h6>
                                <p>Visualize todas as obras em formato de lista com:</p>
                                <ul>
                                    <li>Informações resumidas</li>
                                    <li>Filtros personalizados</li>
                                    <li>Visualização em tabela ou cards</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Estatísticas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h3 class="text-primary">{{ count($obrasDestaque ?? []) }}</h3>
                            <small class="text-muted">Estados com Obras</small>
                        </div>
                        <div class="text-center mb-3">
                            <h3 class="text-success">{{ array_sum(array_column($obrasDestaque ?? [], 'total_obras')) }}</h3>
                            <small class="text-muted">Total de Obras</small>
                        </div>
                        <div class="text-center">
                            <h3 class="text-info">R$ {{ number_format(array_sum(array_column($obrasDestaque ?? [], 'valor_total')), 2, ',', '.') }}</h3>
                            <small class="text-muted">Valor Total Investido</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-link me-2"></i>Links Úteis
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('landing.obras-maps') }}" class="btn btn-primary">
                                <i class="fas fa-map me-1"></i>Ver Mapa de Obras
                            </a>
                            <a href="{{ route('obras.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list me-1"></i>Lista de Obras
                            </a>
                            <a href="{{ route('landing.bi') }}" class="btn btn-info">
                                <i class="fas fa-chart-line me-1"></i>Business Intelligence
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

