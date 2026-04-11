<?php
// src/Routes/web.php — definição de rotas
$router->get('/', 'HomeController@index');
$router->get('/relatorio-doacoes', 'DonationReportController@index');

// Rotas com middleware de autenticação
