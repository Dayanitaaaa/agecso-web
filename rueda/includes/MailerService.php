<?php

class MailerService {
    
    /**
     * Enviar correo de restablecimiento de contraseña
     */
    public static function sendPasswordReset($toEmail, $userName, $resetLink) {
        $subject = "Recuperar Contraseña - AGECSO Rueda de Negocios";
        $htmlBody = self::getPasswordResetTemplate($userName, $resetLink);
        $plainBody = "Hola " . ($userName ?: 'Usuario') . ",\n\n" .
                     "Has solicitado restablecer tu contraseña en la plataforma AGECSO.\n" .
                     "Haz clic en el siguiente enlace o cópialo en tu navegador para continuar:\n\n" .
                     $resetLink . "\n\n" .
                     "Este enlace expirará en 1 hora.\n\n" .
                     "Si no realizaste esta solicitud, puedes ignorar este mensaje de forma segura.\n\n" .
                     "— El equipo de AGECSO";

        return self::sendMail($toEmail, $subject, $htmlBody, $plainBody);
    }

    /**
     * Motor de envío de correos con soporte para SMTP y fallback con headers avanzados
     */
    public static function sendMail($toEmail, $subject, $htmlContent, $plainContent = '') {
        $toEmail = trim($toEmail);
        if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $fromEmail = 'info@agecso.org';
        $fromName  = 'AGECSO Plataforma';
        $replyTo   = 'info@agecso.org';

        // 1. Intentar envío por SMTP si hay configuración disponible
        $configPath = __DIR__ . '/../config/mail.php';
        if (file_exists($configPath)) {
            $mailConfig = require $configPath;
            if (!empty($mailConfig['smtp_enabled']) && !empty($mailConfig['smtp_host'])) {
                $smtpResult = self::sendViaSmtp($toEmail, $subject, $htmlContent, $mailConfig);
                if ($smtpResult) {
                    return true;
                }
            }
        }

        // 2. Envío optimizado vía PHP mail() con cabeceras MIME completas y envelope sender (-f)
        $boundary = "----=_NextPart_" . md5(uniqid(time()));
        
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$replyTo}\r\n";
        $headers .= "Return-Path: <{$fromEmail}>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";

