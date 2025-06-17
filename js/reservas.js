const API_URL = "http://localhost/PROYECTO-SQUAD-ZERO/backend/routes/api.php";

// Obtener todas las reservas (GET)
function listarReservas() {
  fetch(API_URL)
    .then(res => res.json())
    .then(data => {
      mostrarTablaReservas(data);
    })
    .catch(err => mostrarError("Error al obtener reservas: " + err));
}

// Mostrar reservas en el HTML
function mostrarTablaReservas(reservas) {
  const container = document.getElementById('reservasContainer');
  if (!Array.isArray(reservas) || reservas.length === 0) {
    container.innerHTML = '<p>No hay reservas para mostrar.</p>';
    return;
  }
  let html = '<div style="display: flex; flex-wrap: wrap; gap: 16px;">';
  reservas.forEach(r => {
    html += `
      <div style="border:1px solid #ccc; border-radius:8px; padding:16px; width:240px; box-shadow:2px 2px 8px #eee;">
        <h3>${r.tour}</h3>
        <p><strong>Usuario:</strong> ${r.nombre} ${r.apellido}</p>
        <p><strong>Fecha:</strong> ${r.fecha}</p>
        <p><strong>Hora:</strong> ${r.hora}</p>
        <p><strong>Personas:</strong> ${r.cantidad_personas}</p>
        <button onclick="cargarReservaEnFormulario(${r.id})">Modificar</button>
        <button onclick="eliminarReserva(${r.id})">Eliminar</button>
      </div>
    `;
  });
  html += '</div>';
  container.innerHTML = html;
}

// Agregar una reserva (POST)
function agregarReserva(tour, fecha, hora, cantidad_personas) {
  fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ tour, fecha, hora, cantidad_personas })
  })
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        mostrarError("Error al agregar reserva: " + data.error);
      } else {
        listarReservas();
      }
    })
    .catch(err => mostrarError("Error al agregar reserva: " + err));
}

// Modificar una reserva (PUT)
function modificarReserva(id, tour, fecha, hora, cantidad_personas) {
  fetch(API_URL, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, tour, fecha, hora, cantidad_personas })
  })
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        mostrarError("Error al modificar reserva: " + data.error);
      } else {
        listarReservas();
      }
    })
    .catch(err => mostrarError("Error al modificar reserva: " + err));
}

// Eliminar una reserva (DELETE)
function eliminarReserva(id) {
  fetch(`${API_URL}?id=${id}`, {
    method: "DELETE"
  })
    .then(res => res.json())
    .then(data => {
      listarReservas();
    })
    .catch(err => mostrarError("Error al eliminar reserva: " + err));
}

// Cargar reserva en formulario para modificar
function cargarReservaEnFormulario(id) {
  fetch(`${API_URL}?id=${id}`)
    .then(res => res.json())
    .then(reserva => {
      document.getElementById('reservaId').value = reserva.id;
      document.getElementById('tour').value = reserva.tour;
      document.getElementById('fecha').value = reserva.fecha;
      document.getElementById('hora').value = reserva.hora;
      document.getElementById('cantidadPersonas').value = reserva.cantidad_personas;
    })
    .catch(err => mostrarError("Error al cargar reserva: " + err));
}

// Guardar reserva: agrega o modifica según si hay ID
function guardarReservaDesdeFormulario() {
  document.getElementById('output').textContent = "";
  const id = document.getElementById('reservaId').value;
  const tour = document.getElementById('tour').value;
  const fecha = document.getElementById('fecha').value;
  const hora = document.getElementById('hora').value;
  const cantidad_personas = parseInt(document.getElementById('cantidadPersonas').value);

  if (!tour || !fecha || !hora || isNaN(cantidad_personas)) {
    mostrarError("Completa todos los campos correctamente.");
    return;
  }

  if (id) {
    modificarReserva(id, tour, fecha, hora, cantidad_personas);
  } else {
    agregarReserva(tour, fecha, hora, cantidad_personas);
  }
  document.getElementById('formAgregarReserva').reset();
  document.getElementById('reservaId').value = '';
}

function mostrarError(msg) {
  document.getElementById('output').textContent = msg;
}