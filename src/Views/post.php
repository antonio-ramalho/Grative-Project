<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title'] ?? 'Post', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?> - Comentários</title>
    <link rel="stylesheet" href="/css/estilo_comment.css">
</head>
<body>
    <div class="page-container">
        <h1><?php echo htmlspecialchars($post['title'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($post['body'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>

        <div class="comments">
            <h2>Comentários</h2>
            <?php if (!empty($comments) && count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item"><?php echo htmlspecialchars($comment['comment'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum comentário ainda.</p>
            <?php endif; ?>
        </div>

        <form method="post" action="/comment">
            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['id'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
            <label for="comment">Adicionar comentário:</label>
            <textarea id="comment" name="comment" required></textarea>
            <button type="submit" class="button">Enviar</button>
        </form>

        <a href="/posts" class="button secondary">Voltar</a>
    </div>

    <script src="/js/app.js" defer></script>
</body>
</html>