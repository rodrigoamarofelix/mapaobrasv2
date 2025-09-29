@extends('layouts.app')

@section('title', 'Business Intelligence - Mapa Obras V2')
@section('description', 'Dashboards e análises de dados das obras')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="hero-section">
        <img src="/images/markers/logocgeatualizada2023.png" alt="Logo CGE" class="hero-logo img-fluid">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="display-4 fw-bold mb-4">Business Intelligence</h1>
                    <p class="lead mb-4">Dashboards e análises avançadas dos dados das obras públicas.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Power BI Section -->
    <section class="section" id="section-shot">
        <div class="container">
            <iframe style="width: 100%; height: 100vh;"
                src="https://app.powerbi.com/view?r=eyJrIjoiMjAzOTYwMzEtZWVhOC00MTYxLWE2ZjMtMWZkNTYxZDE1OGJlIiwidCI6IjY3ZmQ0MzFjLWIyYWQtNDg2Ny04MWJjLWQ3NTYyMjBiNTZkNCJ9">
            </iframe>
            <div style="margin-top: 12px; margin-bottom: 12px;display: flex; justify-content: center;">
                <div class="links"
                    style="display: flex; flex-wrap: wrap; gap: 10px; max-width: 600px; justify-content: space-between; width: 100%;">

                    <h4 style="font-size: 1em; width: 100%; text-align: center;">
                        Veja informações adicionais relacionadas a obras
                    </h4>

                    <a href="https://www.transparencia.go.gov.br/wp-content/uploads/sites/2/painel/lai.php?painel=contratos_fiscais_e_gestores&orgao="
                        target="_blank"
                        style="flex: 1 1 45%; background-color: #219d21; color: white; text-align: center; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 15px; cursor: pointer;">
                        Gestores/fiscais e contatos
                    </a>

                    <a href="https://goias.gov.br/meioambiente/obras-estudo-de-impacto-ambiental-eia-e-relatorio-de-impacto-ambiental-rima-e-ou-estudo-de-impacto-de-vizinhanca-eiv-2/" target="_blank"
                        style="flex: 1 1 45%; background-color: #219d21; color: white; text-align: center; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 15px; cursor: pointer;">
                        Estudos e relatórios de impacto ambiental das obras
                    </a>

                    <a href="https://portal.meioambiente.go.gov.br/transparencia-web/informacoes" target="_blank"
                        style="flex: 1 1 45%; background-color: #219d21; color: white; text-align: center; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 15px; cursor: pointer;">
                        Licenças ambientais
                    </a>

                    <a href="https://transparencia.go.gov.br/audiencias-e-consultas-publicas-obras-estaduais/"
                        target="_blank"
                        style="flex: 1 1 45%; background-color: #219d21; color: white; text-align: center; text-decoration: none; padding: 12px; border-radius: 8px; font-size: 15px; cursor: pointer;">
                        Audiências e consultas públicas
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endsection