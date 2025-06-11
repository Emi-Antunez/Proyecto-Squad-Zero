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
        mostrarMensaje("La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.", true);
        return;
    }

    fetch('http://localhost/PROYECTO-SQUAD-ZERO/backend/routes/api.php?action=register', {
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
    // Al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(contrasena);
}

function loginUsuario() {
    const usuario = document.getElementById('username').value.trim();
    const contrasena = document.getElementById('contrasena').value;

    if (!usuario || !contrasena) {
        mostrarMensaje("Completa todos los campos.", true);
        return;
    }

    fetch('http://localhost/PROYECTO-SQUAD-ZERO/backend/routes/api.php?action=login', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ usuario, contrasena })
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if (data.error) {
            mostrarMensaje(data.error, true);
        } else {
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

    fetch('http://localhost/PROYECTO-SQUAD-ZERO/backend/routes/api.php?action=login', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ usuario, contrasena })
    })
    .then(res => res.json())
    .then(data => {
        console.log(data); // <-- Agrega esto para ver la respuesta
        if (data.error) {
            mostrarMensaje(data.error, true);
        } else {
            mostrarMensaje("Bienvenido " + data.usuario, false);
            setTimeout(() => {
                window.location.href = "../index.html";
            }, 1500);
        }
    })
    .catch(() => {
        mostrarMensaje("Error de conexión con el servidor.", true);
    });
