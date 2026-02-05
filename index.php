<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/app.php';

date_default_timezone_set('America/Sao_Paulo');

use App\Core\Router;
use App\Core\Request;
use App\Core\Session;

// Start session
Session::start();

// Error handling
if (config('app.debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

$router = new Router();
$request = new Request();

// API endpoint for N8N to generate tokens
$router->post('/api/generate-token', 'ApiController@generateToken');

// Token-based form routes (client access)
$router->get('/checkin', 'CheckInController@show');
$router->post('/checkin', 'CheckInController@submit');
$router->get('/checkout', 'CheckOutController@show');
$router->post('/checkout', 'CheckOutController@submit');

// Home
$router->get('/', 'ApiController@home');

$router->dispatch($request);
