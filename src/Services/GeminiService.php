<?php

namespace App\Services;

class GeminiService {
    private $apiKey;
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

    public function __construct() {
        $caminhoEnv = __DIR__ . '/../../.env'; 
        
        $this->apiKey = ''; 

        if (file_exists($caminhoEnv)) {
            $linhas = file($caminhoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($linhas as $linha) {
                if (strpos(trim($linha), '#') === 0) continue; 

                $partes = explode('=', $linha, 2);
                
                if (count($partes) === 2) {
                    $chave = trim($partes[0]);
                    
                    if ($chave === 'GEMINI_API_KEY') {
                        $this->apiKey = trim(trim($partes[1]), '"\'');
                        break;
                    }
                }
            }
        } else {
            error_log("ERRO: Arquivo .env não encontrado na raiz do projeto!");
        }
    }

    public function analisarDocumento($caminhoArquivo, $mimeType) {
        $arquivoBase64 = base64_encode(file_get_contents($caminhoArquivo));

        $prompt = "Você é um recrutador de RH analisando um currículo. Analise o documento anexo. 
        Critérios de aprovação:
        1. Deve conter o nome de uma pessoa.
        2. Deve conter alguma forma de contato (email ou telefone).
        3. Deve listar algum histórico de educação ou experiência profissional.
        
        Retorne APENAS um JSON estrito no seguinte formato, sem formatação markdown:
        {\"valido\": true, \"cnpj\": \"Nome da pessoa encontrado\", \"motivo\": \"vazio se valido, ou o erro encontrado\"}";

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt],
                        [
                            "inlineData" => [
                                "mimeType" => $mimeType,
                                "data" => $arquivoBase64
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->fazerRequisicao($payload);
    }

    private function fazerRequisicao($payload) {
        $ch = curl_init($this->apiUrl . "?key=" . $this->apiKey);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $resposta = curl_exec($ch);
        $erroCurl = curl_error($ch); 
        curl_close($ch);

        if ($resposta === false) {
            return ["valido" => false, "motivo" => "Erro interno do servidor (cURL): " . $erroCurl];
        }

        $dados = json_decode($resposta, true);

        if (isset($dados['error'])) {
            return ["valido" => false, "motivo" => "O Google recusou: " . $dados['error']['message']];
        }

        if (isset($dados['candidates'][0]['content']['parts'][0]['text'])) {
            $textoIa = $dados['candidates'][0]['content']['parts'][0]['text'];
            
            $textoIa = str_replace(['```json', '```'], '', $textoIa);
            $jsonDecodificado = json_decode(trim($textoIa), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                 return ["valido" => false, "motivo" => "IA respondeu fora do padrão. Resposta bruta: " . $textoIa];
            }
            
            return $jsonDecodificado;
        }

        return ["valido" => false, "motivo" => "Estrutura de resposta inesperada da API."];
    }
}