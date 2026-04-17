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
$router->get('/', 'App\Controllers\HomeController@index');
$router->get('/relatorio-doacoes', 'DonationRelatorioController@index');
$router->post('/relatorio/publicar', 'App\Controllers\RelatorioController@publicar');

// Rotas de Autenticação
$router->get('/login', 'App\Controllers\LoginController@index');
$router->post('/login', 'App\Controllers\LoginController@authenticate');
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