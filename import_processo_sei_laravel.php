<?php

// Script Laravel para importar processo_sei_da_contratacao
// Execute: docker compose exec app php import_processo_sei_laravel.php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== IMPORTAÇÃO DE PROCESSO SEI (LARAVEL) ===\n";

try {
    // Verificar se há dados para importar
    echo "Verificando dados atuais...\n";

    $totalObras = DB::table('mapaobras')->count();
    $obrasComProcesso = DB::table('mapaobras')
        ->whereNotNull('numeroprocessosei')
        ->where('numeroprocessosei', '!=', '')
        ->count();

    echo "Total de obras: $totalObras\n";
    echo "Obras com processo SEI: $obrasComProcesso\n";

    if ($obrasComProcesso == 0) {
        echo "\nNenhuma obra com processo SEI encontrada.\n";
        echo "Você precisa:\n";
        echo "1. Conectar ao banco MySQL original\n";
        echo "2. Exportar os dados do campo processo_sei_da_contratacao\n";
        echo "3. Importar para o campo numeroprocessosei no PostgreSQL\n\n";

        echo "Exemplo de query para exportar do MySQL:\n";
        echo "SELECT id_projeto, processo_sei_da_contratacao FROM mapaobras WHERE processo_sei_da_contratacao IS NOT NULL;\n\n";

        echo "Exemplo de query para importar no PostgreSQL:\n";
        echo "UPDATE mapaobras SET numeroprocessosei = 'valor' WHERE id_projeto = 'id';\n";

    } else {
        echo "\nEncontradas $obrasComProcesso obras com processo SEI!\n";

        // Mostrar alguns exemplos
        $exemplos = DB::table('mapaobras')
            ->whereNotNull('numeroprocessosei')
            ->where('numeroprocessosei', '!=', '')
            ->limit(5)
            ->get(['id_projeto', 'numeroprocessosei', 'nome_projeto']);

        echo "\nExemplos de obras com processo SEI:\n";
        foreach ($exemplos as $exemplo) {
            echo "- ID: {$exemplo->id_projeto}, Processo: {$exemplo->numeroprocessosei}, Nome: {$exemplo->nome_projeto}\n";
        }
    }

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT FINALIZADO ===\n";


