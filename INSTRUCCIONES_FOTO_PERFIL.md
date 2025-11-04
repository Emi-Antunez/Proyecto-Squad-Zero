# 📸 Instrucciones para Activar Fotos de Perfil

## 🔧 Configuración Necesaria

### 1. Actualizar Base de Datos
Ejecuta el siguiente script SQL en tu base de datos MySQL:

```sql
ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL;
```

O ejecuta el archivo: `backend/database/add_foto_perfil_column.sql`

### 2. Verificar Permisos de Carpeta
Asegúrate de que la carpeta `img/perfiles/` tenga permisos de escritura:

**En Windows (XAMPP):**
- La carpeta ya fue creada automáticamente
- XAMPP generalmente tiene los permisos correctos por defecto

**En Linux/Mac:**
```bash
chmod 777 img/perfiles/
```

### 3. Verificar Configuración PHP
Asegúrate de que tu `php.ini` permita subida de archivos:

```ini
file_uploads = On
upload_max_filesize = 2M
post_max_size = 3M
```

## ✨ Funcionalidades Implementadas

### Para Usuarios:
1. **Registro con Foto de Perfil**
   - Campo opcional en el formulario de registro
   - Preview en tiempo real antes de subir
   - Validación de tipo (JPG, PNG, GIF, WEBP)
   - Validación de tamaño (máximo 2MB)

### Para Administradores:
1. **Visualización en Panel Admin**
   - Las fotos de perfil se muestran en las tarjetas de usuario
   - Si no hay foto, se muestran las iniciales (comportamiento anterior)
   - Diseño circular con borde y sombra

## 🎨 Características Técnicas

### Frontend:
- Preview de imagen antes de subir
- Validación en cliente (tamaño y tipo)
- Interfaz intuitiva con placeholder

### Backend:
- Validación de tipo MIME
- Validación de tamaño de archivo
- Nombres únicos para evitar conflictos
- Almacenamiento en `img/perfiles/`

### Base de Datos:
- Nueva columna `foto_perfil` (VARCHAR 255)
- Permite valores NULL (opcional)

## 📝 Uso

### Para Registrarse con Foto:
1. Ir a la página de registro
2. Click en el círculo de "Foto de Perfil"
3. Seleccionar una imagen (JPG, PNG, GIF o WEBP)
4. Ver el preview
5. Completar el resto del formulario
6. Registrarse

### Para Registrarse sin Foto:
1. Simplemente dejar el campo de foto vacío
2. El sistema mostrará las iniciales en el panel admin

## 🔍 Verificación

Para verificar que todo funciona:

1. **Crear un usuario con foto:**
   - Registra un nuevo usuario con foto de perfil
   - Inicia sesión como admin
   - Ve a "Gestión de Usuarios"
   - Verifica que aparezca la foto

2. **Crear un usuario sin foto:**
   - Registra un nuevo usuario sin foto
   - Verifica que aparezcan las iniciales

## 📂 Estructura de Archivos

```
Proyecto-Squad-Zero/
├── img/
│   └── perfiles/          # Carpeta para fotos de perfil
│       └── (archivos de imágenes)
├── page/
│   └── register.html      # Formulario con campo de foto
├── js/
│   └── usuario.js         # Manejo de FormData
├── backend/
│   ├── controllers/
│   │   └── usuarios.php   # Procesamiento de subida
│   ├── models/
│   │   └── usuario.php    # Modelo actualizado
│   └── database/
│       └── add_foto_perfil_column.sql
└── admin.html             # Visualización de fotos
```

## ⚠️ Notas Importantes

1. Las fotos se guardan con nombres únicos (timestamp + ID único)
2. El tamaño máximo es 2MB
3. Solo se aceptan formatos: JPG, PNG, GIF, WEBP
4. La foto es opcional durante el registro
5. Si no hay foto, se muestran las iniciales del usuario

## 🐛 Solución de Problemas

### "Error al subir la imagen"
- Verifica permisos de la carpeta `img/perfiles/`
- Verifica configuración de `upload_max_filesize` en php.ini

### "Tipo de archivo no permitido"
- Solo usa JPG, PNG, GIF o WEBP
- Verifica que el archivo no esté corrupto

### "La imagen es demasiado grande"
- Reduce el tamaño de la imagen a menos de 2MB
- Usa herramientas de compresión de imágenes

### Las fotos no aparecen en admin
- Verifica que la columna `foto_perfil` exista en la base de datos
- Verifica que las rutas de las imágenes sean correctas
- Revisa la consola del navegador para errores

## 🎉 ¡Listo!

El sistema de fotos de perfil está completamente implementado y listo para usar.