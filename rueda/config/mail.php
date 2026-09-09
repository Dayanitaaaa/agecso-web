<?php

/**
 * Configuración de correo para AGECSO
 * Si dispones de credenciales SMTP de tu hosting (Hostinger / cPanel / Titan),
 * puedes activar 'smtp_enabled' => true y colocar el usuario y contraseña.
 */

return [
    'smtp_enabled' => false, // Cambiar a true si deseas usar SMTP autenticado
    'smtp_host'    => 'smtp.hostinger.com',
    'smtp_port'    => 465,                  // 465 para SSL o 587 para TLS
    'smtp_secure'  => 'ssl',                // 'ssl' o 'tls'
    'smtp_user'    => 'info@agecso.org',    // Tu correo corporativo completo
    'smtp_pass'    => '',                   // Contraseña del correo corporativo
    'from_email'   => 'info@agecso.org',
    'from_name'    => 'AGECSO Rueda de Negocios',
    'reply_to'     => 'info@agecso.org'
];
