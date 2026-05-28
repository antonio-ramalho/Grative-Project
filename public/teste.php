<?php
// Usando a chave que você me mostrou anteriormente
$apiKey = "AIzaSyAeGfISJhgTQByB_5LcsRI7EqcH7f7TyZ8"; 
$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora SSL no XAMPP

$resposta = curl_exec($ch);
curl_close($ch);

$dados = json_decode($resposta, true);

echo "<h1>Modelos Suportados por esta Chave de API:</h1>";
echo "<ul>";

if (isset($dados['models'])) {
    foreach ($dados['models'] as $modelo) {
        // Filtra apenas os que suportam "generateContent" (que é o que precisamos)
        $metodos = $modelo['supportedGenerationMethods'] ?? [];
        if (in_array('generateContent', $metodos)) {
            echo "<li><strong>" . $modelo['name'] . "</strong></li>";
        }
    }
} else {
    echo "Erro ao buscar modelos: " . $resposta;
}
echo "</ul>";
?>