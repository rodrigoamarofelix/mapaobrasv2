@extends('layouts.app')

@section('title', 'Mapa de Obras')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="hero-section">
        <img src="/images/markers/logocgeatualizada2023.png" alt="Logo CGE" class="hero-logo img-fluid">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <h1 class="display-4 fw-bold mb-4">Mapa de Obras</h1>
                    <p class="lead mb-4">Visualize todas as obras públicas em um mapa interativo com filtros avançados.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
        <div class="card">
            <div class="card-body">
                        <form id="filterForm" action="{{ route('obras.filter') }}" method="POST">
                            @csrf
                    <div class="row g-3">
                                <div class="col-md-2">
                                    <label for="nr_projeto" class="form-label">Número do Projeto</label>
                                    <input type="text" class="form-control" id="nr_projeto" name="nr_projeto">
                                </div>

                                <div class="col-md-2">
                            <label for="situacao_obra" class="form-label">Situação da Obra</label>
                                    <select class="form-control" id="situacao_obra" name="situacao_obra[]" multiple>
                                @foreach($situacoes ?? [] as $situacao)
                                            <option value="{{ $situacao->situacao_obra }}">{{ $situacao->descricao ?? handler_situation_work($situacao->situacao_obra) }}</option>
                                @endforeach
                            </select>
                        </div>

                                <div class="col-md-2">
                            <label for="municipio" class="form-label">Município</label>
                                    <select class="form-control" id="municipio" name="municipio[]" multiple>
                                @foreach($municipios ?? [] as $municipio)
                                            <option value="{{ $municipio->nome ?? $municipio }}">{{ $municipio->nome ?? $municipio }}</option>
                                @endforeach
                            </select>
                        </div>

                                <div class="col-md-2">
                            <label for="area_tematica" class="form-label">Área Temática</label>
                                    <select class="form-control" id="area_tematica" name="area_tematica[]" multiple>
                                @foreach($areaTematica ?? [] as $area)
                                            <option value="{{ $area->area_tematica ?? $area }}">{{ $area->area_tematica ?? $area }}</option>
                                @endforeach
                            </select>
                        </div>

                                <div class="col-md-2">
                            <label for="nm_obra" class="form-label">Nome da Obra</label>
                                    <select class="form-control" id="nm_obra" name="nm_obra[]" multiple>
                                        @foreach($nome_obras ?? [] as $nome)
                                            <option value="{{ $nome->nome_projeto ?? $nome }}">{{ $nome->nome_projeto ?? $nome }}</option>
                                @endforeach
                            </select>
                        </div>

                                <div class="col-md-2">
                            <label for="orgao" class="form-label">Órgão</label>
                                    <select class="form-control" id="orgao" name="orgao[]" multiple>
                                @foreach($orgao ?? [] as $orgaoItem)
                                            <option value="{{ $orgaoItem->sigla ?? $orgaoItem }}">{{ $orgaoItem->sigla ?? $orgaoItem }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                            <!-- Botões de ação -->
                    <div class="row mt-3">
                        <div class="col-12">
                                    <button type="button" class="btn btn-primary me-2" id="filterButton" onclick="filtrarObras()">Filtrar</button>
                                    <button type="button" class="btn btn-secondary" onclick="limparFiltros()">Limpar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div id="map" style="height: 600px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Variáveis globais
    let map;
    let markers = [];
    let legendAdded = false;

    // Dados das obras
    const obras = @json($locations ?? []);

    // Função para inicializar o mapa
    function initMap() {
        // Coordenadas do estado de Goiás
        const defaultLat = -16.6869;
        const defaultLng = -49.2648;

        map = L.map('map').setView([defaultLat, defaultLng], 8);
        map.setMinZoom(2);

        // Adicionar camada de tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // FORÇAR coordenadas de Goiás sempre que o mapa for movido
        map.on('moveend', function() {
            const center = map.getCenter();
            console.log('Mapa movido para:', center.lat, center.lng);

            // Se o mapa sair muito de Goiás, forçar de volta
            if (center.lat < -20 || center.lat > -10 || center.lng < -55 || center.lng > -40) {
                console.log('Mapa saiu de Goiás, forçando de volta...');
                map.setView([-16.6869, -49.2648], 8);
            }
        });
    }

    // Função para obter o ícone personalizado baseado na área temática
    function getCustomIcon(areaTematica) {
        let iconPath = '/images/markers/default.png'; // Marcador padrão

        console.log('=== DEBUG getCustomIcon ===');
        console.log('Área temática recebida:', areaTematica);

        // Mapear por área temática - VERSÃO CORRIGIDA
        if (areaTematica) {
            const area = areaTematica.toLowerCase();
            console.log('Área em lowercase:', area);

            // Mapeamento correto com marcadores específicos
            if (area === 'habitação') {
                iconPath = '/images/markers/habitacao.png';
                console.log('Usando marcador: HABITAÇÃO');
            } else if (area === 'esporte e lazer') {
                iconPath = '/images/markers/esporte.png';
                console.log('Usando marcador: ESPORTE');
            } else if (area === 'saúde') {
                iconPath = '/images/markers/saude.png';
                console.log('Usando marcador: SAÚDE');
            } else if (area === 'segurança pública') {
                iconPath = '/images/markers/seguranca.png';
                console.log('Usando marcador: SEGURANÇA');
            } else if (area === 'educação') {
                iconPath = '/images/markers/educacao.png';
                console.log('Usando marcador: EDUCAÇÃO');
            } else if (area === 'infraestrutura e transportes') {
                iconPath = '/images/markers/rodovia.png';
                console.log('Usando marcador: RODOVIA (Infraestrutura)');
            } else {
                iconPath = '/images/markers/default.png';
                console.log('Usando marcador: DEFAULT para:', area);
            }
        } else {
            console.log('Sem área temática, usando DEFAULT');
        }

        console.log('Caminho final do ícone:', iconPath);
        console.log('=== FIM DEBUG getCustomIcon ===');

        return L.icon({
            iconUrl: iconPath,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });
    }

    // Função para adicionar marcadores ao mapa
    function addMarkersToMap() {
        console.log('=== INICIANDO addMarkersToMap ===');
        console.log('Número de obras:', obras.length);
        console.log('Tipo de obras:', typeof obras);

        obras.forEach((obra, index) => {
            try {
                // Verificar se a obra tem latitude e longitude válidas
                if (!obra.latitude || !obra.longitude ||
                    obra.latitude === '0.00000000' || obra.longitude === '0.00000000') {
                    return;
                }

                const lat = parseFloat(obra.latitude);
                const lng = parseFloat(obra.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    return;
                }

                // Obter ícone personalizado
                console.log(`Obra ${index} - Área temática:`, obra.area_tematica);
                const customIcon = getCustomIcon(obra.area_tematica);

                const marker = L.marker([lat, lng], { icon: customIcon })
                    .bindPopup(`
                        <div class="popup-content">
                            <h6 class="fw-bold">${obra.nome_projeto || 'Nome não informado'}</h6>
                            <p class="mb-2"><strong>Objeto:</strong> ${obra.objeto || 'Não informado'}</p>
                            <p class="mb-2"><strong>Situação:</strong> ${handlerSituationWork(obra.situacao_obra) || 'Não informado'}</p>
                            <p class="mb-2"><strong>Município:</strong> ${obra.municipio || 'Não informado'}</p>
                            <p class="mb-2"><strong>Valor Total:</strong> R$ ${formatarValor(obra.valor_total_do_projeto)}</p>
                            <p class="mb-2"><strong>Execução:</strong> ${obra.estagio_execucao_percentual || 0}%</p>
                            <div class="d-grid gap-2">
                                <a href="/detalhe-mapas/${obra.id}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-info-circle me-1"></i>Ver Detalhes
                                </a>
                            </div>
                        </div>
                    `);

                markers.push(marker);
                marker.addTo(map);

            } catch (error) {
                console.error(`Erro ao processar obra ${index}:`, error);
                console.error('Obra que causou erro:', obra);
            }
        });

        console.log('Marcadores criados:', markers.length);
        console.log('=== FINALIZANDO addMarkersToMap ===');

        // Ajustar zoom para mostrar todos os marcadores (limitado ao estado de Goiás)
        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            const bounds = group.getBounds();

            // Verificar se há marcadores válidos antes de ajustar o zoom
            if (bounds.isValid()) {
                map.fitBounds(bounds.pad(0.1));
            } else {
                // Se não há marcadores válidos, manter o zoom padrão do estado de Goiás
                map.setView([-16.6869, -49.2648], 8);
            }
        }

        // Adicionar legenda dos marcadores apenas uma vez
        if (!legendAdded) {
            addMarkerLegend();
            legendAdded = true;
        }
    }

    // Função para adicionar legenda dos marcadores
    function addMarkerLegend() {
        // Verificar se já existe uma legenda
        const existingLegend = document.querySelector('.marker-legend');
        if (existingLegend) {
            console.log('Legenda já existe, não criando nova');
            return;
        }

        console.log('Criando nova legenda...');
        const legend = L.control({position: 'bottomright'});

        legend.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'marker-legend');
            div.style.backgroundColor = 'white';
            div.style.padding = '10px';
            div.style.borderRadius = '5px';
            div.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
            div.style.fontSize = '12px';
            div.style.maxWidth = '200px';

            div.innerHTML = `
                <h6 style="margin: 0 0 10px 0; font-weight: bold;">Legenda dos Marcadores</h6>
                <div style="margin-bottom: 8px;">
                    <strong>Áreas Temáticas:</strong>
                </div>
                <div class="legend-item" style="margin-bottom: 5px; cursor: pointer; padding: 2px; border-radius: 3px;" onclick="filtrarPorAreaTematica('habitação')" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
                    <img src="/images/markers/habitacao.png" style="width: 20px; height: 20px; margin-right: 5px; vertical-align: middle;">
                    <span>Habitação</span>
                </div>
                <div class="legend-item" style="margin-bottom: 5px; cursor: pointer; padding: 2px; border-radius: 3px;" onclick="filtrarPorAreaTematica('esporte e lazer')" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
                    <img src="/images/markers/esporte.png" style="width: 20px; height: 20px; margin-right: 5px; vertical-align: middle;">
                    <span>Esporte</span>
                </div>
                <div class="legend-item" style="margin-bottom: 5px; cursor: pointer; padding: 2px; border-radius: 3px;" onclick="filtrarPorAreaTematica('saúde')" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
                    <img src="/images/markers/saude.png" style="width: 20px; height: 20px; margin-right: 5px; vertical-align: middle;">
                    <span>Saúde</span>
                </div>
                <div class="legend-item" style="margin-bottom: 5px; cursor: pointer; padding: 2px; border-radius: 3px;" onclick="filtrarPorAreaTematica('segurança pública')" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
                    <img src="/images/markers/seguranca.png" style="width: 20px; height: 20px; margin-right: 5px; vertical-align: middle;">
                    <span>Segurança</span>
                </div>
                <div class="legend-item" style="margin-bottom: 5px; cursor: pointer; padding: 2px; border-radius: 3px;" onclick="filtrarPorAreaTematica('educação')" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
                    <img src="/images/markers/educacao.png" style="width: 20px; height: 20px; margin-right: 5px; vertical-align: middle;">
                    <span>Educação</span>
                </div>
                <div class="legend-item" style="margin-bottom: 5px; cursor: pointer; padding: 2px; border-radius: 3px;" onclick="filtrarPorAreaTematica('infraestrutura e transportes')" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
                    <img src="/images/markers/rodovia.png" style="width: 20px; height: 20px; margin-right: 5px; vertical-align: middle;">
                    <span>Infraestrutura</span>
                </div>
                <div class="legend-item" style="margin-bottom: 5px; cursor: pointer; padding: 2px; border-radius: 3px;" onclick="limparFiltros()" onmouseover="this.style.backgroundColor='#f0f0f0'" onmouseout="this.style.backgroundColor='transparent'">
                    <img src="/images/markers/default.png" style="width: 20px; height: 20px; margin-right: 5px; vertical-align: middle;">
                    <span>Outros</span>
                </div>
            `;

            return div;
        };

        legend.addTo(map);

        // Adicionar botão para centralizar em Goiás (apenas uma vez)
        const existingButton = document.querySelector('.center-button');
        if (!existingButton) {
            const centerButton = L.control({position: 'topleft'});
            centerButton.onAdd = function (map) {
                const div = L.DomUtil.create('div', 'center-button');
                div.style.backgroundColor = 'white';
                div.style.padding = '5px';
                div.style.borderRadius = '3px';
                div.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
                div.style.cursor = 'pointer';
                div.innerHTML = '📍 Goiás';
                div.onclick = function() {
                    map.setView([-16.6869, -49.2648], 8);
                };
                return div;
            };
            centerButton.addTo(map);
        }
    }

    // Função para filtrar obras
    async function filtrarObras() {
        console.log('=== INICIANDO FILTRO ===');
        console.log('Função filtrarObras chamada');

        // Construir parâmetros da URL manualmente
        const params = new URLSearchParams();

        // Processar cada campo do formulário
        const nrProjeto = $('#nr_projeto').val();
        if (nrProjeto && nrProjeto.trim() !== '') {
            params.append('nr_projeto', nrProjeto);
        }

        const situacaoObra = $('#situacao_obra').val();
        if (situacaoObra && situacaoObra.length > 0) {
            situacaoObra.forEach(situacao => {
                params.append('situacao_obra[]', situacao);
            });
        }

        const municipio = $('#municipio').val();
        if (municipio && municipio.length > 0) {
            municipio.forEach(mun => {
                params.append('municipio[]', mun);
            });
        }

        const areaTematica = $('#area_tematica').val();
        console.log('Valor de areaTematica capturado:', areaTematica);
        console.log('Tipo do valor:', typeof areaTematica);
        console.log('É array?', Array.isArray(areaTematica));
        if (areaTematica && areaTematica.length > 0) {
            console.log('Adicionando área temática aos parâmetros:', areaTematica);
            areaTematica.forEach(area => {
                console.log('Adicionando área:', area);
                params.append('area_tematica[]', area);
            });
        } else {
            console.log('Nenhuma área temática selecionada');
        }

        const nmObra = $('#nm_obra').val();
        if (nmObra && nmObra.length > 0) {
            nmObra.forEach(nome => {
                params.append('nm_obra[]', nome);
            });
        }

        const orgao = $('#orgao').val();
        if (orgao && orgao.length > 0) {
            orgao.forEach(org => {
                params.append('orgao[]', org);
            });
        }

        console.log('Parâmetros enviados:', params.toString());

        try {
            const response = await fetch('{{ route("api.filter") }}?' + params.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const filteredObras = await response.json();
            console.log('Obras filtradas recebidas:', filteredObras.length);

            // Atualizar marcadores no mapa
            updateMarkersOnly(filteredObras);

        } catch (error) {
            console.error('Erro ao filtrar obras:', error);
            alert('Erro ao filtrar obras. Verifique o console para mais detalhes.');
        }
    }

    // Função para atualizar apenas os marcadores
    function updateMarkersOnly(filteredObras) {
        console.log('=== ATUALIZANDO MARCADORES ===');

        // Limpar marcadores existentes
        markers.forEach(marker => {
            map.removeLayer(marker);
        });
        markers = [];

        // Adicionar novos marcadores
        filteredObras.forEach((obra, index) => {
            try {
                if (!obra.latitude || !obra.longitude ||
                    obra.latitude === '0.00000000' || obra.longitude === '0.00000000') {
                    return;
                }

                const lat = parseFloat(obra.latitude);
                const lng = parseFloat(obra.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    return;
                }

                const customIcon = getCustomIcon(obra.area_tematica);

                const marker = L.marker([lat, lng], { icon: customIcon })
                    .bindPopup(`
                        <div class="popup-content">
                            <h6 class="fw-bold">${obra.nome_projeto || 'Nome não informado'}</h6>
                            <p class="mb-2"><strong>Objeto:</strong> ${obra.objeto || 'Não informado'}</p>
                            <p class="mb-2"><strong>Situação:</strong> ${handlerSituationWork(obra.situacao_obra) || 'Não informado'}</p>
                            <p class="mb-2"><strong>Município:</strong> ${obra.municipio || 'Não informado'}</p>
                            <p class="mb-2"><strong>Valor Total:</strong> R$ ${formatarValor(obra.valor_total_do_projeto)}</p>
                            <p class="mb-2"><strong>Execução:</strong> ${obra.estagio_execucao_percentual || 0}%</p>
                            <div class="d-grid gap-2">
                                <a href="/detalhe-mapas/${obra.id}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-info-circle me-1"></i>Ver Detalhes
                                </a>
                            </div>
                        </div>
                    `);

                markers.push(marker);
                marker.addTo(map);

            } catch (error) {
                console.error(`Erro ao processar obra ${index}:`, error);
            }
        });

        console.log('=== MARCADORES ATUALIZADOS ===');
    }

    // Função para limpar filtros
    function limparFiltros() {
        window.location.reload();
    }

    // Função para filtrar por área temática específica (escopo global)
    window.filtrarPorAreaTematica = function(areaTematica) {
        console.log('=== FILTRANDO POR ÁREA TEMÁTICA ===', areaTematica);

        try {
            // Primeiro, vamos ver quais opções estão disponíveis no campo
            console.log('Opções disponíveis no campo area_tematica:');
            $('#area_tematica option').each(function() {
                console.log('Opção:', $(this).val(), '- Texto:', $(this).text());
            });

            // Limpar apenas outros filtros, mas manter área temática
            $('#nr_projeto').val('');
            $('#situacao_obra').val(null).trigger('change');
            $('#municipio').val(null).trigger('change');
            $('#nm_obra').val(null).trigger('change');
            $('#orgao').val(null).trigger('change');

            // Aguardar um pouco para o Select2 processar
            setTimeout(function() {
                // Tentar encontrar a opção correta
                let opcaoEncontrada = null;
                $('#area_tematica option').each(function() {
                    const valor = $(this).val().toLowerCase();
                    const texto = $(this).text().toLowerCase();
                    console.log('Comparando:', valor, 'com', areaTematica.toLowerCase());
                    console.log('Comparando texto:', texto, 'com', areaTematica.toLowerCase());

                    if (valor === areaTematica.toLowerCase() || texto.includes(areaTematica.toLowerCase())) {
                        opcaoEncontrada = $(this).val();
                        console.log('Opção encontrada:', opcaoEncontrada);
                    }
                });

                if (opcaoEncontrada) {
                    // Obter valores atuais do campo
                    const valoresAtuais = $('#area_tematica').val() || [];
                    console.log('Valores atuais:', valoresAtuais);

                    // Verificar se a opção já está selecionada (toggle)
                    if (!valoresAtuais.includes(opcaoEncontrada)) {
                        // Adicionar a nova opção aos valores existentes
                        const novosValores = [...valoresAtuais, opcaoEncontrada];
                        $('#area_tematica').val(novosValores).trigger('change');
                        console.log('Área temática adicionada:', novosValores);
                    } else {
                        // Remover a opção se já estiver selecionada
                        const novosValores = valoresAtuais.filter(valor => valor !== opcaoEncontrada);
                        $('#area_tematica').val(novosValores).trigger('change');
                        console.log('Área temática removida:', novosValores);
                    }

                    // Executar o filtro
                    setTimeout(function() {
                        console.log('Executando filtrarObras...');
                        console.log('Valor final do campo:', $('#area_tematica').val());
                        filtrarObras();
                    }, 200);
                } else {
                    console.error('Opção não encontrada para:', areaTematica);
                    alert('Área temática "' + areaTematica + '" não encontrada nas opções disponíveis');
                }
            }, 100);

        } catch (error) {
            console.error('Erro ao filtrar por área temática:', error);
        }
    }

    // Função para formatar valores
    function formatarValor(valor) {
        if (!valor) return '0,00';
        return new Intl.NumberFormat('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(valor);
    }

    // Função para converter situação
    function handlerSituationWork(situation) {
        switch (situation) {
            case 'A': return 'Andamento';
            case 'C': return 'Concluída';
            case 'I': return 'Inacabado';
            case 'P': return 'Paralisado';
            default: return situation;
        }
    }

        // Inicializar quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== INICIANDO MAPA ===');

        // Inicializar Select2
        $('#situacao_obra, #municipio, #area_tematica, #nm_obra, #orgao').select2({
            placeholder: 'Selecione...',
            allowClear: true,
            closeOnSelect: false,
            language: {
                noResults: function() {
                    return "Nenhum resultado encontrado";
                }
            }
        });

        // Teste da função
        console.log('Testando função filtrarPorAreaTematica:', typeof window.filtrarPorAreaTematica);

        initMap();
        addMarkersToMap();

        // FORÇAR coordenadas de Goiás múltiplas vezes
        setTimeout(function() {
            console.log('Forçando coordenadas de Goiás (1s)...');
            map.setView([-16.6869, -49.2648], 8);
        }, 1000);

        setTimeout(function() {
            console.log('Forçando coordenadas de Goiás (3s)...');
            map.setView([-16.6869, -49.2648], 8);
        }, 3000);

        setTimeout(function() {
            console.log('Forçando coordenadas de Goiás (5s)...');
            map.setView([-16.6869, -49.2648], 8);
        }, 5000);
    });
</script>

<style>
.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-height: 38px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #007bff;
    color: white;
    border-radius: 3px;
    padding: 0 5px;
    margin: 2px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white;
    margin-right: 5px;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ff6b6b;
}
</style>
@endsection