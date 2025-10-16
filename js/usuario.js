function registrarUsuario() {
    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const gmail = document.getElementById('email').value.trim();
    const usuario = document.getElementById('usuario').value.trim();
    const contrasena = document.getElementById('contrasena').value;
    const confirmar = document.getElementById('confirmar').value;

    if (!nombre || !apellido || !gmail || !usuario || !contrasena || !confirmar) {
        mostrarMensaje("Completa todos los campos.", true);
        return;
    }
    if (!esGmailValido(gmail)) {
        mostrarMensaje("El correo debe ser un Gmail válido.", true);
        return;
    }
    if (contrasena !== confirmar) {
        mostrarMensaje("Las contraseñas no coinciden.", true);
        return;
    }
    if (!esContrasenaSegura(contrasena)) {
        mostrarMensaje("La contraseña debe tener al menos 6 caracteres.", true);
        return;
    }

    fetch('http://localhost/Proyecto-Squad-Zero/backend/routes/api.php?action=register', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nombre, apellido, gmail, usuario, contrasena })
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                mostrarMensaje(data.error, true);
            } else {
                mostrarMensaje("Registro exitoso. Ahora puedes iniciar sesión.", false);
                setTimeout(() => {
                    window.location.href = "login.html";
                }, 2000);
            }
        })
        .catch(() => {
            mostrarMensaje("Error de conexión con el servidor.", true);
        });
}

function mostrarMensaje(msg, esError) {
    let div = document.getElementById('mensaje');
    if (!div) {
        div = document.createElement('div');
        div.id = 'mensaje';
        document.querySelector('form').prepend(div);
    }
    div.textContent = msg;
    div.style.color = esError ? 'red' : 'green';
}

function toggleContrasena() {
    const pass = document.getElementById('contrasena');
    const confirmar = document.getElementById('confirmar');
    const tipo = pass.type === "password" ? "text" : "password";
    pass.type = tipo;
    if (confirmar) confirmar.type = tipo;
}

function esGmailValido(email) {
    return /^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(email);
}

function esContrasenaSegura(contrasena) {
    // Al menos 6 caracteres
    return /^.{6,}$/.test(contrasena);
}

// ...existing code...
function loginUsuario() {
    const usuario = document.getElementById('username').value.trim();
    const contrasena = document.getElementById('contrasena').value;

    if (!usuario || !contrasena) {
        mostrarMensaje("Completa todos los campos.", true);
        return;
    }

    fetch('http://localhost/Proyecto-Squad-Zero/backend/routes/api.php?action=login', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ usuario, contrasena })
    })
        .then(res => res.text())
        .then(text => {
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                mostrarMensaje("Respuesta no válida del servidor.", true);
                return;
            }
            if (data.error) {
                mostrarMensaje(data.error, true);
            } else {
                // Guardar rol en localStorage
                localStorage.setItem('rol', data.rol);
                localStorage.setItem('usuario', data.usuario);
                mostrarMensaje("Bienvenido " + data.usuario, false);
                setTimeout(() => {
                    window.location.href = "../index.html";
                }, 1500);
            }
        })
        .catch(() => {
            mostrarMensaje("Error de conexión con el servidor.", true);
        });
}
// ...existing code...