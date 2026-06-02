<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//OSC rotas
require_once __DIR__ . '/../Controllers/OscController.php';
$router->get('/cadastro_osc', 'App\Controllers\OscController@mostrarFormulario');
$router->get('/home_osc', 'App\Controllers\OscController@mostrarHomeOsc');
$router->post('/api/osc/cadastrar', 'App\Controllers\OscController@cadastrar');
$router->get('/editar_osc', 'App\Controllers\OscController@mostrarFormularioEdicao');
$router->get('/api/osc/dados', 'App\Controllers\OscController@obterDados');

$router->post('/api/osc/editar', 'App\Controllers\OscController@atualizar');
$router->post('/api/osc/excluir', 'App\Controllers\OscController@excluir');

// Rotas Gerais
$router->get('/relatorio-doacoes', 'App\Controllers\DonationRelatorioController@getDoacoes');
$router->post('/relatorio/publicar', 'App\Controllers\RelatorioController@publicar');
$router->get('/inserir-documento', 'App\Controllers\InserirDocController@index');
$router->post('/upload-doc', 'App\Controllers\InserirDocController@upload');
$router->get('/listar-docs', 'App\Controllers\InserirDocController@listar');
$router->post('/excluir-doc', 'App\Controllers\InserirDocController@excluir');

// Rotas de caixa-entrada
$router->get('/caixa-entrada', 'App\Controllers\CaixaEntradaController@index'); 
$router->get('/api/notificacoes', 'App\Controllers\CaixaEntradaController@listarAjax'); 
$router->post('/api/notificacoes/ler', 'App\Controllers\CaixaEntradaController@lerAjax'); 

// Rotas de Autenticação
$router->get('/', 'App\Controllers\LoginController@index');
$router->post('/api/login', 'App\Controllers\LoginController@authenticateApi');
$router->get('/logout', 'App\Controllers\LoginController@logout');

// Rotas Doação
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
$router->post('/curtir-publicacao', 'App\Controllers\PublicacaoController@curtir');

// Rotas Mostrar Categorias
$router->get('/buscar-categoria', 'App\Controllers\BuscarController@mostrarBusca');
$router->get('/api/oscs/categoria', 'App\Controllers\BuscarController@filtrarPorCategoria');
$router->get('/buscar-proximidade', 'App\Controllers\ProximidadeController@index');
$router->get('/api/osc/buscar_proximidade', 'App\Controllers\ProximidadeController@buscar');

//? Rotas Publicação
$router->get('/feedOsc', 'App\controllers\PublicacaoController@mostrarFeedOsc');
$router->post('/api/publicacao/criar','App\Controllers\PublicacaoController@fazerPublicacao');
$router->get('/api/feed-osc', 'App\Controllers\PublicacaoController@listarFeed');
$router->post('/api/excluir-publicacao', 'App\Controllers\PublicacaoController@excluirPublicacao');
$router->get('/api/feed-geral', 'App\Controllers\PublicacaoController@listarFeedGlobal');

// Rotas de Comentários
$router->post('/api/comentario/adicionar', 'App\Controllers\PublicacaoController@adicionarComentario');
$router->get('/api/comentario/listar', 'App\Controllers\PublicacaoController@listarComentarios'); 
$router->post('/api/comentario/deletar', 'App\Controllers\PublicacaoController@deletarComentario');
