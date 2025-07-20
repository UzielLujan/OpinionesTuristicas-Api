<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

error_log('DEBUG: index.php - Start');

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

error_log('DEBUG: index.php - Before Autoload');
// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

error_log('DEBUG: index.php - Before App Bootstrap');
// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

error_log('DEBUG: index.php - Before Request Handle');
$app->handleRequest(Request::capture());

error_log('DEBUG: index.php - End');
