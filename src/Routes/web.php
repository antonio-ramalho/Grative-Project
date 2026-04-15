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
$router->get('/relatorio-doacoes', 'App\Controllers\DonationReportController@index');
$router->post('/relatorio/publicar', 'App\Controllers\RelatorioController@publicar');

// Rotas de Autenticação
$router->get('/login', 'App\Controllers\LoginController@index');
$router->post('/login', 'App\Controllers\LoginController@authenticate');
$router->get('/logout', 'App\Controllers\LoginController@logout');

// Rotas com middleware de autenticação
