# 🌊 Gestión de Reservas - Tours en Barco

Sistema web que permite gestionar reservas de tours turísticos en barco. Los usuarios pueden registrarse, iniciar sesión y reservar tours. Los administradores tienen acceso a la gestión completa de reservas y usuarios.

---

## 📚 Tabla de Contenido

- [✨ Características](#-características)
- [📁 Estructura de Carpetas](#-estructura-de-carpetas)
- [⚙️ Instalación y Configuración](#️-instalación-y-configuración)
- [👥 Uso](#-uso)
- [📡 API](#-api)
- [✅ Requisitos](#-requisitos)
- [🙌 Créditos](#-créditos)
- [📝 Notas](#-notas)

---

## ✨ Características

- Registro e inicio de sesión con roles (`usuario` y `admin`)
- Reserva de tours en barco: selección de tour, fecha, hora y cantidad de personas
- Visualización y eliminación de reservas realizadas
- Panel administrativo para gestión de usuarios y reservas
- Interfaz moderna y responsive con Bootstrap
- Seguridad: cada reserva se asocia al usuario autenticado

---

## 📁 Estructura de Carpetas

Proyecto-Squad-Zero/
│
├── backend/
│ ├── controllers/
│ │ ├── reservas.php
│ │ └── usuarios.php
│ ├── database/
│ │ └── conexion.php
│ ├── models/
│ │ ├── reserva.php
│ │ └── usuario.php
│ └── routes/
│ └── api.php
│
├── js/
│ └── reservas.js
│
├── style/
│ └── style.css
│
├── index.html
└── page/
├── login.html
└── register.html


---

## ⚙️ Instalación y Configuración

1. **Clona este repositorio o copia los archivos a tu servidor local (se recomienda XAMPP).**
2. **Importa la base de datos:**  
   Abre `phpMyAdmin` y usa el archivo `registro_usuarios.sql` para crear las tablas `usuarios` y `reservas`.
3. **Configura la conexión a la base de datos:**  
   Edita `backend/database/conexion.php` con tus credenciales (`usuario`, `contraseña` y `nombre de base de datos`).
4. **Inicia Apache y MySQL desde XAMPP.**
5. **Accede a la aplicación desde tu navegador:**  
   - Página principal: [http://localhost/Proyecto-Squad-Zero/index.html](http://localhost/Proyecto-Squad-Zero/index.html)  
   - Login: [http://localhost/Proyecto-Squad-Zero/page/login.html](http://localhost/Proyecto-Squad-Zero/page/login.html)  
   - Registro: [http://localhost/Proyecto-Squad-Zero/page/register.html](http://localhost/Proyecto-Squad-Zero/page/register.html)

---

## 👥 Uso

- **Registro:**  
  Los nuevos usuarios pueden registrarse desde la página de registro.
- **Login:**  
  El usuario inicia sesión y accede a la funcionalidad de reservas.
- **Reservas:**  
  Usuarios autenticados pueden crear, visualizar y cancelar sus reservas.  
  Los administradores pueden ver y gestionar todas las reservas del sistema.
- **Cerrar sesión:**  
  Disponible en la barra de navegación.

---

## 📡 API

La API está ubicada en `backend/routes/api.php` y expone las siguientes rutas:

- `action=login` (POST): Login de usuario
- `action=register` (POST): Registro de nuevo usuario
- `action=usuarios` (GET): Listado de usuarios (admin)
- Reservas:
  - `GET`: Obtener reservas (por usuario o todas si es admin)
  - `POST`: Crear nueva reserva
  - `DELETE`: Eliminar reserva existente

---

## ✅ Requisitos

- PHP 7.4 o superior  
- MySQL / MariaDB  
- XAMPP, WAMP o servidor web similar  
- Navegador web moderno (Chrome, Firefox, Edge, etc.)

---

## 🙌 Créditos

- Proyecto desarrollado por **[Tu Nombre o Equipo]**
- Tecnologías utilizadas: **PHP**, **MySQL**, **JavaScript**, **Bootstrap**, **FontAwesome**, **Google Fonts**

---

## 📝 Notas

- Si tienes problemas de conexión, verifica:
  - La ruta de la API en tus archivos `.js`
  - La configuración en `conexion.php`
- La base de datos debe llamarse `registro_usuarios` (o edita el nombre en tu archivo de conexión)
- Los datos de usuarios y reservas se almacenan en dicha base de datos
- Para soporte o sugerencias, contacta a: **[tu_email@ejemplo.com]**

---

