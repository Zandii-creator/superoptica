<?php

/**
 * Router para el servidor embebido de PHP en desarrollo.
 *
 * Uso: php -S 127.0.0.1:8000 -t public server.php
 *
 * Sirve los archivos existentes de public/ y entrega el resto a Laravel,
 * igual que Apache con el document root en public/.
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path !== '/' && is_file(__DIR__.'/public'.$path)) {
    return false;
}

require __DIR__.'/public/index.php';
