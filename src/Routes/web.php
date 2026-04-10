<?php
// 1. Apresentamos o arquivo do Controller para a aplicação
require_once __DIR__ . '/../Controllers/OscController.php';

// 2. Agora o router sabe quem ele é!
$router->get('/cadastro', 'OscController@mostrarFormulario');