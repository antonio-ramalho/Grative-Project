<?php

class DoadorModel {
    private $conn;

    public function __construct($conexaoBanco){
        $this->conn = $conexaoBanco;
    }

    public function salvar($dados) {
        try {
            $sql = "INSERT INTO doador 
                    (nome, nome_usuario, email, data_nascimento, firebase_uid) 
                    VALUES 
                    (:nome, :usuario, :email, :data_nasc, :uid)";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':nome', $dados['nome_doador']);
            $stmt->bindValue(':usuario', $dados['usuario_doador']);
            $stmt->bindValue(':email', $dados['email_doador']);
            $stmt->bindValue(':data_nasc', $dados['data_nasc_doador']);

            $stmt->bindValue(':uid', $dados['id_firebase']); 

            return $stmt->execute();

        } 
        catch (PDOException $e) {
            die("ERRO SQL: " . $e->getMessage());
        }
    }
}