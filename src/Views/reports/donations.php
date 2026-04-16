<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Doações</title>
    <link rel="stylesheet" href="css\geral.css">
    <link rel="stylesheet" href="css\gerar_relatorio.css">
    <link rel="stylesheet" href="css\componentes.css">
</head>
<body>
    <section id="cabecalho" class="interface">
        <button class="botao_cancelar">Voltar</button>
        <img src="/img/icons/person-circle.svg" alt="icone-usuario" class="icone">
    </section>

    <section id="filtro_gerar" class="interface">
        <div>
            <h1>Defina um intervalo de tempo</h1>
        </div>

        <div class="filtro">
            <form method="GET" action="/relatorio-doacoes" class="formulario">
                <div>
                    <label>Data Início:</label>
                    <input class="input_padrao" type="date" name="inicio" value="<?= e($dataInicio) ?>">
                    <label>Data Fim:</label>
                    <input class="input_padrao" type="date" name="fim" value="<?= e($dataFim) ?>">
                </div>
            
                <button type="submit" class="botao_acao">Consultar Dados</button>
            </form>
        </div>
    </section>

    <section id="resultado_cards" class="interface">
        <div id="container-app" class="card interface card_categoria" data-saldo-inicial="<?= (float)$dados['total'] ?>">
            <h2>Defina as categorias</h2>
            <form id="form-categoria">
                <div class="inputs-form">
                    <div class="box-form">
                        <label for="categoria">Categoria</label>
                        <select class="input_padrao input-categoria" id="selecionar-categoria" name="categoria">
                            <option value="">Selecionar</option>
                            <option value="Roupas">Roupas</option>
                            <option value="Salários">Salários</option>
                            <option value="Operacionais">Despesas Operacionais</option>
                        </select>
                    </div>
                    <div class="box-form">
                        <label for="categoria-nome">Valor</label>
                        <input data-valor="" class="input_padrao input-categoria" type="number" step="0.01" 
                            min="0.01"id="categoria-valor" name="categoria-valor" placeholder="Digire o valor">
                    </div>
                </div>
                <button type="submit" id="btn-salvar-categoria" class="botao_acao">Nova Categoria</button>
            </form>
            <hr>
            <div id="lista_categorias"></div>
        </div>

        <div class="card interface card_resumo">
            <h2>Resumo das Doações</h2>
        
            <?php if ($dados['quantidade'] > 0): ?>
                <div class="resumo">
                    <p>Período selecionado: <strong><?= e($dataInicio) ?></strong> - <strong><?= e($dataFim) ?></strong></p>
                    <p>Quantidade de Doações: <strong><?= e($dados['quantidade']) ?></strong></p>
                    <div class="total">
                        <p>Total Arrecadado:</p>
                        <div class="valor">
                            R$ <?= number_format($dados['total'] ?? 0, 2, ',', '.') ?>
                        </div>
                    </div>
                </div>
                <hr>
            <?php else: ?>
                <p>Nenhuma doação encontrada para o período selecionado.</p>
            <?php endif; ?>

            <div id="js_publicar_relatorio">
                <div id="resumo-financeiro" data-total="<?= (float)$dados['total'] ?>">
                    <h2>Saldo Restante: <span id="saldo-dinamico">R$ <?= number_format($dados['total'], 2, ',', '.') ?></span></h2>
                </div>
                <div class="div-btn-pub"><button type="submit" class="botao_acao" id="btn-publicar">Públicar Relatório</button></div>
            </div>
        </div>
    </section>
    <script src="js/donations.js"></script>
</body>
</html>