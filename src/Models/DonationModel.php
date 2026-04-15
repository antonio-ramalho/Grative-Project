<?php
class DonationModel {
    private $pdo;
    
    public function __construct($pdo) { 
        $this->pdo = $pdo; 
    }


    public function salvar($dados) {
        try {
            // Comando SQL para inserir a doação como "pendente"
            $sql = "INSERT INTO doacao 
                    (fk_id_instituicao, fk_id_usuario, quantia, data_doacao, status, mensagem, forma_pagamento, metodo_pagamento) 
                    VALUES 
                    (:fk_id_inst, :fk_id_user, :quantia, NOW(), 'pendente', :mensagem, 'Pix', 'Pix')";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':fk_id_inst', $dados['id_instituicao']); 
            $stmt->bindValue(':fk_id_user', $dados['id_doador']);
            $stmt->bindValue(':quantia', $dados['valor']);
            $stmt->bindValue(':mensagem', $dados['mensagem']);
            
            $stmt->execute();

            // Retorna o número (ID) da doação que acabou de ser criada
            return $this->pdo->lastInsertId(); 

        } catch (PDOException $e) {
            throw new Exception("Erro no SQL ao salvar: " . $e->getMessage());
        }
    }

    // Atualiza o status da doação para "pago"
    public function confirmarPagamento($id) {
        try {
            $sql = "UPDATE doacao SET status = 'pagamento efetuado' WHERE id_doacao = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro no SQL ao confirmar: " . $e->getMessage());
        }
    }
}