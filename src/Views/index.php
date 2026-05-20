<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários de post</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="page-container">
        <h1>Painel Usuário</h1>
        <p>Grative, unindo intenção e necessidade, gerando impacto e valor real.</p>

        <?php if (!empty($posts) && is_array($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <h2><?php echo htmlspecialchars($post['title'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h2>
                    <p><?php echo htmlspecialchars($post['body'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></p>
                    <div class="actions">
                        <span>Post ID: <?php echo htmlspecialchars($post['id'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></span>
                        <a href="/post/<?php echo htmlspecialchars($post['id'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>" class="button">Comentar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum post disponível.</p>
        <?php endif; ?>
    </div>

    <script src="/js/app.js" defer></script>
</body>
</html>
