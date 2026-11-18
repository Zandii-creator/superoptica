<?php

/**
 * index.php de la carpeta publicada en cPanel: /home/USUARIO/public_html/superoptica.
 *
 * La aplicacion vive fuera de public_html, en /home/USUARIO/superoptica-app, por eso
 * las rutas suben dos niveles. El script scripts/build-deploy.ps1 copia este archivo
 * como index.php dentro del zip de la carpeta publica; no se edita en el servidor.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../../superoptica-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../../superoptica-app/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../../superoptica-app/bootstrap/app.php';

$app->handleRequest(Request::capture());
