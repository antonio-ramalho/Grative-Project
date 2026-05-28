<?php

namespace App\Services;

use PDO;

class TrustScoreService {
    private $pdo;

    public function __construct(PDO $db) {
        $this->pdo = $db;
    }

    public function atualizarScoreDaOsc($idInstituicao) {
        $scoreTotal = 0;

        $stmtDocBase = $this->pdo->prepare("SELECT COUNT(*) FROM documentos WHERE instituicao_id = ? AND status = 'aprovado' AND tipo = 'estatuto'");
        $stmtDocBase->execute([$idInstituicao]);
        if ($stmtDocBase->fetchColumn() > 0) {
            $scoreTotal += 50;
        }

        $stmtCert = $this->pdo->prepare("SELECT COUNT(*) FROM documentos WHERE instituicao_id = ? AND status = 'aprovado' AND tipo IN ('certificado', 'parceria')");
        $stmtCert->execute([$idInstituicao]);
        $qtdCertificados = $stmtCert->fetchColumn();
        
        $scoreTotal += min($qtdCertificados * 10, 30);

        try {
            $stmtRel = $this->pdo->prepare("SELECT COUNT(*) FROM relatorio WHERE instituicao_id = ?");
            $stmtRel->execute([$idInstituicao]);
            $qtdRelatorios = $stmtRel->fetchColumn();
            
            $scoreTotal += min($qtdRelatorios * 5, 20);
        } catch (\PDOException $e) {
            $scoreTotal += 0; 
        }

        $stmtUpdate = $this->pdo->prepare("UPDATE instituicao SET trust_score = ? WHERE id_instituicao = ?");
        $stmtUpdate->execute([$scoreTotal, $idInstituicao]);

        return $scoreTotal;
    }
}