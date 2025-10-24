# 📧 Sistema de Envío de Correos - Squad Zero

## 🚀 Instalación y Configuración

### Paso 1: Descargar PHPMailer

Como no tienes Composer instalado, descarga PHPMailer manualmente:

1. Ve a: [Descargar PHPMailer][phpmailer-download]
2. Descarga el archivo ZIP
3. Descomprime el contenido
4. Copia la carpeta `PHPMailer-master` a `backend/lib/PHPMailer`

La estructura debe quedar así:
```
backend/
├── lib/
│   ├── PHPMailer/
│   │   ├── src/
│   │   │   ├── PHPMailer.php
│   │   │   ├── SMTP.php
│   │   │   └── Exception.php
│   │   └── ...
│   └── EmailService.php
```

### Paso 2: Configurar Gmail para enviar correos

#### Opción A: Usar Gmail (Recomendado para desarrollo)

1. **Habilita la verificación en dos pasos:**
   - Ve a: [Seguridad de Google][google-security]
   - Activa "Verificación en dos pasos"

2. **Genera una contraseña de aplicación:**
   - Ve a: [Contraseñas de aplicación][google-apppasswords]
   - Selecciona "Correo" y "Otro (nombre personalizado)"
   - Escribe "Squad Zero" y haz clic en "Generar"
   - Copia la contraseña de 16 caracteres que aparece

3. **Configura el archivo `backend/config/email_config.php`:**
   ```php
   define('SMTP_HOST', 'smtp.gmail.com');
   define('SMTP_PORT', 587);
   define('SMTP_SECURE', 'tls');
   define('SMTP_USERNAME', 'tu-email@gmail.com');  // Tu correo de Gmail
   define('SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx');  // La contraseña de aplicación
   define('EMAIL_FROM', 'tu-email@gmail.com');
   define('EMAIL_FROM_NAME', 'Squad Zero - Reservas');
   ```

#### Opción B: Usar Outlook/Hotmail

```php
define('SMTP_HOST', 'smtp-mail.outlook.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'tu-email@outlook.com');
define('SMTP_PASSWORD', 'tu-contraseña');
define('EMAIL_FROM', 'tu-email@outlook.com');
```

#### Opción C: Usar un servidor SMTP personalizado (Hostinger, etc.)

```php
define('SMTP_HOST', 'smtp.tu-dominio.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'tu-email@tu-dominio.com');
define('SMTP_PASSWORD', 'tu-contraseña');
define('EMAIL_FROM', 'tu-email@tu-dominio.com');
```

---

## 📝 Uso de la API

### 1. Envío Automático al Crear Reserva

El correo se envía **automáticamente** cuando se crea una reserva exitosamente a través de `api.php`:

```javascript
// Desde tu frontend (reservas.js)
fetch('http://localhost/Proyecto-Squad-Zero/backend/routes/api.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        tour: 'Tour Náutico',
        fecha: '2025-02-15',
        hora: '10:00',
        cantidad_personas: 4
    })
})
.then(response => response.json())
.then(data => {
    console.log(data);
    // Respuesta incluye: mensaje, id, email_enviado
});
```

### 2. Envío Manual de Correo

Si necesitas reenviar un correo de confirmación:

```javascript
fetch('http://localhost/Proyecto-Squad-Zero/backend/routes/apimail.php?action=enviar', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        id_reserva: 123,
        id_usuario: 45
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Correo enviado a:', data.destinatario);
    } else {
        console.error('Error:', data.error);
    }
});
```

### 3. Probar la Configuración

Verifica que tu configuración SMTP esté correcta:

```javascript
fetch('http://localhost/Proyecto-Squad-Zero/backend/routes/apimail.php?action=test')
.then(response => response.json())
.then(data => {
    console.log(data);
    // Muestra la configuración actual (sin contraseñas)
});
```

---

## 🎨 Personalización del Correo

### Modificar el diseño del correo

Edita el método `generarHTMLConfirmacion()` en `backend/lib/EmailService.php`:

```php
private function generarHTMLConfirmacion($reserva, $usuario) {
    // Aquí puedes modificar:
    // - Colores (cambia #667eea, #764ba2, etc.)
    // - Textos
    // - Estructura HTML
    // - Agregar logo de tu empresa
    // - Agregar más información
}
```

### Agregar logo de tu empresa

1. Sube tu logo a `img/logo-email.png`
2. En el HTML del correo, agrega:
   ```html
   <img src="http://tu-dominio.com/img/logo-email.png" alt="Logo" style="max-width: 200px;">
   ```

