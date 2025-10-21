<?php

// Script para importar processo_sei_da_contratacao do MySQL para numeroprocessosei no PostgreSQL
// Execute este script no terminal: php import_processo_sei.php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Configuração do MySQL (banco de origem)
$mysqlConfig = [
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'seu_banco_mysql',
    'username' => 'seu_usuario_mysql',
    'password' => 'sua_senha_mysql',
];

// Configuração do PostgreSQL (banco de destino)
$pgsqlConfig = [
    'host' => 'localhost',
    'port' => '5432',
    'database' => 'mapaobrasv2',
    'username' => 'postgres',
    'password' => 'postgres',
];

try {
    echo "=== INICIANDO IMPORTAÇÃO DE PROCESSO SEI ===\n";

    // Conectar ao MySQL
    $mysqlConnection = new PDO(
        "mysql:host={$mysqlConfig['host']};port={$mysqlConfig['port']};dbname={$mysqlConfig['database']}",
        $mysqlConfig['username'],
        $mysqlConfig['password']
    );
    $mysqlConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Conectar ao PostgreSQL
    $pgsqlConnection = new PDO(
        "pgsql:host={$pgsqlConfig['host']};port={$pgsqlConfig['port']};dbname={$pgsqlConfig['database']}",
        $pgsqlConfig['username'],
        $pgsqlConfig['password']
    );
    $pgsqlConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar dados do MySQL
    $query = "SELECT id_projeto, processo_sei_da_contratacao FROM mapaobras WHERE processo_sei_da_contratacao IS NOT NULL AND processo_sei_da_contratacao != ''";
    $stmt = $mysqlConnection->prepare($query);
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Encontrados " . count($dados) . " registros com processo SEI no MySQL\n";

    $atualizados = 0;
    $erros = 0;

    foreach ($dados as $dado) {
        try {
            // Atualizar no PostgreSQL
            $updateQuery = "UPDATE mapaobras SET numeroprocessosei = ? WHERE id_projeto = ?";
            $updateStmt = $pgsqlConnection->prepare($updateQuery);
            $result = $updateStmt->execute([$dado['processo_sei_da_contratacao'], $dado['id_projeto']]);

            if ($result) {
                $atualizados++;
                echo "✓ Atualizado ID Projeto {$dado['id_projeto']}: {$dado['processo_sei_da_contratacao']}\n";
            } else {
                $erros++;
                echo "✗ Erro ao atualizar ID Projeto {$dado['id_projeto']}\n";
            }

        } catch (Exception $e) {
            $erros++;
            echo "✗ Erro no ID Projeto {$dado['id_projeto']}: " . $e->getMessage() . "\n";
        }
    }

    echo "\n=== RESUMO DA IMPORTAÇÃO ===\n";
    echo "Total de registros processados: " . count($dados) . "\n";
    echo "Registros atualizados com sucesso: $atualizados\n";
    echo "Erros: $erros\n";

    // Verificar resultado final
    $verificacaoQuery = "SELECT COUNT(*) as total FROM mapaobras WHERE numeroprocessosei IS NOT NULL AND numeroprocessosei != ''";
    $verificacaoStmt = $pgsqlConnection->prepare($verificacaoQuery);
    $verificacaoStmt->execute();
    $resultado = $verificacaoStmt->fetch(PDO::FETCH_ASSOC);

    echo "Total de registros com processo SEI no PostgreSQL após importação: {$resultado['total']}\n";

} catch (Exception $e) {
    echo "ERRO GERAL: " . $e->getMessage() . "\n";
}

echo "\n=== IMPORTAÇÃO FINALIZADA ===\n";


