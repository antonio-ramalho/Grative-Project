<?php
/**
 * Inicializa o banco de dados com a tabela de comentários para publicações
 * Execute este arquivo uma vez: php config/init_comments_db.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

$envPath = __DIR__ . '/../.env';

// Carrega variáveis do .env
if (file_exists($envPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} else {
    die("❌ Erro: Arquivo .env não encontrado em $envPath\n");
}

// Obtém variáveis (tenta tanto getenv quanto $_ENV)
$dbHost = getenv('DB_HOST') ?: $_ENV['DB_HOST'] ?? null;
$dbDatabase = getenv('DB_DATABASE') ?: $_ENV['DB_DATABASE'] ?? null;
$dbUsername = getenv('DB_USERNAME') ?: $_ENV['DB_USERNAME'] ?? null;
$dbPassword = getenv('DB_PASSWORD') ?: $_ENV['DB_PASSWORD'] ?? null;
$dbPort = (getenv('DB_PORT') ?: $_ENV['DB_PORT'] ?? '3306') ?: '3306';

if (!$dbHost || !$dbDatabase || !$dbUsername || !$dbPassword) {
    echo "❌ Erro: Variáveis de ambiente não configuradas!\n";
    echo "   DB_HOST: " . ($dbHost ? '✓' : '✗') . "\n";
    echo "   DB_DATABASE: " . ($dbDatabase ? '✓' : '✗') . "\n";
    echo "   DB_USERNAME: " . ($dbUsername ? '✓' : '✗') . "\n";
    echo "   DB_PASSWORD: " . ($dbPassword ? '✓' : '✗') . "\n";
    die();
}

try {
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbDatabase;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/cacert.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];

    echo "🔌 Conectando ao banco de dados ($dbHost:$dbPort / $dbDatabase)...\n";
    $conn = new PDO($dsn, $dbUsername, $dbPassword, $options);

    // Criar tabela de comentários para publicações
    $conn->exec("
        CREATE TABLE IF NOT EXISTS comentarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            publicacao_id VARCHAR(255) NOT NULL,
            usuario_id INT NOT NULL,
            texto LONGTEXT NOT NULL,
            data_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(publicacao_id),
            INDEX(usuario_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    echo "✓ Tabela 'comentarios' criada com sucesso!\n";
    echo "✓ Comments table created successfully!\n";

} catch (PDOException $e) {
    die("❌ Erro ao conectar ou criar tabela: " . $e->getMessage() . "\n");
}
