<?php

function verificarSessao() {
    if (!isset($_SESSION['id_instituicao'])) {
        echo json_encode(['error' => 'Usuário não autenticado']);
        header('Location: login');
        return;
    }
}



