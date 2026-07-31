<?php
/**
 * Servicio JWT simple compatible con HS256
 * Implementación sin librerías externas (nativo PHP)
 */

require_once __DIR__ . '/../config/jwt.php';

class JwtService {
    
    /**
     * Genera un token JWT
     * 
     * @param array $payload Datos a incluir en el token (sub, role, etc.)
     * @return string Token JWT
     */
    public static function generate(array $payload): string {
        // Header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => JWT_ALGORITHM
        ]);
        
        // Payload con claims estándar
        $time = time();
        $payload = array_merge([
            'iss' => JWT_ISSUER,           // Issuer
            'iat' => $time,                // Issued at
            'exp' => $time + JWT_EXPIRATION, // Expiration
            'nbf' => $time                 // Not before
        ], $payload);
        
        $payloadJson = json_encode($payload);
        
        // Codificar en Base64Url
        $headerEncoded = self::base64UrlEncode($header);
        $payloadEncoded = self::base64UrlEncode($payloadJson);
        
        // Firma
        $signature = hash_hmac('sha256', 
            "$headerEncoded.$payloadEncoded", 
            JWT_SECRET, 
            true
        );
        $signatureEncoded = self::base64UrlEncode($signature);
        
        // Token completo
        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }
    
    /**
     * Valida y decodifica un token JWT
     * 
     * @param string $token Token JWT
     * @return array|null Payload decodificado o null si es inválido
     */
    public static function validate(string $token): ?array {
        // Separar partes
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        
        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;
        
        // Verificar firma
        $signature = self::base64UrlDecode($signatureEncoded);
        $expectedSignature = hash_hmac('sha256', 
            "$headerEncoded.$payloadEncoded", 
            JWT_SECRET, 
            true
        );
        
        // Comparación segura contra timing attacks
        if (!hash_equals($expectedSignature, $signature)) {
            error_log('[JWT] Firma inválida');
            return null;
        }
        
        // Decodificar payload
        $payloadJson = self::base64UrlDecode($payloadEncoded);
        $payload = json_decode($payloadJson, true);
        
        if (!$payload) {
            return null;
        }
        
        // Verificar expiración con leeway
        $now = time();
        $leeway = JWT_LEEWAY;
        
        if (isset($payload['exp']) && ($now - $leeway) > $payload['exp']) {
            error_log('[JWT] Token expirado');
            return null;
        }
        
        // Verificar not before
        if (isset($payload['nbf']) && ($now + $leeway) < $payload['nbf']) {
            error_log('[JWT] Token aún no válido (nbf)');
            return null;
        }
        
        // Verificar issuer
        if (isset($payload['iss']) && $payload['iss'] !== JWT_ISSUER) {
            error_log('[JWT] Issuer inválido');
            return null;
        }
        
        return $payload;
    }
    
    /**
     * Extrae el token del header Authorization
     * 
     * @return string|null Token o null si no existe
     */
    public static function extractFromHeader(): ?string {
        $headers = [];
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        }
        
        // Buscar header Authorization (case-insensitive)
        $authHeader = null;
        foreach ($headers as $name => $value) {
            if (strcasecmp($name, 'Authorization') === 0) {
                $authHeader = $value;
                break;
            }
        }
        
        if (!$authHeader) {
            // Fallback: leer desde $_SERVER
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }
        }
        
        if (!$authHeader) {
            return null;
        }
        
        // Formato: "Bearer <token>"
        if (preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
            return trim($matches[1]);
        }
        
        return null;
    }
    
    /**
     * Codifica en Base64Url (RFC 4648)
     */
    private static function base64UrlEncode(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Decodifica de Base64Url
     */
    private static function base64UrlDecode(string $data): string {
        // Restaurar padding si es necesario
        $padding = 4 - (strlen($data) % 4);
        if ($padding !== 4) {
            $data .= str_repeat('=', $padding);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