        // Cuerpo multipart (texto plano + HTML)
        $body  = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($plainContent ?: strip_tags($htmlContent))) . "\r\n";

        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($htmlContent)) . "\r\n";
        $body .= "--{$boundary}--\r\n";

        $encodedSubject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

        // El parámetro -f es crítico en Hostinger/Linux para evitar rechazos SPF
        $additionalParams = "-f {$fromEmail}";

        $sent = @mail($toEmail, $encodedSubject, $body, $headers, $additionalParams);
        if (!$sent) {
            // Intentar sin parámetro adicional por si el servidor local (XAMPP/Windows) no lo admite
            $sent = @mail($toEmail, $encodedSubject, $body, $headers);
        }

        if (!$sent) {
            error_log("[MailerService] Falló el envío de correo a {$toEmail} usando mail()");
        }

        return $sent;
    }

    /**
     * Cliente SMTP ligero en socket nativo PHP sin librerías externas
     */
    private static function sendViaSmtp($toEmail, $subject, $htmlContent, $config) {
        $host     = $config['smtp_host'] ?? 'smtp.hostinger.com';
        $port     = $config['smtp_port'] ?? 465;
        $username = $config['smtp_user'] ?? '';
        $password = $config['smtp_pass'] ?? '';
        $fromEmail = $config['from_email'] ?? 'info@agecso.org';
        $fromName  = $config['from_name'] ?? 'AGECSO';
        $encryption = $config['smtp_secure'] ?? 'ssl'; // 'ssl', 'tls', or ''

        if (empty($username) || empty($password)) {
            return false;
        }

        try {
            $prefix = ($encryption === 'ssl') ? 'ssl://' : '';
            $socket = @fsockopen($prefix . $host, $port, $errno, $errstr, 15);
            if (!$socket) {
                return false;
            }

            $read = fgets($socket, 515);

            fputs($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'agecso.org') . "\r\n");
            $read = fgets($socket, 515);

            if ($encryption === 'tls') {
                fputs($socket, "STARTTLS\r\n");
                $read = fgets($socket, 515);
                stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                fputs($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'agecso.org') . "\r\n");
                $read = fgets($socket, 515);
            }

            fputs($socket, "AUTH LOGIN\r\n");
            $read = fgets($socket, 515);
            fputs($socket, base64_encode($username) . "\r\n");
            $read = fgets($socket, 515);
            fputs($socket, base64_encode($password) . "\r\n");
            $read = fgets($socket, 515);

            if (substr($read, 0, 3) != '235') {
                fclose($socket);
                return false;
            }

            fputs($socket, "MAIL FROM: <{$fromEmail}>\r\n");
            $read = fgets($socket, 515);
            fputs($socket, "RCPT TO: <{$toEmail}>\r\n");
            $read = fgets($socket, 515);

            fputs($socket, "DATA\r\n");
            $read = fgets($socket, 515);

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n";
            $headers .= "To: <{$toEmail}>\r\n";
            $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $headers .= "Date: " . date('r') . "\r\n";

            $messageData = $headers . "\r\n" . $htmlContent . "\r\n.\r\n";
            fputs($socket, $messageData);
            $read = fgets($socket, 515);

            fputs($socket, "QUIT\r\n");
            fclose($socket);

            return substr($read, 0, 3) == '250';
        } catch (Exception $e) {
            error_log("[MailerService SMTP Error] " . $e->getMessage());
            return false;
        }
    }

    /**
     * Plantilla HTML elegante y responsiva para recuperación de contraseña
     */
    private static function getPasswordResetTemplate($userName, $resetLink) {
        $safeName = htmlspecialchars($userName ?: 'Empresario(a)');
        $safeLink = htmlspecialchars($resetLink);
        $dateYear = date('Y');

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - AGECSO</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f4f6f9; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;" cellspacing="0" cellpadding="0">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #002e53 0%, #00a2ff 100%); padding: 35px 30px; text-align: center;">
                            <img src="https://agecso.org/assets/img/AGECSO.jpg" alt="AGECSO Logo" style="height: 65px; width: 65px; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.15); object-fit: cover; margin-bottom: 12px;">
                            <h1 style="color: #ffffff; font-size: 22px; margin: 0; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase;">AGECSO</h1>
                            <p style="color: rgba(255,255,255,0.85); font-size: 13px; margin: 5px 0 0 0; font-weight: 500;">Plataforma Rueda de Negocios</p>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 35px; color: #334155;">
                            <h2 style="color: #0f172a; font-size: 20px; font-weight: 700; margin: 0 0 16px 0;">Hola, {$safeName}</h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #475569; margin: 0 0 24px 0;">
                                Hemos recibido una solicitud para restablecer la contraseña de acceso a tu cuenta en la plataforma de negocios de AGECSO.
                            </p>
                            
                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{$safeLink}" target="_blank" style="display: inline-block; background-color: #00a2ff; color: #ffffff; font-size: 15px; font-weight: 800; text-decoration: none; padding: 15px 38px; border-radius: 50px; box-shadow: 0 6px 20px rgba(0, 162, 255, 0.35); text-transform: uppercase; letter-spacing: 0.5px;">
                                            Restablecer Mi Contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin: 0 0 20px 0;">
                                ⏰ <strong>Nota de seguridad:</strong> Este enlace tiene una validez de <strong>1 hora</strong> por motivos de seguridad.
                            </p>

                            <div style="background-color: #f8fafc; border-left: 4px solid #00a2ff; padding: 14px 18px; border-radius: 8px; margin: 25px 0;">
                                <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.5;">
                                    Si el botón no funciona, copia y pega el siguiente enlace en tu navegador web:<br>
                                    <a href="{$safeLink}" style="color: #00a2ff; word-break: break-all; font-size: 11px; text-decoration: underline;">{$safeLink}</a>
                                </p>
                            </div>

                            <p style="font-size: 13px; color: #94a3b8; line-height: 1.5; margin: 25px 0 0 0;">
                                Si tú no solicitaste este cambio, puedes ignorar este correo con total tranquilidad. Tu contraseña actual no se modificará.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="color: #94a3b8; font-size: 11px; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                AGECSO – Conectamos los nodos del desarrollo económico
                            </p>
                            <p style="color: #cbd5e1; font-size: 11px; margin: 0;">
                                © {$dateYear} Asociación Grupo de Empresarios y Comerciantes de Sabana de Occidente
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
}
