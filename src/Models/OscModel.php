<?php
namespace App\Models;
use PDO;

class OscModel {
    private $conn;

    public function __construct($conexaoBanco){
        $this->conn = $conexaoBanco;
    }

    public function buscarFirebaseUid($id_instituicao) {
        $query = "SELECT firebase_uid FROM instituicao WHERE id_instituicao = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id_instituicao);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            return $resultado['firebase_uid'];
        }
        return false;
    }

    public function buscarIdPorFirebaseUid($firebaseUid) {
            $query = "SELECT id_instituicao FROM instituicao WHERE firebase_uid = :uid LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':uid', $firebaseUid);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return $resultado['id_instituicao'];
            }
            return false;
        }

    public function buscarPorId($id) {
        $query = "SELECT * FROM instituicao WHERE id_instituicao = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Retorna um array com todos os dados da linha
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function salvar($dados) {
            try {
                $sql = "INSERT INTO instituicao 
                        (nome_instituicao, cnpj, cep, telefone, email, firebase_uid, logradouro, num_ende, bairro, cidade, estado, descricao, chave_pix, nota) 
                        VALUES 
                        (:nome, :cnpj, :cep, :telefone, :email, :uid, :logradouro, :numero, :bairro, :cidade, :estado, :descricao, :pix, 0)";

                $stmt = $this->conn->prepare($sql);

                $stmt->bindValue(':nome', $dados['nome_osc']);
                $stmt->bindValue(':cnpj', $dados['cnpj_osc']);
                $stmt->bindValue(':cep', $dados['cep_osc']);
                $stmt->bindValue(':telefone', $dados['telefone_osc']);
                $stmt->bindValue(':email', $dados['email_osc']);
                $stmt->bindValue(':uid', $dados['id_firebase']);
                $stmt->bindValue(':pix', $dados['pix_osc']);
                $stmt->bindValue(':logradouro', $dados['logradouro_osc']);
                $stmt->bindValue(':numero', $dados['num_ende_osc']);
                $stmt->bindValue(':bairro', $dados['bairro_osc']);
                $stmt->bindValue(':cidade', $dados['cidade_osc']);
                $stmt->bindValue(':estado', $dados['estado_osc']);
                $stmt->bindValue(':descricao', $dados['descricao_osc']);

                $stmt->execute();
                return $this->conn->lastInsertId();

            } 
            catch (PDOException $e) {
                die("ERRO SQL: " . $e->getMessage());
            }
        }

    public function editar($id, $dados) {
        try {
            $sql = "UPDATE instituicao SET 
                    nome_instituicao = :nome, 
                    cnpj = :cnpj, 
                    cep = :cep, 
                    telefone = :telefone, 
                    email = :email, 
                    chave_pix = :pix, 
                    logradouro = :logradouro, 
                    num_ende = :numero, 
                    bairro = :bairro, 
                    cidade = :cidade, 
                    estado = :estado, 
                    descricao = :descricao 
                    WHERE id_instituicao = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':nome', $dados['nome_osc']);
            $stmt->bindValue(':cnpj', $dados['cnpj_osc']);
            $stmt->bindValue(':cep', $dados['cep_osc']);
            $stmt->bindValue(':telefone', $dados['telefone_osc']);
            $stmt->bindValue(':email', $dados['email_osc']);
            $stmt->bindValue(':pix', $dados['pix_osc']);
            $stmt->bindValue(':logradouro', $dados['logradouro_osc']);
            $stmt->bindValue(':numero', $dados['num_ende_osc']);
            $stmt->bindValue(':bairro', $dados['bairro_osc']);
            $stmt->bindValue(':cidade', $dados['cidade_osc']);
            $stmt->bindValue(':estado', $dados['estado_osc']);
            $stmt->bindValue(':descricao', $dados['descricao_osc']);
            $stmt->bindValue(':id', $id);

            return $stmt->execute();
        } catch (\PDOException $e) {
            die("Erro ao atualizar: " . $e->getMessage());
        }
    }

    public function excluir($id) {
        try {
            $sql = "DELETE FROM instituicao WHERE id_instituicao = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (\PDOException $e) {
            die("Erro ao excluir: " . $e->getMessage());
        }
    }

}