<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Doações</title>
    <style>
        body { font-family: sans-serif; margin: 40px; line-height: 1.6; }
        .filtro { background: #f4f4f4; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .resultado { border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .valor { font-size: 1.5em; color: #2c3e50; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Relatório de Doações</h1>

    <div class="filtro">
        <form method="GET" action="/relatorio-doacoes">
            <label>Data Início:</label>
            <input type="date" name="inicio" value="<?= e($dataInicio) ?>">
            
            <label>Data Fim:</label>
            <input type="date" name="fim" value="<?= e($dataFim) ?>">
            
            <button type="submit">Gerar Relatório</button>
        </form>
    </div>

    <div class="resultado">
        <h3>Resumo das Doações</h3>
        
        <?php if ($dados['quantidade'] > 0): ?>
            <p>Período selecionado: <strong><?= e($dataInicio) ?></strong> até <strong><?= e($dataFim) ?></strong></p>
            <hr>
            <p>Quantidade de Doações: <strong><?= e($dados['quantidade']) ?></strong></p>
            <p>Total Arrecadado:</p>
            <div class="valor">
                R$ <?= number_format($dados['total'] ?? 0, 2, ',', '.') ?>
            </div>
        <?php else: ?>
            <p>Nenhuma doação encontrada para o período selecionado.</p>
        <?php endif; ?>
    </div>

</body>
</html>