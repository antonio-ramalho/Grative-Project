<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helpers/functions.php';
require_once __DIR__ . '/../src/Core/Router.php';

require_once __DIR__ . '/../src/Controllers/DonationController.php';
require_once __DIR__ . '/../src/Controllers/DonationReportController.php';
require_once __DIR__ . '/../src/Models/DonationModel.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD']);

$router = new Router();

require __DIR__ . '/../src/Routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);