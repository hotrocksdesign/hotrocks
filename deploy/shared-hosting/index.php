<?php

/**
 * Variante de public/index.php para hosting compartido donde NO se puede
 * cambiar el document root (queda fijo en public_html).
 *
 * Estructura esperada en el servidor:
 *
 *   /home/tu-usuario/
 *     ├── hotrocks-app/        <- todo el proyecto Laravel (subido por fuera de public_html)
 *     │     ├── app/
 *     │     ├── vendor/
 *     │     ├── bootstrap/
 *     │     ├── storage/
 *     │     └── ...
 *     └── public_html/         <- document root real del hosting
 *           ├── index.php      <- ESTE archivo
 *           ├── .htaccess      <- copiado de public/.htaccess (sin cambios)
 *           └── ...            <- el resto del contenido de public/ (favicon, robots.txt, etc.)
 *
 * Si tu carpeta del proyecto se llama distinto a "hotrocks-app", cambiá el
 * valor de $appPath más abajo.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$appPath = __DIR__.'/../hotrocks-app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appPath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appPath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $appPath.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
