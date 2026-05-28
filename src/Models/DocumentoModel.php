<?php

namespace App\Models;

use PDO;
use PDOException;

class DocumentoModel {
    private $pdo;

    public function __construct(PDO $db) {
        $this->pdo = $db;
    }

    public function salvarDocumento($idInstituicao, $caminhoArquivo, $cnpj, $tipo_doc) {
        $sql = "INSERT INTO documentos (instituicao_id, caminho_arquivo, cnpj, status, data_envio, tipo) 
                VALUES (:id_instituicao, :caminho, :cnpj, 'aprovado', NOW(), :tipo)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            
            $cnpjValor = $cnpj ? $cnpj : 'Não identificado';

            $stmt->bindParam(':id_instituicao', $idInstituicao, PDO::PARAM_INT);
            $stmt->bindParam(':caminho', $caminhoArquivo);
            $stmt->bindParam(':cnpj', $cnpjValor);
            $stmt->bindParam(':tipo', $tipo_doc);
            
            return $stmt->execute();
        } 
        catch (PDOException $e) {
            error_log("Erro ao salvar documento validado: " . $e->getMessage());
            return false;
        }
    }
}