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

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function salvar($dados) {
        try {
            $logradouro = isset($dados['logradouro_osc']) ? $dados['logradouro_osc'] : '';
            $cidade = isset($dados['cidade_osc']) ? $dados['cidade_osc'] : '';
            $estado = isset($dados['estado_osc']) ? $dados['estado_osc'] : '';
            
            $latitude = null;
            $longitude = null;

            if (!empty($cidade) && !empty($estado)) {
                $enderecoCompleto = $logradouro . ', ' . $cidade . ', ' . $estado . ', Brasil';
                $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($enderecoCompleto) . "&limit=1";
                
                $opts = [
                    "http" => [
                        "method" => "GET",
                        "header" => "User-Agent: GrativeBackend/1.0 (breno@pucpr.edu.br)\r\n"
                    ]
                ];
                $context = stream_context_create($opts);
                $response = @file_get_contents($url, false, $context);

                if ($response) {
                    $resultadoGeo = json_decode($response, true);
                    if (!empty($resultadoGeo) && isset($resultadoGeo[0])) {
                        $latitude = $resultadoGeo[0]['lat'];
                        $longitude = $resultadoGeo[0]['lon'];
                    } else {
                        // Backup caso a rua falhe: busca pela cidade
                        $urlBackup = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($cidade . ', ' . $estado . ', Brasil') . "&limit=1";
                        $responseBackup = @file_get_contents($urlBackup, false, $context);
                        if ($responseBackup) {
                            $resultadoBackup = json_decode($responseBackup, true);
                            if (!empty($resultadoBackup) && isset($resultadoBackup[0])) {
                                $latitude = $resultadoBackup[0]['lat'];
                                $longitude = $resultadoBackup[0]['lon'];
                            }
                        }
                    }
                }
            }

            $sql = "INSERT INTO instituicao 
                    (nome_instituicao, cnpj, cep, telefone, email, firebase_uid, logradouro, num_ende, bairro, cidade, estado, descricao, chave_pix, nota, categoria, latitude, longitude) 
                    VALUES 
                    (:nome, :cnpj, :cep, :telefone, :email, :uid, :logradouro, :numero, :bairro, :cidade, :estado, :descricao, :pix, 0, :categoria, :latitude, :longitude)";

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
            $stmt->bindValue(':categoria', $dados['categoria_osc']);
            
            // Vincula os valores calculados pelo backend
            $stmt->bindValue(':latitude', $latitude);
            $stmt->bindValue(':longitude', $longitude);

            $stmt->execute();
            return $this->conn->lastInsertId();

        } 
        catch (PDOException $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(["erro" => "Erro de Banco no Salvar: " . $e->getMessage()]);
            exit;
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

    public function obterMetricasDashboard($id_osc) {
        $sql = "
            SELECT 
                i.trust_score as score,
                SUM(d.quantia) as total_doacoes
            FROM instituicao i
            LEFT JOIN doacao d 
                ON i.id_instituicao = d.fk_id_instituicao 
                AND d.status = 'pagamento efetuado' 
                AND d.data_doacao >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            WHERE i.id_instituicao = :id_instituicao
            GROUP BY i.trust_score
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_instituicao', $id_osc, \PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}