@extends('layouts.app')

@section('title', 'Detalhes da Obra - Mapa Obras V2')
@section('description', 'Informações detalhadas sobre a obra')

@section('content')
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

.d-flex.flex-column.container-fluid, .container-fluid {
    overflow: visible;
}

.por-title {
    font-weight: bold;
    margin-bottom: 10px;
    color: #2c3e50;
}

.por-txt {
    color: #666;
    font-size: 14px;
}

.desc {
    line-height: 1.5;
}
</style>

<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section">
        <img src="/images/markers/logocgeatualizada2023.png" alt="Logo CGE" class="hero-logo img-fluid">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="display-4 fw-bold mb-4">Detalhes da Obra</h1>
                    <p class="lead mb-4">{{ $obra->nome_projeto ?? 'Nome não informado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center shadow-lg p-3 my-3 mx-1 bg-white rounded">
        <div class="col-12 col-sm-5 offset-sm-1 my-2">
            <i class="fa fa-list" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Número do projeto</h4>
            <div id="proj_orgao" class="desc text-justify por-txt">{{ $obra->id_projeto ?? 'Não informado' }}</div>
        </div>
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-list" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Nome do projeto</h4>
            <div id="proj_orgao" class="desc text-justify por-txt">
                {{ $obra->nome_projeto ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 offset-sm-1 my-2">
            <i class="fa fa-sitemap" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Órgão</h4>
            <div id="proj_orgao" class="desc text-justify por-txt">
                {{ $obra->orgao ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-thumb-tack" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Tipo</h4>
            <div id="proj_tipo" class="desc text-justify por-txt">
                {{ $obra->tipos_do_projeto ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 offset-sm-1 my-2">
            <i class="fa fa-info-circle" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Área temática</h4>
            <div id="proj_dt_fim" class="por-txt p-0">
                {{ $obra->area_tematica ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-clock-o" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Data de Início</h4>
            <div id="proj_dt_inicio" class="por-txt p-0">
                {{ $obra->data_de_inicio_ou_previsao ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 offset-sm-1 my-2">
            <i class="fa fa-calendar-o" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Prazo Estimado</h4>
            <div id="proj_dt_fim" class="por-txt p-0">
                {{ $obra->data_prevista_conclusao ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-bar-chart-o" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Situação</h4>
            <div id="proj_situacao" class="por-txt p-0">
                {{ handler_situation_work($obra->situacao_obra) ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 offset-sm-1 my-2">
            <i class="fa fa-money" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Valor Planejado</h4>
            <div id="proj_vl_planejado" class="por-txt p-0">
                @if (($obra->valor_total_do_projeto ?? 0) == 0)
                    Não informado
                @else
                    R$ {{ number_format($obra->valor_total_do_projeto, 2, ',', '.') }}
                @endif
            </div>
        </div>
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-money" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Valor Empenhado</h4>
            <div id="proj_vl_planejado" class="por-txt p-0">
                R$ {{ number_format($obra->valorEmpenhado ?? 0, 2, ',', '.') }}
            </div>
        </div>
        <div class="col-12 col-sm-5 offset-sm-1 my-2">
            <i class="fa fa-money" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Valor Liquidado</h4>
            <div id="proj_vl_planejado" class="por-txt p-0">
                R$ {{ number_format($obra->valorLiquidado ?? 0, 2, ',', '.') }}
            </div>
        </div>
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-money" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Valor Pago</h4>
            <div id="proj_vl_planejado" class="por-txt p-0">
                R$ {{ number_format($obra->valorExecutado ?? 0, 2, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="row shadow-lg p-3 my-3 mx-1 bg-white rounded justify-content-center">
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-line-chart" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Objetivo</h4>
            <div id="proj_objetivo" class="desc text-justify">
                {{ $obra->objeto ?? 'Não informado' }}
            </div>
        </div>
        <div class="col-12 col-sm-5 my-2">
            <i class="fa fa-percent" aria-hidden="true"
                style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
            <h4 class="por-title">Percentual de conclusão</h4>
            <div class="progress" style="width: 90%">
                <div class="progress-bar" role="progressbar"
                    style="width: {{ $obra->estagio_execucao_percentual ?? 0 }}%; background: #2A9E0D;"
                    aria-valuenow="{{ $obra->estagio_execucao_percentual ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                    {{ $obra->estagio_execucao_percentual ?? 0 }}%
                </div>
            </div>
        </div>
    </div>

    @if (in_array($obra->id_projeto ?? 0, [5298, 237, 1336, 1348]))
    <div class="row shadow-lg p-3 my-3 mx-1 bg-white rounded justify-content-center">
        <div class="col-8 my-2" style="max-width:1024px">
            <h4 class="por-title">Galeria de fotos</h4>

            @if (($obra->id_projeto ?? 0) == 5298)
            <div id="carouselExampleFade" class="carousel slide carousel-fade col-6" data-bs-ride="carousel"
                style="margin:0 auto">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-230.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-230-2.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-230-3.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-230-4.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-230-5.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-230-6.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-230-7.jpg">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @endif

            @if (($obra->id_projeto ?? 0) == 237)
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-040.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-040-1.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-040-2.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-040-3.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-040-4.jpeg">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @endif

            @if (($obra->id_projeto ?? 0) == 1336)
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-221.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-221-3.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-221-4.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-221-5.jpeg">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @endif

            @if (($obra->id_projeto ?? 0) == 1348)
            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-080.JPG">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-080-3.JPG">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-080-4.JPG">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-080.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-080-1.jpeg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-080-2.jpg">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="/assets/landing/img/obras/GO-080-5.JPG">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Seção do Diário Oficial -->
    @if (!empty($diarioOficial))
        <div class="row shadow-lg p-3 my-3 mx-1 bg-white rounded justify-content-center">
            <div class="col-12 col-sm-2 offset-sm-1 my-2">
                <h3>Diário oficial</h3>
                <hr class="w-100 w-md-50">
            </div>
            <div class="col-12 col-sm-8"></div>
            @foreach ($diarioOficial as $item)
                <div class="col-12 col-sm-5 offset-sm-1 my-2">
                    <div class="my-3">
                        <i class="fa fa-calendar-o" aria-hidden="true"
                            style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
                        <h4 class="por-title">Data</h4>
                        <div id="proj_dt_fim" class="por-txt p-0">
                            {{ $item->data != 'null' ? date('d/m/Y', strtotime($item->data)) : 'Não informado' }}
                        </div>
                    </div>
                    <div class="my-3">
                        <i class="fa fa-info-circle" aria-hidden="true"
                            style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
                        <h4 class="por-title">Numero</h4>
                        <div id="proj_dt_fim" class="por-txt p-0">
                            {{ $item->numero != 'null' ? $item->numero : 'Não informado' }}</div>
                    </div>
                    <div class="my-3">
                        <i class="fa fa-file-text" aria-hidden="true"
                            style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
                        <h4 class="por-title">Página</h4>
                        <div id="proj_dt_fim" class="por-txt p-0">
                            {{ $item->pagina != 'null' ? $item->pagina : 'Não informado' }}</div>
                    </div>
                    <div class="my-3">
                        <i class="fa fa-thumb-tack" aria-hidden="true"
                            style="color: #28a745; float: left; margin-right: 10px; font-size: 2rem;"></i>
                        <h4 class="por-title">Tipo do documento</h4>
                        <div id="proj_dt_fim" class="por-txt p-0">
                            {{ $item->nome_tipo != 'null' ? $item->nome_tipo : 'Não informado' }}</div>
                    </div>
                </div>
                <div class="col-12 col-sm-5   my-2">
                    <h5>Texto do Diário Oficial</h5>
                    <p>{{ $item->texto_publicacao != 'null' ? $item->texto_publicacao : 'Não informado' }}</p>
                </div>
                <div class="col-12">
                    <hr>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.carousel').carousel()
    })
</script>
@endsection
