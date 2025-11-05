<?php
/**
 * Servicio de Email usando PHPMailer
 * 
 * Esta clase maneja el envío de correos electrónicos de forma segura y eficiente
 */

require_once __DIR__ . '/../config/email_config.php';

// Importar PHPMailer (descarga manual)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Si no tienes Composer, descarga PHPMailer de:
// https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
// Y descomprime en backend/lib/PHPMailer/
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

class EmailService {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configurarSMTP();
    }
    
    /**
     * Configura los parámetros SMTP
     */
    private function configurarSMTP() {
        try {
            // Configuración del servidor
            $this->mailer->isSMTP();
            $this->mailer->Host       = SMTP_HOST;
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = SMTP_USERNAME;
            $this->mailer->Password   = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = SMTP_SECURE;
            $this->mailer->Port       = SMTP_PORT;
            $this->mailer->CharSet    = EMAIL_CHARSET;
            
            // Debug (solo para desarrollo)
            $this->mailer->SMTPDebug = EMAIL_DEBUG;
            
            // Remitente por defecto
            $this->mailer->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
            
        } catch (Exception $e) {
            error_log("Error configurando SMTP: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Envía un correo de confirmación de reserva
     * 
     * @param array $reservaData Datos de la reserva
     * @param array $usuarioData Datos del usuario
     * @return bool True si se envió correctamente
     */
    public function enviarConfirmacionReserva($reservaData, $usuarioData) {
        try {
            // Destinatario
            $this->mailer->addAddress($usuarioData['gmail'], $usuarioData['nombre'] . ' ' . $usuarioData['apellido']);
            
            // Contenido del correo
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '✅ Confirmación de Reserva - Tour Náutico Colonia';
            $this->mailer->Body    = $this->generarHTMLConfirmacion($reservaData, $usuarioData);
            $this->mailer->AltBody = $this->generarTextoPlanoConfirmacion($reservaData, $usuarioData);
            
            // Enviar
            $resultado = $this->mailer->send();
            
            // Limpiar destinatarios para el próximo envío
            $this->mailer->clearAddresses();
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error enviando email: " . $this->mailer->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Genera el HTML del correo de confirmación
     */
    private function generarHTMLConfirmacion($reserva, $usuario) {
        $nombreCompleto = htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']);
        $tour = htmlspecialchars($reserva['tour']);
        $fecha = htmlspecialchars($reserva['fecha']);
        $hora = htmlspecialchars($reserva['hora']);
        $personas = htmlspecialchars($reserva['cantidad_personas']);
        
        // Formatear fecha en español
        $fechaObj = new DateTime($fecha);
        $fechaFormateada = $fechaObj->format('d/m/Y');
        
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 0; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h1 style="color: white; margin: 0; font-size: 28px;">Tour Náutico Colonia</h1>
                <p style="color: white; margin: 10px 0 0 0; font-size: 16px;">Reservas y Tours</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="display: inline-block; background-color: #10b981; color: white; padding: 15px 30px; border-radius: 50px; font-size: 18px; font-weight: bold;">
                                    ✅ Reserva Confirmada
                                </div>
                            </div>
                            
                            <h2 style="color: #333; margin-bottom: 20px;">¡Hola, {$nombreCompleto}!</h2>
                            
                            <p style="color: #666; font-size: 16px; line-height: 1.6;">
                                Tu reserva ha sido confirmada exitosamente. A continuación, encontrarás los detalles de tu reserva:
                            </p>
                            
                            <div style="background-color: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin: 30px 0; border-radius: 4px;">
                                <h3 style="color: #667eea; margin-top: 0; margin-bottom: 15px;">Detalles de la Reserva</h3>
                                
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold; width: 40%;">🎫 Tour:</td>
                                        <td style="padding: 10px 0; color: #333;">{$tour}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold;">📅 Fecha:</td>
                                        <td style="padding: 10px 0; color: #333;">{$fechaFormateada}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold;">🕐 Hora:</td>
                                        <td style="padding: 10px 0; color: #333;">{$hora}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold;">👥 Personas:</td>
                                        <td style="padding: 10px 0; color: #333;">{$personas}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 4px; margin: 20px 0;">
                                <p style="margin: 0; color: #856404; font-size: 14px;">
                                    <strong>⚠️ Importante:</strong> Por favor, llega 15 minutos antes de la hora programada. No olvides traer tu documento de identidad.
                                </p>
                            </div>
                            
                            <p style="color: #666; font-size: 14px; line-height: 1.6; margin-top: 30px;">
                                Si necesitas modificar o cancelar tu reserva, por favor contáctanos lo antes posible.
                            </p>
                            
                            <div style="text-align: center; margin-top: 40px;">
                                <p style="color: #999; font-size: 14px; margin: 0;">
                                    ¿Tienes preguntas? Contáctanos en:
                                </p>
                                <p style="color: #667eea; font-size: 16px; font-weight: bold; margin: 10px 0;">
                                    squadzero1234@gmail.com
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px; text-align: center;">
                <p style="color: #999; font-size: 12px; margin: 0;">
                    © 2025 Tour Náutico Colonia. Todos los derechos reservados.
                </p>
                <p style="color: #999; font-size: 12px; margin: 10px 0 0 0;">
                    Este es un correo automático, por favor no respondas a este mensaje.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
    
    /**
     * Genera el texto plano del correo (para clientes que no soportan HTML)
     */
    private function generarTextoPlanoConfirmacion($reserva, $usuario) {
        $nombreCompleto = $usuario['nombre'] . ' ' . $usuario['apellido'];
        $tour = $reserva['tour'];
        $fecha = $reserva['fecha'];
        $hora = $reserva['hora'];
        $personas = $reserva['cantidad_personas'];
        
        $fechaObj = new DateTime($fecha);
        $fechaFormateada = $fechaObj->format('d/m/Y');
        
        return <<<TEXT
TOUR NÁUTICO COLONIA - CONFIRMACIÓN DE RESERVA

¡Hola, {$nombreCompleto}!

Tu reserva ha sido confirmada exitosamente.

DETALLES DE LA RESERVA:
------------------------
Tour: {$tour}
Fecha: {$fechaFormateada}
Hora: {$hora}
Cantidad de personas: {$personas}

IMPORTANTE: Por favor, llega 15 minutos antes de la hora programada. No olvides traer tu documento de identidad.

Si necesitas modificar o cancelar tu reserva, por favor contáctanos lo antes posible.

¿Tienes preguntas? Contáctanos en: squadzero1234@gmail.com

---
© 2025 Tour Náutico Colonia. Todos los derechos reservados.
Este es un correo automático, por favor no respondas a este mensaje.
TEXT;
    }
    
    /**
     * Envía un correo de cancelación de reserva
     * 
     * @param array $reservaData Datos de la reserva cancelada
     * @param array $usuarioData Datos del usuario
     * @param string $motivoCancelacion Motivo de la cancelación
     * @return bool True si se envió correctamente
     */
    public function enviarCorreoCancelacion($reservaData, $usuarioData, $motivoCancelacion = '') {
        try {
            // Destinatario
            $this->mailer->addAddress($usuarioData['gmail'], $usuarioData['nombre'] . ' ' . $usuarioData['apellido']);
            
            // Contenido del correo
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '❌ Reserva Cancelada - Tour Náutico Colonia';
            $this->mailer->Body    = $this->generarHTMLCancelacion($reservaData, $usuarioData, $motivoCancelacion);
            $this->mailer->AltBody = $this->generarTextoPlanoCancelacion($reservaData, $usuarioData, $motivoCancelacion);
            
            // Enviar
            $resultado = $this->mailer->send();
            
            // Limpiar destinatarios para el próximo envío
            $this->mailer->clearAddresses();
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error enviando email de cancelación: " . $this->mailer->ErrorInfo);
            return false;
        }
    }
    
    /**
     * Genera el HTML del correo de cancelación
     */
    private function generarHTMLCancelacion($reserva, $usuario, $motivo) {
        $nombreCompleto = htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']);
        $tour = htmlspecialchars($reserva['tour']);
        $fecha = htmlspecialchars($reserva['fecha']);
        $hora = htmlspecialchars($reserva['hora']);
        $personas = htmlspecialchars($reserva['cantidad_personas']);
        $motivoHTML = htmlspecialchars($motivo);
        
        // Formatear fecha en español
        $fechaObj = new DateTime($fecha);
        $fechaFormateada = $fechaObj->format('d/m/Y');
        
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelación de Reserva</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 0; text-align: center; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <h1 style="color: white; margin: 0; font-size: 28px;">Tour Náutico Colonia</h1>
                <p style="color: white; margin: 10px 0 0 0; font-size: 16px;">Reservas y Tours</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="text-align: center; margin-bottom: 30px;">
                                <div style="display: inline-block; background-color: #dc3545; color: white; padding: 15px 30px; border-radius: 50px; font-size: 18px; font-weight: bold;">
                                    ❌ Reserva Cancelada
                                </div>
                            </div>
                            
                            <h2 style="color: #333; margin-bottom: 20px;">Hola, {$nombreCompleto}</h2>
                            
                            <p style="color: #666; font-size: 16px; line-height: 1.6;">
                                Lamentamos informarte que tu reserva ha sido cancelada. A continuación, encontrarás los detalles:
                            </p>
                            
                            <div style="background-color: #f8f9fa; border-left: 4px solid #dc3545; padding: 20px; margin: 30px 0; border-radius: 4px;">
                                <h3 style="color: #dc3545; margin-top: 0; margin-bottom: 15px;">Detalles de la Reserva Cancelada</h3>
                                
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold; width: 40%;">🎫 Tour:</td>
                                        <td style="padding: 10px 0; color: #333;">{$tour}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold;">📅 Fecha:</td>
                                        <td style="padding: 10px 0; color: #333;">{$fechaFormateada}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold;">🕐 Hora:</td>
                                        <td style="padding: 10px 0; color: #333;">{$hora}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; color: #666; font-weight: bold;">👥 Personas:</td>
                                        <td style="padding: 10px 0; color: #333;">{$personas}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 20px; border-radius: 4px; margin: 20px 0;">
                                <h4 style="margin: 0 0 10px 0; color: #856404;">
                                    <i style="margin-right: 8px;">ℹ️</i>Motivo de la Cancelación:
                                </h4>
                                <p style="margin: 0; color: #856404; font-size: 15px; line-height: 1.6;">
                                    {$motivoHTML}
                                </p>
                            </div>
                            
                            <p style="color: #666; font-size: 14px; line-height: 1.6; margin-top: 30px;">
                                Si tienes alguna pregunta o deseas hacer una nueva reserva, no dudes en contactarnos.
                            </p>
                            
                            <div style="text-align: center; margin-top: 40px;">
                                <p style="color: #999; font-size: 14px; margin: 0;">
                                    ¿Necesitas ayuda? Contáctanos en:
                                </p>
                                <p style="color: #dc3545; font-size: 16px; font-weight: bold; margin: 10px 0;">
                                    squadzero1234@gmail.com
                                </p>
                                <p style="color: #999; font-size: 14px; margin: 20px 0 0 0;">
                                    O por WhatsApp: +598 96 018 202
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px; text-align: center;">
                <p style="color: #999; font-size: 12px; margin: 0;">
                    © 2025 Tour Náutico Colonia. Todos los derechos reservados.
                </p>
                <p style="color: #999; font-size: 12px; margin: 10px 0 0 0;">
                    Este es un correo automático, por favor no respondas a este mensaje.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }
    
    /**
     * Genera el texto plano del correo de cancelación
     */
    private function generarTextoPlanoCancelacion($reserva, $usuario, $motivo) {
        $nombreCompleto = $usuario['nombre'] . ' ' . $usuario['apellido'];
        $tour = $reserva['tour'];
        $fecha = $reserva['fecha'];
        $hora = $reserva['hora'];
        $personas = $reserva['cantidad_personas'];
        
        $fechaObj = new DateTime($fecha);
        $fechaFormateada = $fechaObj->format('d/m/Y');
        
        return <<<TEXT
TOUR NÁUTICO COLONIA - RESERVA CANCELADA

Hola, {$nombreCompleto}

Lamentamos informarte que tu reserva ha sido cancelada.

DETALLES DE LA RESERVA CANCELADA:
----------------------------------
Tour: {$tour}
Fecha: {$fechaFormateada}
Hora: {$hora}
Cantidad de personas: {$personas}

MOTIVO DE LA CANCELACIÓN:
{$motivo}

Si tienes alguna pregunta o deseas hacer una nueva reserva, no dudes en contactarnos.

¿Necesitas ayuda? Contáctanos en: squadzero1234@gmail.com
O por WhatsApp: +598 96 018 202

---
© 2025 Tour Náutico Colonia. Todos los derechos reservados.
Este es un correo automático, por favor no respondas a este mensaje.
TEXT;
    }
}
?>