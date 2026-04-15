<?php
//OSC rotas
require_once __DIR__ . '/../Controllers/OscController.php';
$router->get('/cadastro_osc', 'OscController@mostrarFormulario');
$router->get('/home_osc', 'OscController@mostrarHomeOsc');

// src/Routes/web.php — definição de rotas
$router->get('/', 'HomeController@index');
$router->get('/relatorio-doacoes', 'DonationReportController@index');
$router->post('/relatorio/publicar', 'App\Controllers\RelatorioController@publicar');

$router->post('/api/osc/cadastrar', 'OscController@cadastrar');

// Rotas com middleware de autenticação
