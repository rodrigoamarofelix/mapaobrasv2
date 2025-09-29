@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-download me-2"></i>Importação de Obras</h1>
        <div>
            <a href="{{ route('obras.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Voltar para Obras
            </a>
        </div>
    </div>

    <!-- Status da API -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Status da API</h5>
                </div>
                <div class="card-body">
                    <div id="api-status" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Verificando...</span>
                        </div>
                        <p class="mt-2">Verificando status da API...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Opções de Importação -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sync-alt me-2"></i>Importação Completa</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>⚠️ ATENÇÃO:</strong> Esta operação irá apagar todos os dados existentes e importar tudo novamente da API.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Validação prévia da API</li>
                        <li><i class="fas fa-check text-success me-2"></i>Lotes de 1000 projetos</li>
                        <li><i class="fas fa-check text-success me-2"></i>Tratamento de rate limiting</li>
                        <li><i class="fas fa-check text-success me-2"></i>Logs detalhados</li>
                    </ul>
                    <button id="btn-import-complete" class="btn btn-danger w-100" disabled>
                        <i class="fas fa-download me-2"></i>Importação Completa
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Importação Incremental</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <strong>✅ RECOMENDADO:</strong> Importa apenas projetos novos ou atualizados, mantendo os dados existentes.
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Validação prévia da API</li>
                        <li><i class="fas fa-check text-success me-2"></i>Preserva dados existentes</li>
                        <li><i class="fas fa-check text-success me-2"></i>Atualiza projetos modificados</li>
                        <li><i class="fas fa-check text-success me-2"></i>Mais rápido e seguro</li>
                    </ul>
                    <button id="btn-import-incremental" class="btn btn-primary w-100" disabled>
                        <i class="fas fa-plus me-2"></i>Importação Incremental
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary"><i class="fas fa-building me-2"></i>Projetos</h5>
                    <h3 class="text-primary" id="total-projetos">-</h3>
                    <p class="card-text">Total de projetos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title text-success"><i class="fas fa-map-marker-alt me-2"></i>Obras</h5>
                    <h3 class="text-success" id="total-obras">-</h3>
                    <p class="card-text">Total de obras</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title text-info"><i class="fas fa-city me-2"></i>Municípios</h5>
                    <h3 class="text-info" id="total-municipios">-</h3>
                    <p class="card-text">Total de municípios</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning"><i class="fas fa-clock me-2"></i>Última Importação</h5>
                    <h6 class="text-warning" id="ultima-importacao">-</h6>
                    <p class="card-text">Data da última importação</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Log de Importação -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Log de Importação</h5>
                    <button id="btn-refresh-log" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-sync-alt me-1"></i>Atualizar
                    </button>
                </div>
                <div class="card-body">
                    <div id="import-log" class="bg-dark text-light p-3 rounded" style="height: 300px; overflow-y: auto; font-family: monospace;">
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            Aguardando início da importação...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Importação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirm-message"></p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta operação pode levar alguns minutos para ser concluída.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-confirm-import">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentImportType = null;
    let logInterval = null;

    // Verificar status da API
    checkApiStatus();

    // Carregar estatísticas
    loadStatistics();

    // Event listeners
    document.getElementById('btn-import-complete').addEventListener('click', () => {
        showConfirmModal('complete', 'Tem certeza que deseja fazer uma importação completa? Todos os dados existentes serão apagados e substituídos pelos dados da API.');
    });

    document.getElementById('btn-import-incremental').addEventListener('click', () => {
        showConfirmModal('incremental', 'Tem certeza que deseja fazer uma importação incremental? Apenas projetos novos ou atualizados serão importados.');
    });

    document.getElementById('btn-confirm-import').addEventListener('click', startImport);
    document.getElementById('btn-refresh-log').addEventListener('click', refreshLog);

    function checkApiStatus() {
        fetch('/obras/validate-api')
            .then(response => response.json())
            .then(data => {
                const statusDiv = document.getElementById('api-status');
                if (data.success) {
                    statusDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>API Online</strong><br>
                            ${data.message}<br>
                            <small class="text-muted">Última verificação: ${new Date().toLocaleString()}</small>
                        </div>
                    `;
                    enableImportButtons();
                } else {
                    statusDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>API Offline</strong><br>
                            ${data.message}<br>
                            <small class="text-muted">Código do erro: ${data.error_code}</small>
                        </div>
                    `;
                    disableImportButtons();
                }
            })
            .catch(error => {
                document.getElementById('api-status').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Erro de Conexão</strong><br>
                        Não foi possível verificar o status da API.
                    </div>
                `;
                disableImportButtons();
            });
    }

    function enableImportButtons() {
        document.getElementById('btn-import-complete').disabled = false;
        document.getElementById('btn-import-incremental').disabled = false;
    }

    function disableImportButtons() {
        document.getElementById('btn-import-complete').disabled = true;
        document.getElementById('btn-import-incremental').disabled = true;
    }

    function loadStatistics() {
        // Simular carregamento de estatísticas
        document.getElementById('total-projetos').textContent = '8';
        document.getElementById('total-obras').textContent = '8';
        document.getElementById('total-municipios').textContent = '246';
        document.getElementById('ultima-importacao').textContent = 'Hoje 14:30';
    }

    function showConfirmModal(type, message) {
        currentImportType = type;
        document.getElementById('confirm-message').textContent = message;
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
    }

    function startImport() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
        modal.hide();

        const url = currentImportType === 'complete' ? '/obras/import-api' : '/obras/import-incremental';
        const buttonId = currentImportType === 'complete' ? 'btn-import-complete' : 'btn-import-incremental';

        // Desabilitar botões
        document.getElementById(buttonId).disabled = true;
        document.getElementById(buttonId).innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Importando...';

        // Limpar log
        document.getElementById('import-log').innerHTML = '<div class="text-center text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Iniciando importação...</div>';

        // Iniciar monitoramento do log
        startLogMonitoring();

        // Fazer requisição
        fetch(url)
            .then(response => response.json())
            .then(data => {
                stopLogMonitoring();

                if (data.success) {
                    addLogEntry('success', 'Importação concluída com sucesso!');
                    addLogEntry('info', `Projetos: ${data.data.total_projetos || data.data.projetos_novos + data.data.projetos_atualizados}`);
                    addLogEntry('info', `Obras: ${data.data.total_obras || data.data.obras_novas}`);
                    loadStatistics();
                } else {
                    addLogEntry('error', `Erro na importação: ${data.message}`);
                }
            })
            .catch(error => {
                stopLogMonitoring();
                addLogEntry('error', `Erro de conexão: ${error.message}`);
            })
            .finally(() => {
                // Reabilitar botões
                document.getElementById(buttonId).disabled = false;
                document.getElementById(buttonId).innerHTML = currentImportType === 'complete'
                    ? '<i class="fas fa-download me-2"></i>Importação Completa'
                    : '<i class="fas fa-plus me-2"></i>Importação Incremental';
            });
    }

    function startLogMonitoring() {
        logInterval = setInterval(refreshLog, 2000);
    }

    function stopLogMonitoring() {
        if (logInterval) {
            clearInterval(logInterval);
            logInterval = null;
        }
    }

    function refreshLog() {
        // Simular atualização do log
        const logDiv = document.getElementById('import-log');
        const now = new Date().toLocaleTimeString();

        // Adicionar entrada simulada
        addLogEntry('info', `[${now}] Verificando status da API...`);
    }

    function addLogEntry(type, message) {
        const logDiv = document.getElementById('import-log');
        const timestamp = new Date().toLocaleTimeString();
        const icon = type === 'success' ? 'check-circle text-success' :
                    type === 'error' ? 'times-circle text-danger' :
                    'info-circle text-info';

        const entry = document.createElement('div');
        entry.innerHTML = `<i class="fas fa-${icon} me-2"></i>[${timestamp}] ${message}`;
        logDiv.appendChild(entry);
        logDiv.scrollTop = logDiv.scrollHeight;
    }
});
</script>
@endsection
