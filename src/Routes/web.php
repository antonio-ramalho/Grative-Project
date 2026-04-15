<?php
//OSC rotas
require_once __DIR__ . '/../Controllers/OscController.php';
$router->get('/cadastro', 'OscController@mostrarFormulario');

// src/Routes/web.php — definição de rotas
$router->get('/', 'HomeController@index');
$router->get('/relatorio-doacoes', 'DonationReportController@index');
$router->post('/relatorio/publicar', 'App\Controllers\RelatorioController@publicar');

$router->post('/api/osc/cadastrar', 'OscController@cadastrar');

// Rotas doação
require_once __DIR__ . '/../Controllers/DonationController.php';
$router->post('/api/doacao/registrar', 'DonationController@registrarDoacao');
$router->get('/fazer-doacao', 'DonationController@mostrarFormulario');
$router->get('/pagamento', 'DonationController@mostrarPagamento');
$router->post('/api/doacao/confirmar', 'DonationController@confirmarDoacao');
$router->get('/obrigado', 'DonationController@mostrarSucesso');