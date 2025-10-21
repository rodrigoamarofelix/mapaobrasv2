<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== IMPORTAÇÃO PERSONALIZADA DE PROCESSO SEI ===\n";
echo "Este script permite importar dados de processo SEI manualmente.\n\n";

// DADOS PARA IMPORTAR - EDITE ESTA SEÇÃO COM OS DADOS CORRETOS DO SEU MYSQL
// Formato: ['id_projeto' => numero_do_projeto, 'processo_sei' => 'numero_do_processo_sei']
$dadosProcessoSei = [
    // Exemplo com alguns IDs de projeto que existem na sua tabela:
    ['id_projeto' => 100, 'processo_sei' => '202200004039661'],
    ['id_projeto' => 101, 'processo_sei' => '201712404000390'],
    ['id_projeto' => 102, 'processo_sei' => '201812404000066'],
    ['id_projeto' => 103, 'processo_sei' => '201700027000076'],
    ['id_projeto' => 104, 'processo_sei' => '201500027000240'],
    ['id_projeto' => 105, 'processo_sei' => '202100006030250'],
    ['id_projeto' => 106, 'processo_sei' => '201600005004057'],
    ['id_projeto' => 108, 'processo_sei' => '202100053000390'],
    ['id_projeto' => 124, 'processo_sei' => '201800025005959'],
    ['id_projeto' => 141, 'processo_sei' => '201900015000123'],

    // ADICIONE MAIS DADOS AQUI CONFORME NECESSÁRIO
    // ['id_projeto' => XXX, 'processo_sei' => 'YYYYYYYYYYYYYYY'],
];

echo "📋 Dados para importar:\n";
foreach ($dadosProcessoSei as $dado) {
    echo "ID Projeto: {$dado['id_projeto']}, Processo SEI: {$dado['processo_sei']}\n";
}

echo "\n🔄 Iniciando importação...\n";

$successCount = 0;
$errorCount = 0;

foreach ($dadosProcessoSei as $dado) {
    try {
        $idProjeto = $dado['id_projeto'];
        $processoSei = $dado['processo_sei'];

        // Verificar se a obra existe
        $obra = DB::table('mapaobras')->where('id_projeto', $idProjeto)->first();

        if ($obra) {
            // Atualizar o campo numeroprocessosei
            DB::table('mapaobras')
                ->where('id_projeto', $idProjeto)
                ->update(['numeroprocessosei' => $processoSei]);

            $successCount++;
            echo "✅ Obra ID {$obra->id} (Projeto $idProjeto): $processoSei - {$obra->nome_projeto}\n";
        } else {
            $errorCount++;
            echo "⚠️  Obra com ID Projeto $idProjeto não encontrada\n";
        }

    } catch (\Exception $e) {
        $errorCount++;
        echo "❌ Erro no ID Projeto {$dado['id_projeto']}: " . $e->getMessage() . "\n";
    }
}

echo "\n=== RESUMO DA IMPORTAÇÃO ===\n";
echo "✅ Registros importados com sucesso: $successCount\n";
echo "❌ Registros com erro: $errorCount\n";
echo "📊 Total processado: " . count($dadosProcessoSei) . "\n";

// Verificar resultado final
echo "\n🔍 Verificando resultado final...\n";
$totalComProcesso = DB::table('mapaobras')
    ->whereNotNull('numeroprocessosei')
    ->where('numeroprocessosei', '!=', '')
    ->count();

echo "📈 Total de obras com processo SEI no PostgreSQL: $totalComProcesso\n";

if ($totalComProcesso > 0) {
    echo "\n🎉 IMPORTAÇÃO CONCLUÍDA COM SUCESSO!\n";
    echo "Agora você pode testar a funcionalidade do Diário Oficial.\n";

    // Mostrar algumas obras atualizadas
    echo "\n📋 Exemplos de obras atualizadas:\n";
    $obrasAtualizadas = DB::table('mapaobras')
        ->whereNotNull('numeroprocessosei')
        ->where('numeroprocessosei', '!=', '')
        ->limit(5)
        ->get(['id', 'id_projeto', 'nome_projeto', 'numeroprocessosei']);

    foreach ($obrasAtualizadas as $obra) {
        echo "ID: {$obra->id}, Projeto: {$obra->id_projeto}, Processo SEI: {$obra->numeroprocessosei}\n";
    }
} else {
    echo "\n⚠️  Nenhuma obra foi atualizada. Verifique os dados.\n";
}

echo "\n=== INSTRUÇÕES PARA PERSONALIZAR ===\n";
echo "1. Edite a seção 'DADOS PARA IMPORTAR' neste arquivo\n";
echo "2. Substitua os valores de exemplo pelos dados reais do seu MySQL\n";
echo "3. Execute o script novamente: docker compose exec app php import_processo_sei_personalizado.php\n";
echo "4. Repita até importar todos os dados necessários\n";

echo "\n=== SCRIPT FINALIZADO ===\n";

?>


