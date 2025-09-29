<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\ApiController;

// Rota de saúde da aplicação
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '2.0.0'
    ]);
});

// Rota de teste para municípios
Route::get('/test-municipios', function () {
    $municipios = DB::table('municipios')->orderBy('nome')->limit(10)->get();
    $total = DB::table('municipios')->count();

    return response()->json([
        'total' => $total,
        'municipios' => $municipios
    ]);
});

// Rota simples para ver municípios
Route::get('/municipios-simples', function () {
    $municipios = DB::table('municipios')->orderBy('nome')->limit(20)->get();
    $total = DB::table('municipios')->count();

    $html = '<h1>Municípios Importados (' . $total . ' total)</h1>';
    $html .= '<ul>';
    foreach($municipios as $municipio) {
        $html .= '<li>' . $municipio->nome . ' (' . $municipio->uf . ')</li>';
    }
    $html .= '</ul>';

    return $html;
});

// Rotas do módulo Landing
Route::controller(LandingController::class)->group(function () {
    Route::get('/', 'index')->name('landing.index');
    Route::get('/saiba-mais', 'saibaMais')->name('landing.saiba-mais');
    Route::get('/bi', 'bi')->name('landing.bi');
    Route::get('/obras-maps', 'obrasMaps')->name('landing.obras-maps');
    Route::get('/obras-makers', 'obrasMakers')->name('landing.obras-makers');
    Route::get('/detalhe-mapas/{id}', 'detalheMapas')->name('landing.detalhe-mapas');
    Route::get('/detalhe-mapas/{id}/{cj}', 'detalheMapas')->name('landing.detalhe-mapas-cj');
    Route::get('/detalhe-makers/{id}', 'detalheMakers')->name('landing.detalhe-makers');
});

// Rotas do módulo Obras
Route::controller(ObraController::class)->group(function () {
    Route::get('/obras', 'index')->name('obras.index');
    Route::get('/obras/{id}', 'show')->name('obras.show');
    Route::get('/detalhe-mapas/{id}', 'detalheMapas')->name('obras.detalhe-mapas');
    Route::post('/obras/filter', 'filter')->name('obras.filter');
    Route::get('/obras/export/json', 'export')->name('obras.export');
    Route::post('/obras/atualizar-cache', 'atualizarCache')->name('obras.atualizar-cache');
    Route::get('/obras/import-municipios', 'importMunicipios')->name('obras.import-municipios');
    Route::get('/obras/municipios', 'listMunicipios')->name('obras.municipios');
    Route::get('/obras/import-api', 'getObrasApi')->name('obras.import-api');
    Route::get('/obras/import-incremental', 'getObrasApiIncremental')->name('obras.import-incremental');
    Route::get('/obras/test-import', 'testImport')->name('obras.test-import');
    Route::get('/obras/test-api', 'testApi')->name('obras.test-api');
    Route::get('/obras/debug-api', 'debugApi')->name('obras.debug-api');
    Route::get('/obras/test-count', 'testApiCount')->name('obras.test-count');
});

// Rota simples para ver municípios (fora do grupo de controller)
Route::get('/municipios', function () {
    $municipios = DB::table('municipios')->orderBy('nome')->limit(50)->get();
    $total = DB::table('municipios')->count();

    $html = '<!DOCTYPE html><html><head><title>Municípios Importados</title>';
    $html .= '<style>body{font-family:Arial,sans-serif;margin:20px;}';
    $html .= '.header{background:#e74c3c;color:white;padding:20px;border-radius:5px;margin-bottom:20px;}';
    $html .= '.municipio{background:#f8f9fa;padding:10px;margin:5px 0;border-left:3px solid #3498db;border-radius:3px;}';
    $html .= '.stats{background:#ecf0f1;padding:15px;border-radius:5px;margin-bottom:20px;}</style></head><body>';

    $html .= '<div class="header"><h1>🏗️ Municípios Importados</h1>';
    $html .= '<p>Total: ' . $total . ' municípios</p></div>';

    $html .= '<div class="stats"><h3>📊 Estatísticas</h3>';
    $html .= '<p><strong>Total de municípios:</strong> ' . $total . '</p>';
    $html .= '<p><strong>Exibindo:</strong> ' . $municipios->count() . ' municípios</p></div>';

    $html .= '<h3>📍 Lista de Municípios</h3>';
    foreach($municipios as $municipio) {
        $html .= '<div class="municipio"><strong>' . $municipio->nome . '</strong> (' . $municipio->uf . ')</div>';
    }

    $html .= '<p><a href="/obras">← Voltar para Obras</a></p>';
    $html .= '</body></html>';

    return $html;
});

// Rotas da API
Route::prefix('api')->controller(ApiController::class)->group(function () {
    Route::get('/obras', 'index')->name('api.obras.index');
    Route::get('/obras/{id}', 'show')->name('api.obras.show');
    Route::get('/obras/projeto/{id_projeto}', 'getByProject')->name('api.obras.by-project');
    Route::get('/obras/statistics', 'statistics')->name('api.obras.statistics');
});

