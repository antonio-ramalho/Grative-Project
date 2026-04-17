<?php
namespace App\Models;

use PDO;
use PDOException;

class DoadorModel {
    private $conn;

    public function __construct($conexaoBanco){
        $this->conn = $conexaoBanco;
    }

    public function salvar($dados) {
        try {
           
            $sql = "INSERT INTO usuario 
                    (usuario, firebase_uid, nome_completo, cpf, data_nasc, telefone, email, data_cadastro, status_ativo) 
                    VALUES 
                    (:usuario, :uid, :nome, :cpf, :data_nasc, :telefone, :email, NOW(), 1)";

            $stmt = $this->conn->prepare($sql);

            
            $stmt->bindValue(':usuario', $dados['usuario_doador']);
            $stmt->bindValue(':uid', $dados['id_firebase']); 
            $stmt->bindValue(':nome', $dados['nome_doador']);
            $stmt->bindValue(':cpf', $dados['cpf_doador']);
            $stmt->bindValue(':data_nasc', $dados['data_nasc_doador']);
            $stmt->bindValue(':telefone', $dados['telefone_doador']);
            $stmt->bindValue(':email', $dados['email_doador']);

            $stmt->execute();
            
            return $this->conn->lastInsertId();

        } 
        catch (PDOException $e) {
            die("ERRO SQL: " . $e->getMessage());
        }
    }
    public function buscarPorId($id) {
        $query = "SELECT * FROM usuario WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarFirebaseUid($id) {
        $query = "SELECT firebase_uid FROM usuario WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['firebase_uid'] : false;
    }

    public function editar($id, $dados) {
        try {
            $sql = "UPDATE usuario SET 
                    nome_completo = :nome, 
                    usuario = :usuario, 
                    cpf = :cpf, 
                    telefone = :telefone 
                    WHERE id_usuario = :id";

            $stmt = $this->conn->prepare($sql);

            
            $stmt->bindValue(':nome', $dados['nome_doador']);
            $stmt->bindValue(':usuario', $dados['usuario_doador']);
            $stmt->bindValue(':cpf', preg_replace('/\D/', '', $dados['cpf_doador'])); 
            $stmt->bindValue(':telefone', preg_replace('/\D/', '', $dados['telefone_doador']));
            $stmt->bindValue(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao atualizar: " . $e->getMessage());
        }
    }

    public function excluir($id) {
        try {
            $sql = "DELETE FROM usuario WHERE id_usuario = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao excluir: " . $e->getMessage());
        }
    }
    public function buscarIdPorFirebaseUid($firebaseUid) {
        $query = "SELECT id_usuario FROM usuario WHERE firebase_uid = :uid LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $firebaseUid);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $resultado['id_usuario'];
        }
        return false;
    }
}
