<?php
// Arquivo simples para visualizar municípios
require_once __DIR__ . '/../vendor/autoload.php';

// Configurar Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $municipios = DB::table('municipios')->orderBy('nome')->limit(50)->get();
    $total = DB::table('municipios')->count();

    $porUf = DB::table('municipios')
        ->select('uf', DB::raw('count(*) as total'))
        ->groupBy('uf')
        ->orderBy('uf')
        ->get();

    echo '<!DOCTYPE html><html><head><title>Municípios Importados</title>';
    echo '<style>';
    echo 'body{font-family:Arial,sans-serif;margin:20px;background:#f5f5f5;}';
    echo '.container{max-width:1200px;margin:0 auto;background:white;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}';
    echo '.header{background:#e74c3c;color:white;padding:20px;border-radius:5px;margin-bottom:20px;text-align:center;}';
    echo '.stats{background:#ecf0f1;padding:15px;border-radius:5px;margin-bottom:20px;}';
    echo '.municipio{background:#f8f9fa;padding:10px;margin:5px 0;border-left:3px solid #3498db;border-radius:3px;}';
    echo '.uf-stats{background:#e8f4fd;padding:15px;border-radius:5px;margin-bottom:20px;}';
    echo '.uf-item{display:inline-block;background:white;padding:8px 12px;margin:5px;border-radius:3px;border:1px solid #ddd;}';
    echo '</style></head><body>';

    echo '<div class="container">';
    echo '<div class="header">';
    echo '<h1>🏗️ Municípios Importados</h1>';
    echo '<p>Total: ' . $total . ' municípios</p>';
    echo '</div>';

    echo '<div class="stats">';
    echo '<h3>📊 Estatísticas</h3>';
    echo '<p><strong>Total de municípios:</strong> ' . $total . '</p>';
    echo '<p><strong>Exibindo:</strong> ' . $municipios->count() . ' municípios</p>';
    echo '</div>';

    if ($porUf->count() > 0) {
        echo '<div class="uf-stats">';
        echo '<h3>📍 Distribuição por UF</h3>';
        foreach($porUf as $uf) {
            echo '<div class="uf-item"><strong>' . $uf->uf . '</strong>: ' . $uf->total . ' municípios</div>';
        }
        echo '</div>';
    }

    echo '<h3>📍 Lista de Municípios</h3>';
    foreach($municipios as $municipio) {
        echo '<div class="municipio"><strong>' . $municipio->nome . '</strong> (' . $municipio->uf . ')</div>';
    }

    echo '<p style="text-align:center;margin-top:30px;">';
    echo '<a href="/obras" style="background:#e74c3c;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">← Voltar para Obras</a>';
    echo '</p>';

    echo '</div>';
    echo '</body></html>';

} catch (Exception $e) {
    echo '<h1>Erro</h1>';
    echo '<p>Erro ao carregar municípios: ' . $e->getMessage() . '</p>';
}
?>
