<?php

class OscModel {
    private $conn;

    public function __construct($conexaoBanco){
        $this->conn = $conexaoBanco;
    }

public function salvar($dados) {
        try {
            $sql = "INSERT INTO instituicao 
                    (nome_instituicao, cnpj, cep, telefone, email, firebase_uid, logradouro, num_ende, bairro, cidade, estado, descricao, chave_pix, nota) 
                    VALUES 
                    (:nome, :cnpj, :cep, :telefone, :email, :uid, :logradouro, :numero, :bairro, :cidade, :estado, :descricao, :pix, 0)";

            $stmt = $this->conn->prepare($sql);

            // 3. Fazer a "Tradução" (Ligando o SQL ao seu JavaScript)
            $stmt->bindValue(':nome', $dados['nome_osc']);
            $stmt->bindValue(':cnpj', $dados['cnpj_osc']);
            $stmt->bindValue(':cep', $dados['cep_osc']);
            $stmt->bindValue(':telefone', $dados['telefone_osc']);
            $stmt->bindValue(':email', $dados['email_osc']);
            $stmt->bindValue(':uid', $dados['id_firebase']); // Esse é o ID que vem da autenticação!
            $stmt->bindValue(':pix', $dados['pix_osc']);
            $stmt->bindValue(':logradouro', $dados['logradouro_osc']);
            $stmt->bindValue(':numero', $dados['num_ende_osc']);
            $stmt->bindValue(':bairro', $dados['bairro_osc']);
            $stmt->bindValue(':cidade', $dados['cidade_osc']);
            $stmt->bindValue(':estado', $dados['estado_osc']);
            $stmt->bindValue(':descricao', $dados['descricao_osc']);


            return $stmt->execute();

        } 
        catch (PDOException $e) {
            die("ERRO SQL: " . $e->getMessage());
        }
    }
}