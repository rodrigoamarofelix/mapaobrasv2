<?php
// Arquivo simples para visualizar municípios - versão sem Laravel
$host = 'postgres';
$port = '5432';
$dbname = 'mapaobrasv2';
$user = 'postgres';
$password = 'postgres';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar municípios
    $stmt = $pdo->query("SELECT nome, uf FROM municipios ORDER BY nome LIMIT 50");
    $municipios = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Contar total
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM municipios");
    $total = $stmt->fetch(PDO::FETCH_OBJ)->total;

    // Contar por UF
    $stmt = $pdo->query("SELECT uf, COUNT(*) as total FROM municipios GROUP BY uf ORDER BY uf");
    $porUf = $stmt->fetchAll(PDO::FETCH_OBJ);

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
    echo '<p><strong>Exibindo:</strong> ' . count($municipios) . ' municípios</p>';
    echo '</div>';

    if (count($porUf) > 0) {
        echo '<div class="uf-stats">';
        echo '<h3>📍 Distribuição por UF</h3>';
        foreach($porUf as $uf) {
            echo '<div class="uf-item"><strong>' . $uf->uf . '</strong>: ' . $uf->total . ' municípios</div>';
        }
        echo '</div>';
    }

    echo '<h3>📍 Lista de Municípios</h3>';
    foreach($municipios as $municipio) {
        echo '<div class="municipio"><strong>' . htmlspecialchars($municipio->nome) . '</strong> (' . htmlspecialchars($municipio->uf) . ')</div>';
    }

    echo '<p style="text-align:center;margin-top:30px;">';
    echo '<a href="/obras" style="background:#e74c3c;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">← Voltar para Obras</a>';
    echo '</p>';

    echo '</div>';
    echo '</body></html>';

} catch (PDOException $e) {
    echo '<h1>Erro de Conexão</h1>';
    echo '<p>Erro ao conectar com o banco de dados: ' . $e->getMessage() . '</p>';
} catch (Exception $e) {
    echo '<h1>Erro</h1>';
    echo '<p>Erro ao carregar municípios: ' . $e->getMessage() . '</p>';
}
?>
