<?php
//OSC rotas
require_once __DIR__ . '/../Controllers/OscController.php';
$router->get('/cadastro_osc', 'App\Controllers\OscController@mostrarFormulario');
$router->get('/home_osc', 'App\Controllers\OscController@mostrarHomeOsc');
$router->post('/api/osc/cadastrar', 'App\Controllers\OscController@cadastrar');
$router->get('/editar_osc', 'App\Controllers\OscController@mostrarFormularioEdicao');

$router->post('/api/osc/editar', 'App\Controllers\OscController@atualizar');
$router->post('/api/osc/excluir', 'App\Controllers\OscController@excluir');

// Rotas Gerais
$router->get('/', 'App\Controllers\LoginController@index'); 
$router->get('/relatorio-doacoes', 'DonationRelatorioController@index');
$router->post('/relatorio/publicar', 'App\Controllers\RelatorioController@publicar');

// Rotas de Autenticação
$router->get('/login', 'App\Controllers\LoginController@index');
$router->post('/api/login', 'App\Controllers\LoginController@authenticateApi');
$router->get('/logout', 'App\Controllers\LoginController@logout');

// Rotas doação
require_once __DIR__ . '/../Controllers/DonationController.php';
$router->post('/api/doacao/registrar', 'DonationController@registrarDoacao');
$router->get('/fazer-doacao', 'DonationController@mostrarFormulario');
$router->get('/pagamento', 'DonationController@mostrarPagamento');
$router->post('/api/doacao/confirmar', 'DonationController@confirmarDoacao');
$router->get('/obrigado', 'DonationController@mostrarSucesso');
$router->get('/home', 'DonationController@mostrarHome');

$router->get('/api/oscs', 'DonationController@listarOscsApi');
$router->get('/api/pagamento/detalhes', 'DonationController@apiDetalhesPagamento');

// Rotas Doador
require_once __DIR__ . '/../Controllers/DoadorController.php';
$router->get('/cadastro_doador', 'App\Controllers\DoadorController@mostrarFormulario');
$router->post('/api/doador/cadastrar', 'App\Controllers\DoadorController@cadastrar');
$router->get('/home_doador', 'App\Controllers\DoadorController@mostrarHomeDoador');
$router->get('/editar_doador', 'App\Controllers\DoadorController@mostrarFormularioEdicao');
$router->post('/api/doador/editar', 'App\Controllers\DoadorController@atualizar');
$router->post('/api/doador/excluir', 'App\Controllers\DoadorController@excluir');