<?php

namespace App\Models;

use PDO;
use PDOException;

class NotificacaoModel {
    private $pdo;

    public function __construct(PDO $db) {
        $this->pdo = $db;
    }

    public function criarNotificacao($idInstituicao, $mensagem, $link = null) {
        $sql = "INSERT INTO notificacoes (instituicao_id, mensagem, link_destino) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idInstituicao, $mensagem, $link]);
    }

    public function buscarPorInstituicao($idInstituicao) {
        $sql = "SELECT * FROM notificacoes WHERE instituicao_id = ? ORDER BY data_criacao DESC LIMIT 50";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idInstituicao]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marcarComoLida($idNotificacao, $idInstituicao) {
        $sql = "UPDATE notificacoes SET lida = 1 WHERE id = ? AND instituicao_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idNotificacao, $idInstituicao]);
    }
}