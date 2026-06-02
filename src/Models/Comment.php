<?php

namespace App\Models;

use PDO;

class Comment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByPostId($post_id)
    {
        // Usa LEFT JOIN para buscar o nome do autor, seja ele um doador ou uma OSC
        $stmt = $this->pdo->prepare("
            SELECT c.id, c.texto, c.data_comentario, c.usuario_id, c.instituicao_id,
                   COALESCE(u.usuario, i.nome_instituicao) AS nome_usuario 
            FROM comentarios c
            LEFT JOIN usuario u ON c.usuario_id = u.id_usuario 
            LEFT JOIN instituicao i ON c.instituicao_id = i.id_instituicao
            WHERE c.post_id = ? 
            ORDER BY c.data_comentario DESC
        ");
        
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Pega o ID de quem estiver logado (doador ou OSC)
        $usuario_id = $data['usuario_id'] ?? null;
        $instituicao_id = $data['instituicao_id'] ?? null;

        $stmt = $this->pdo->prepare("INSERT INTO comentarios (post_id, texto, usuario_id, instituicao_id, data_comentario) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$data['post_id'], $data['comment'], $usuario_id, $instituicao_id]);
        return $this->pdo->lastInsertId();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comentarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id_comentario)
    {
        $stmt = $this->pdo->prepare("DELETE FROM comentarios WHERE id = ?");
        return $stmt->execute([$id_comentario]);
    }
}