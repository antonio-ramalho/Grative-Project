<?php

namespace App\Models;

use PDO;
use PDOException;

class DocumentoModel {
    private $pdo;

    public function __construct(PDO $db) {
        $this->pdo = $db;
    }

    public function listarDocumentos($idInstituicao) {
        $sql = "SELECT id, caminho_arquivo, cnpj, status, data_envio, tipo 
                FROM documentos 
                WHERE instituicao_id = :id_instituicao 
                ORDER BY data_envio DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_instituicao', $idInstituicao, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function buscarCaminhoArquivo($idDocumento, $idInstituicao) {
        $sql = "SELECT caminho_arquivo FROM documentos WHERE id = :id AND instituicao_id = :id_instituicao";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $idDocumento, \PDO::PARAM_INT);
        $stmt->bindParam(':id_instituicao', $idInstituicao, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn(); 
    }

    public function excluirDocumento($idDocumento, $idInstituicao) {
        $sql = "DELETE FROM documentos WHERE id = :id AND instituicao_id = :id_instituicao";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $idDocumento, \PDO::PARAM_INT);
        $stmt->bindParam(':id_instituicao', $idInstituicao, \PDO::PARAM_INT);
        
        return $stmt->execute();
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