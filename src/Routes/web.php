<?php

// src/Routes/web.php — definição de rotas
$router->get('/', 'HomeController@index');
$router->get('/animais', 'AnimalController@lista');
$router->get('/animais/{id}', 'AnimalController@detalhe');
$router->post('/animais/salvar', 'AnimalController@salvar');
$router->put('/animais/{id}', 'AnimalController@atualizar');
$router->delete('/animais/{id}', 'AnimalController@deletar');

// Rotas com middleware de autenticação
$router->get('/admin', 'AdminController@index')->middleware('auth');
$router->get('/perfil', 'PerfilController@show')->middleware('auth');