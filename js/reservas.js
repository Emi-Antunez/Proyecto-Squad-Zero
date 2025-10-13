const API_URL = "http://localhost/PROYECTO-SQUAD-ZERO/backend/routes/api.php";

let reservasOriginal = [];
let ordenAscendente = true;

// Obtener todas las reservas (GET)
function listarReservas() {
  fetch(API_URL)
    .then(res => res.json())
    .then(data => {
      reservasOriginal = Array.isArray(data) ? data : [];
      mostrarTablaReservas(filtrarReservas());
    })
    .catch(err => mostrarError("Error al obtener reservas: " + err));
}

function filtrarReservas() {
  let reservas = [...reservasOriginal];
  const tour = document.getElementById('filtroTour')?.value || "";
  const nombre = document.getElementById('buscadorNombre')?.value?.toLowerCase() || "";

  // Filtrar por tour
  if (tour) reservas = reservas.filter(r => r.tour === tour);

  // Buscar por nombre
  if (nombre) reservas = reservas.filter(r =>
    (`${r.nombre} ${r.apellido}`.toLowerCase().includes(nombre))
  );

  // Ordenar por fecha
  reservas.sort((a, b) => {
    const fa = new Date(a.fecha), fb = new Date(b.fecha);
    return ordenAscendente ? fa - fb : fb - fa;
  });

  return reservas;
}

// Actualiza la tabla cuando cambian los filtros
function actualizarTablaReservas() {
  mostrarTablaReservas(filtrarReservas());
  actualizarIconoOrdenFecha(); // <-- Asegura que el icono se actualice SIEMPRE
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
        <button class="btn btn-danger btn-sm" onclick="eliminarReserva(${r.id})">Eliminar</button>
      </div>
    `;
  });
  html += '</div>';
  container.innerHTML = html;
}

function actualizarIconoOrdenFecha() {
  const ordenFechaIcon = document.getElementById('ordenFechaIcon');
  if (ordenFechaIcon) {
    ordenFechaIcon.className = ordenAscendente ? "fas fa-arrow-down" : "fas fa-arrow-up";
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const filtroTour = document.getElementById('filtroTour');
  const buscadorNombre = document.getElementById('buscadorNombre');
  const ordenFechaBtn = document.getElementById('ordenFechaBtn');

  if (filtroTour) filtroTour.addEventListener('change', actualizarTablaReservas);
  if (buscadorNombre) buscadorNombre.addEventListener('input', actualizarTablaReservas);
  if (ordenFechaBtn) ordenFechaBtn.addEventListener('click', function () {
    ordenAscendente = !ordenAscendente;
    actualizarTablaReservas();
  });

  // Inicializa el icono al cargar
  actualizarIconoOrdenFecha();
});



// Agregar una reserva (POST)
function agregarReserva(tour, fecha, hora, cantidad_personas) {
  fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ tour, fecha, hora, cantidad_personas })
  })
    .then(res => res.text())
    .then(text => {
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        mostrarError("Respuesta no válida del servidor: " + text);
        return;
      }
      if (data.error) {
        mostrarError("Error al agregar reserva: " + data.error);
      } else {
        listarReservas();
      }
    })
    .catch(err => mostrarError("Error al agregar reserva: " + err));
}


// Eliminar una reserva (DELETE)
function eliminarReserva(id) {
  if (!confirm("¿Seguro que deseas eliminar esta reserva?")) return;
  fetch(`${API_URL}?id=${id}`, {
    method: "DELETE"
  })
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        mostrarError("Error al eliminar reserva: " + data.error);
      } else {
        listarReservas();
      }
    })
    .catch(err => mostrarError("Error al eliminar reserva: " + err));
}

// Guardar reserva: solo agrega
function guardarReservaDesdeFormulario() {
    // Verifica si el usuario está logueado
    if (!localStorage.getItem('usuario') || !localStorage.getItem('rol')) {
        alert('Debes iniciar sesión para reservar.');
        window.location.href = 'page/login.html';
        return;
    }
    document.getElementById('output').textContent = "";
    const tour = document.getElementById('tour').value;
    const fecha = document.getElementById('fecha').value;
    const hora = document.getElementById('hora').value;
    const cantidad_personas = parseInt(document.getElementById('cantidadPersonas').value);

    if (!tour || !fecha || !hora || isNaN(cantidad_personas)) {
        mostrarError("Completa todos los campos correctamente.");
        return;
    }

    // Validación
    const existe = reservasOriginal.some(r =>
        r.tour === tour &&
        r.fecha === fecha &&
        r.hora === hora
    );
    if (existe) {
        mostrarError("Ya existe una reserva para ese tour, fecha y hora.");
        return;
    }

    agregarReserva(tour, fecha, hora, cantidad_personas);
    document.getElementById('formAgregarReserva').reset();
}

function mostrarError(msg) {
  document.getElementById('output').textContent = msg;
}

if (usuario) {
    document.getElementById('reservas').style.display = 'block';
    document.getElementById('logoutNav').style.display = 'block';
}

if (usuario) {
    document.getElementById('reservas').style.display = 'block';
    document.getElementById('logoutNav').style.display = 'block';
    document.querySelector('a[href="page/login.html"]').style.display = 'none';
    document.querySelector('a[href="page/register.html"]').style.display = 'none';
}
