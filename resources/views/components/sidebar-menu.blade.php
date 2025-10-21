<!-- Sidebar Menu Component -->
<div class="list-group">
    <a href="{{ route('landing.obras-maps') }}" class="list-group-item list-group-item-action {{ request()->routeIs('landing.obras-maps') ? 'active' : '' }}">
        <i class="fas fa-map-marker-alt me-2"></i>MAPA DE OBRAS
    </a>
    <a href="{{ route('landing.bi2') }}" class="list-group-item list-group-item-action {{ request()->routeIs('landing.bi2') ? 'active' : '' }}">
        <i class="fas fa-list me-2"></i>OBRAS - GOMAP
    </a>
    <a href="/detalhes-projeto" class="list-group-item list-group-item-action {{ request()->is('detalhes-projeto') ? 'active' : '' }}">
        <i class="fas fa-search me-2"></i>DETALHES DO PROJETO
    </a>
    <div class="list-group-item">
        <i class="fas fa-times-circle me-2"></i>OBRAS PARALISADAS
    </div>
    <div class="list-group-item">
        <i class="fas fa-handshake me-2"></i>OBRAS COM CONVÊNIOS E OUTRAS PARCERIAS
    </div>
    <div class="list-group-item">
        <i class="fas fa-chart-bar me-2"></i>INFORMAÇÕES CONTRATUAIS E ANEXOS
    </div>
    <div class="list-group-item">
        <i class="fas fa-info-circle me-2"></i>SOBRE
    </div>
</div>

