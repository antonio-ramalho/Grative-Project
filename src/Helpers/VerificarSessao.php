<?php

function verificarSessao() {
    if (!isset($_SESSION['id_instituicao'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Usuário não autenticado']);
        header('Location: login');
        return;
    }
    return $_SESSION['id_instituicao'] ?? null;
}



