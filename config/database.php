<?php
// config/database.php

// Lendo os dados que estão no arquivo .env
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$dbname = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    // Montando a conexão com os dados do TiDB
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    
    // Configura para exibir os erros, útil no desenvolvimento
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Retorna a conexão para ser usada no resto do seu sistema (como na pasta src)
    return $conn; 
    
} catch(PDOException $e) {
    die("Erro de conexão com o Banco de Dados: " . $e->getMessage());
}