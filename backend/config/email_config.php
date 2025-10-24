<?php
/**
 * Configuración de Email
 * 
 * IMPORTANTE: Configura estos valores según tu proveedor de correo
 */

// Configuración SMTP
define('SMTP_HOST', 'smtp.gmail.com');  // Para Gmail
define('SMTP_PORT', 587);                // Puerto TLS (587) o SSL (465)
define('SMTP_SECURE', 'tls');            // 'tls' o 'ssl'
define('SMTP_USERNAME', 'squadzero1234@gmail.com');  // Tu correo
define('SMTP_PASSWORD', 'urrc obhi cykg imyc');  // Contraseña de aplicación de Gmail

// Configuración del remitente
define('EMAIL_FROM', 'squadzero1234@gmail.com');
define('EMAIL_FROM_NAME', 'Squad Zero - Reservas');

// Configuración general
define('EMAIL_CHARSET', 'UTF-8');
define('EMAIL_DEBUG', 0);  // 0 = sin debug, 1 = errores, 2 = mensajes, 3 = todo

/**
 * INSTRUCCIONES PARA GMAIL:
 * 
 * 1. Habilita la verificación en dos pasos en tu cuenta de Google
 * 2. Ve a: https://myaccount.google.com/apppasswords
 * 3. Genera una "Contraseña de aplicación" para "Correo"
 * 4. Usa esa contraseña de 16 caracteres en SMTP_PASSWORD
 * 
 * OTROS PROVEEDORES POPULARES:
 * 
 * Outlook/Hotmail:
 * - SMTP_HOST: smtp-mail.outlook.com
 * - SMTP_PORT: 587
 * - SMTP_SECURE: tls
 * 
 * Yahoo:
 * - SMTP_HOST: smtp.mail.yahoo.com
 * - SMTP_PORT: 587
 * - SMTP_SECURE: tls
 * 
 * Hostinger:
 * - SMTP_HOST: smtp.hostinger.com
 * - SMTP_PORT: 587
 * - SMTP_SECURE: tls
 */
?>