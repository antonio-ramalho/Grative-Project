<?php
//OSC rotas
require_once __DIR__ . '/../Controllers/OscController.php';
$router->get('/cadastro', 'OscController@mostrarFormulario');

// src/Routes/web.php — definição de rotas
$router->get('/', 'HomeController@index');
$router->get('/relatorio-doacoes', 'DonationReportController@index');

$router->post('/api/osc/cadastrar', 'OscController@cadastrar');

// Rotas com middleware de autenticação
