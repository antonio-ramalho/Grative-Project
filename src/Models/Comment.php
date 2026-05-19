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

        $stmt = $this->pdo->prepare("
            SELECT c.id, c.texto, c.data_comentario, c.usuario_id, u.usuario AS nome_usuario 
            FROM comentarios c
            JOIN usuario u ON c.usuario_id = u.id_usuario 
            WHERE c.post_id = ? 
            ORDER BY c.data_comentario DESC
        ");
        
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function create($data)
    {
        
        $stmt = $this->pdo->prepare("INSERT INTO comentarios (post_id, texto, usuario_id, data_comentario) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$data['post_id'], $data['comment'], $data['usuario_id']]);
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