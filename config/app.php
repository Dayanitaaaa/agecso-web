<?php

const APP_NAME = 'AGECSO';

// Detección automática de entorno (Local XAMPP vs Producción)
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1']) || strpos($_SERVER['HTTP_HOST'] ?? '', '192.168.') !== false;
$inSubfolder = strpos($_SERVER['REQUEST_URI'] ?? '', '/AGECSO-web') !== false;

if ($isLocal) {
    $baseUri = $inSubfolder ? '/AGECSO-web' : '';
    define('APP_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $baseUri . '/public');
    define('BUSINESS_PLATFORM_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $baseUri . '/rueda/public');
} else {
    define('APP_URL', 'https://agecso.org');
    define('BUSINESS_PLATFORM_URL', 'https://rueda.agecso.org');
}
