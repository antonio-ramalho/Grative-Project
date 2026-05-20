<?php
session_start();

$posts = [
    [
        'id' => 1,
        'title' => 'TESTE',
        'body' => 'TESTE TESTE TESTE TESTE TESTE TESTE TESTE.',
    ],
    [
        'id' => 2,
        'title' => 'TESTE',
        'body' => 'TESTE TESTE TESTE TESTE TESTE TESTE TESTE.',
    ],
];

if (!isset($_SESSION['comments'])) {
    $_SESSION['comments'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($postId > 0 && $comment !== '') {
        $_SESSION['comments'][$postId][] = htmlspecialchars($comment, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    header('Location: ' . $_SERVER['PHP_SELF'] . '?post=' . $postId);
    exit;
}

$selectedPostId = intval($_GET['post'] ?? 0);
$selectedPost = null;
foreach ($posts as $post) {
    if ($post['id'] === $selectedPostId) {
        $selectedPost = $post;
        break;
    }
}

if ($selectedPost === null) {
    $selectedPost = $posts[0];
    $selectedPostId = $selectedPost['id'];
}

$currentComments = $_SESSION['comments'][$selectedPostId] ?? [];

require __DIR__ . '/../src/Views/index.php';
