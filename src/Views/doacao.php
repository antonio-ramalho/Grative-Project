<?php
$host = 'gateway01.us-east-1.prod.aws.tidbcloud.com';
$port = '4000';
$db   = 'grative';
$user = '3ECGGvFuYA5vMsA.root';
$pass = 'o9siRzf71pKIzfap';

$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'] ?? 0;
    $msg   = $_POST['mensagem'] ?? '';

    try {
        $options = [
            PDO::MYSQL_ATTR_SSL_CA => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        
         $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, $options); 
        
        $sql = "INSERT INTO doacao (quantia, forma_pagan, fk_id_usuaria, fk_id_institui) VALUES (?, ?, 1, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$valor, $msg]);

        header("Location: doacao.php?ok=" . $valor);
        exit;
        
    } catch (\Exception $e) {
        $erro = "Erro: " . $e->getMessage();
    }
}

if (isset($_GET['ok'])) {
    $status = " Sucesso! R$ " . $_GET['ok'] . " gravado no banco.";
} elseif (isset($erro)) {
    $status = "erro " . $erro;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Doação Grative</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">

    <h2>Fazer Doação</h2>
    
    <?php if ($status): ?>
        <p style="color: blue; font-weight: bold; border: 1px solid blue; padding: 10px;">
            <?php echo $status; ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <p>Valor (R$):<br>
        <input type="number" name="valor" step="0.01" required></p>

        <p>Forma de Pagamento / Mensagem:<br>
        <input type="text" name="mensagem"></p>

        <button type="submit" style="padding: 10px; cursor: pointer;">ENVIAR</button>
    </form>

    <p><a href="/">Voltar para o Início</a></p>

</body>
</html>