<?php
/**
 * Configuración JWT para autenticación API
 * 
 * IMPORTANTE: En producción, mover JWT_SECRET a variables de entorno
 * y asegurar que este archivo esté fuera del webroot o protegido.
 */

// Generar un secret fuerte (mínimo 32 bytes) - cambiar en producción
// Para generar: openssl rand -base64 32
if (!defined('JWT_SECRET')) {
    define('JWT_SECRET', 'P941kX3aAfm62dWBNkwOgVwwpOOvJkGpiL6Ad3CpvLU=');
}

// Algoritmo de firma (HS256 es el estándar recomendado)
if (!defined('JWT_ALGORITHM')) {
    define('JWT_ALGORITHM', 'HS256');
}

// Issuer (identifica tu aplicación)
if (!defined('JWT_ISSUER')) {
    define('JWT_ISSUER', 'agecso.app');
}

// Tiempo de expiración del token (en segundos)
// 1 hora = 3600 segundos
if (!defined('JWT_EXPIRATION')) {
    define('JWT_EXPIRATION', 3600);
}

// Tiempo de tolerancia para clock skew (segundos)
if (!defined('JWT_LEEWAY')) {
    define('JWT_LEEWAY', 60);
}