---

## 🔧 Solución de Problemas

### Error: "SMTP connect() failed"

**Causa:** Credenciales incorrectas o configuración SMTP errónea.

**Solución:**
1. Verifica que SMTP_USERNAME y SMTP_PASSWORD sean correctos
2. Si usas Gmail, asegúrate de usar una contraseña de aplicación
3. Verifica que el puerto sea correcto (587 para TLS, 465 para SSL)

### Error: "Could not instantiate mail function"

**Causa:** PHPMailer no está instalado correctamente.

**Solución:**
1. Verifica que la carpeta `backend/lib/PHPMailer` exista
2. Verifica que contenga las carpetas `src/` con los archivos PHP
3. Descarga nuevamente desde GitHub si es necesario

### El correo no llega

**Posibles causas:**
1. El correo está en spam (revisa la carpeta de spam)
2. El email del usuario no es válido
3. El servidor SMTP está bloqueando el envío

**Solución:**
1. Revisa los logs en `backend/routes/email_errores.log`
2. Activa el modo debug en `email_config.php`: `define('EMAIL_DEBUG', 2);`
3. Verifica que el email del usuario esté correcto en la base de datos

### Error: "Authentication failed"

**Causa:** Contraseña incorrecta o cuenta bloqueada.

**Solución para Gmail:**
1. Asegúrate de usar una contraseña de aplicación, NO tu contraseña normal
2. Verifica que la verificación en dos pasos esté activada
3. Genera una nueva contraseña de aplicación

---

## 📊 Logs y Debugging

### Activar modo debug

En `backend/config/email_config.php`:

```php
define('EMAIL_DEBUG', 2);  // 0=sin debug, 1=errores, 2=mensajes, 3=todo
```

### Ver logs de errores

Los errores se guardan en:
- `backend/routes/email_errores.log`
- `backend/errores.log`

---

## 🔒 Seguridad

### Recomendaciones importantes:

1. **NUNCA subas `email_config.php` a GitHub** con tus credenciales reales
2. Usa variables de entorno en producción
3. Cambia las contraseñas regularmente
4. Usa contraseñas de aplicación, no contraseñas normales
5. Limita el número de correos por hora para evitar spam

### Crear archivo .gitignore

Agrega esto a tu `.gitignore`:
```
backend/config/email_config.php
backend/routes/email_errores.log
backend/errores.log
```

---

## 📱 Ejemplo de Integración Completa

### En tu archivo `js/reservas.js`:

```javascript
async function crearReserva(datosReserva) {
    try {
        const response = await fetch('backend/routes/api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosReserva)
        });
        
        const data = await response.json();
        
        if (data.mensaje === "Reserva agregada") {
            // Mostrar mensaje de éxito
            alert('¡Reserva creada exitosamente!');
            
            if (data.email_enviado) {
                alert('Se ha enviado un correo de confirmación a tu email.');
            } else {
                console.warn('No se pudo enviar el correo de confirmación');
            }
            
            // Redirigir o actualizar la página
            window.location.href = 'page/mis-reservas.html';
        } else {
            alert('Error: ' + data.error);
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear la reserva');
    }
}
```

---

## 🎯 Próximos Pasos

1. ✅ Descarga e instala PHPMailer
2. ✅ Configura tus credenciales SMTP en `email_config.php`
3. ✅ Prueba el endpoint de test: `apimail.php?action=test`
4. ✅ Crea una reserva de prueba para verificar el envío
5. ✅ Personaliza el diseño del correo según tu marca
6. ✅ Implementa en producción con credenciales seguras

---

## 💡 Características Adicionales Sugeridas

### 1. Correo de Cancelación
Puedes agregar un método similar para enviar correos cuando se cancela una reserva.

### 2. Recordatorios Automáticos
Implementa un cron job que envíe recordatorios 24 horas antes de la reserva.

### 3. Correos de Bienvenida
Envía un correo de bienvenida cuando un usuario se registra.

---

## 📞 Soporte

Si tienes problemas:
1. Revisa los logs de error
2. Activa el modo debug
3. Verifica la configuración SMTP
4. Consulta la [documentación de PHPMailer][phpmailer-docs]

---

**¡Listo! Tu sistema de correos está configurado y funcionando.** 🎉

---

<!-- Enlaces -->
[phpmailer-download]: https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip
[phpmailer-docs]: https://github.com/PHPMailer/PHPMailer
[google-security]: https://myaccount.google.com/security
[google-apppasswords]: https://myaccount.google.com/apppasswords