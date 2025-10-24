<?php
/**
 * Script de Prueba para el Sistema de Correos
 * 
 * Ejecuta este archivo directamente en el navegador para probar el envío de correos
 * URL: http://localhost/Proyecto-Squad-Zero/backend/APIMails/test_email.php
 */

// Mostrar errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Correos - Squad Zero</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .section h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .status {
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        pre {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 12px;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .step-number {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧪 Test de Sistema de Correos</h1>
            <p>Squad Zero - Verificación de Configuración</p>
        </div>
        
        <div class="content">
            <?php
            // Test 1: Verificar archivos
            echo '<div class="section">';
            echo '<h2>📁 Test 1: Verificación de Archivos</h2>';
            
            $archivos = [
                '../config/email_config.php' => 'Configuración de email',
                '../lib/EmailService.php' => 'Servicio de email',
                '../lib/PHPMailer/src/PHPMailer.php' => 'PHPMailer',
                '../lib/PHPMailer/src/SMTP.php' => 'SMTP',
                '../lib/PHPMailer/src/Exception.php' => 'Exception'
            ];
            
            $todosExisten = true;
            foreach ($archivos as $archivo => $descripcion) {
                $existe = file_exists(__DIR__ . '/' . $archivo);
                $todosExisten = $todosExisten && $existe;
                
                echo '<div class="status ' . ($existe ? 'success' : 'error') . '">';
                echo ($existe ? '✅' : '❌') . ' ' . $descripcion . ': ';
                echo $existe ? '<strong>Encontrado</strong>' : '<strong>NO ENCONTRADO</strong>';
                echo '</div>';
            }
            
            if (!$todosExisten) {
                echo '<div class="status warning">';
                echo '<strong>⚠️ Acción requerida:</strong> Descarga PHPMailer de ';
                echo '<a href="https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip" target="_blank">aquí</a>';
                echo ' y colócalo en backend/lib/PHPMailer/';
                echo '</div>';
            }
            echo '</div>';
            
            // Test 2: Verificar configuración
            if (file_exists(__DIR__ . '/../config/email_config.php')) {
                echo '<div class="section">';
                echo '<h2>⚙️ Test 2: Configuración SMTP</h2>';
                
                require_once __DIR__ . '/../config/email_config.php';
                
                $configurado = (SMTP_USERNAME !== 'tu-email@gmail.com');
                
                echo '<div class="status ' . ($configurado ? 'success' : 'warning') . '">';
                if ($configurado) {
                    echo '✅ <strong>Configuración detectada</strong><br>';
                    echo 'Host: ' . SMTP_HOST . '<br>';
                    echo 'Puerto: ' . SMTP_PORT . '<br>';
                    echo 'Usuario: ' . SMTP_USERNAME . '<br>';
                    echo 'Remitente: ' . EMAIL_FROM;
                } else {
                    echo '⚠️ <strong>Configuración pendiente</strong><br>';
                    echo 'Debes editar backend/config/email_config.php con tus credenciales SMTP';
                }
                echo '</div>';
                echo '</div>';
            }
            
            // Test 3: Verificar base de datos
            echo '<div class="section">';
            echo '<h2>🗄️ Test 3: Conexión a Base de Datos</h2>';
            
            if (file_exists(__DIR__ . '/../database/conexion.php')) {
                require_once __DIR__ . '/../database/conexion.php';
                
                if ($conn) {
                    echo '<div class="status success">';
                    echo '✅ <strong>Conexión exitosa</strong><br>';
                    echo 'Base de datos: ' . $database;
                    echo '</div>';
                    
                    // Verificar tabla usuarios
                    $result = $conn->query("SHOW TABLES LIKE 'usuarios'");
                    if ($result && $result->num_rows > 0) {
                        echo '<div class="status success">';
                        echo '✅ Tabla "usuarios" encontrada';
                        echo '</div>';
                        
                        // Contar usuarios
                        $result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
                        $row = $result->fetch_assoc();
                        echo '<div class="status info">';
                        echo 'ℹ️ Total de usuarios: ' . $row['total'];
                        echo '</div>';
                    } else {
                        echo '<div class="status error">';
                        echo '❌ Tabla "usuarios" no encontrada';
                        echo '</div>';
                    }
                    
                    // Verificar tabla reservas
                    $result = $conn->query("SHOW TABLES LIKE 'reservas'");
                    if ($result && $result->num_rows > 0) {
                        echo '<div class="status success">';
                        echo '✅ Tabla "reservas" encontrada';
                        echo '</div>';
                    } else {
                        echo '<div class="status error">';
                        echo '❌ Tabla "reservas" no encontrada';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="status error">';
                    echo '❌ <strong>Error de conexión</strong><br>';
                    echo 'Verifica la configuración en backend/database/conexion.php';
                    echo '</div>';
                }
            }
            echo '</div>';
            
            // Test 4: Instrucciones de uso
            echo '<div class="section">';
            echo '<h2>📝 Próximos Pasos</h2>';
            
            echo '<div class="step">';
            echo '<div class="step-number">1</div>';
            echo '<div>Descarga PHPMailer si aún no lo has hecho</div>';
            echo '</div>';
            
            echo '<div class="step">';
            echo '<div class="step-number">2</div>';
            echo '<div>Configura tus credenciales SMTP en <code>backend/config/email_config.php</code></div>';
            echo '</div>';
            
            echo '<div class="step">';
            echo '<div class="step-number">3</div>';
            echo '<div>Crea una reserva de prueba desde tu aplicación</div>';
            echo '</div>';
            
            echo '<div class="step">';
            echo '<div class="step-number">4</div>';
            echo '<div>Verifica que el correo llegue a tu bandeja de entrada</div>';
            echo '</div>';
            
            echo '</div>';
            
            // Ejemplo de uso
            echo '<div class="section">';
            echo '<h2>💻 Ejemplo de Uso desde JavaScript</h2>';
            echo '<pre>';
            echo htmlspecialchars('// Crear reserva (envía correo automáticamente)
fetch("backend/routes/api.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        tour: "Tour Náutico",
        fecha: "2025-02-15",
        hora: "10:00",
        cantidad_personas: 4
    })
})
.then(res => res.json())
.then(data => {
    console.log(data);
    if (data.email_enviado) {
        alert("¡Correo enviado!");
    }
});

// Reenviar correo manualmente
fetch("backend/APIMails/apimail.php?action=enviar", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        id_reserva: 123,
        id_usuario: 45
    })
})
.then(res => res.json())
.then(data => console.log(data));');
            echo '</pre>';
            echo '</div>';
            ?>
            
            <div class="section" style="text-align: center;">
                <h2>📚 Documentación Completa</h2>
                <p>Lee el archivo <strong>INSTRUCCIONES_EMAIL.md</strong> para más detalles</p>
                <br>
                <button onclick="window.location.reload()">🔄 Recargar Tests</button>
            </div>
        </div>
    </div>
</body>
</html>