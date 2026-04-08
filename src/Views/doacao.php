<?php

$host = '127.0.0.1';
$port = '3307';
$db   = 'grative';
$user = 'root';
$pass = 'breno136';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'] ?? 0;
    $msg   = $_POST['mensagem'] ?? '';

    try {
       
        $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
        
        $sql = "INSERT INTO donations (amount, message) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$valor, $msg]);

       
        header("Location: doacao.php?ok=" . $valor);
        exit;
        
    } catch (\Exception $e) {
        $erro = "Erro: " . $e->getMessage();
    }
}


$status = "";
if (isset($_GET['ok'])) {
    
} elseif (isset($erro)) {
    
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

        <p>Mensagem:<br>
        <input type="text" name="mensagem"></p>

        <button type="submit" style="padding: 10px; cursor: pointer;">ENVIAR</button>
    </form>

    <p><a href="/">Voltar para o Início</a></p>

</body>
</html>