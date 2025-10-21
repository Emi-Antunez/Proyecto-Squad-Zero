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
    container.innerHTML = '<p class="text-center text-muted">No hay reservas para mostrar.</p>';
    return;
  }
  let html = '<div class="row g-4">';
  reservas.forEach(r => {
    html += `
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm" style="border-radius: 12px; border: 1px solid #e0e0e0; transition: all 0.3s ease;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title" style="color: #5981C2; font-weight: 700; border-bottom: 2px solid #5981C2; padding-bottom: 0.5rem; margin-bottom: 1rem;">
              <i class="fas fa-ship me-2"></i>${r.tour}
            </h5>
            <div class="mb-2">
              <i class="fas fa-user me-2" style="color: #6c757d; width: 20px;"></i>
              <strong>Usuario:</strong> ${r.nombre} ${r.apellido}
            </div>
            <div class="mb-2">
              <i class="fas fa-calendar-alt me-2" style="color: #6c757d; width: 20px;"></i>
              <strong>Fecha:</strong> ${r.fecha}
            </div>
            <div class="mb-2">
              <i class="fas fa-clock me-2" style="color: #6c757d; width: 20px;"></i>
              <strong>Hora:</strong> ${r.hora}
            </div>
            <div class="mb-3">
              <i class="fas fa-users me-2" style="color: #6c757d; width: 20px;"></i>
              <strong>Personas:</strong> ${r.cantidad_personas}
            </div>
            <button class="btn btn-danger btn-sm mt-auto w-100" onclick="eliminarReserva(${r.id})" style="border-radius: 8px;">
              <i class="fas fa-trash-alt me-2"></i>Eliminar
            </button>
          </div>
        </div>
      </div>
    `;
  });
  html += '</div>';
  container.innerHTML = html;
}

function actualizarIconoOrdenFecha() {
  const ordenFechaIcon = document.getElementById('ordenFechaIcon');
  const ordenFechaAdminIcon = document.getElementById('ordenFechaAdminIcon');
  
  if (ordenFechaIcon) {
    ordenFechaIcon.className = ordenAscendente ? "fas fa-arrow-down" : "fas fa-arrow-up";
  }
  
  if (ordenFechaAdminIcon) {
    ordenFechaAdminIcon.className = ordenAscendente ? "fas fa-arrow-down" : "fas fa-arrow-up";
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

  const popoverTrigger = document.getElementById('infoPopover');
  if (popoverTrigger) {
    const popover = new bootstrap.Popover(popoverTrigger, {
      html: true,
      content: `
                <div style="max-width: 300px;">
                    <h6>Colonia del Sacramento</h6>
                    <p>Colonia del Sacramento es una ciudad histórica y pintoresca, famosa por su casco antiguo, declarado Patrimonio de la Humanidad por la UNESCO. Sus calles empedradas, arquitectura colonial y hermosas playas la convierten en un destino imperdible en Uruguay.</p>
                    <h6>El Bote</h6>
                    <p>El Malibú es un crucero de 12 metros de eslora por 4 de manga, habilitado para 23 pasajeros. Ofrece una experiencia segura y cómoda para explorar las aguas de Colonia.</p>
                </div>
            `,
      placement: 'right', // Posición del popover
    });
  } else {
    console.error('El ícono con ID "infoPopover" no se encontró en el DOM.');
  }
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
        mostrarModalExito(tour, fecha, hora, cantidad_personas);
        listarReservas();
      }
    })
    .catch(err => mostrarError("Error al agregar reserva: " + err));
}

// Mostrar modal de éxito con detalles de la reserva
function mostrarModalExito(tour, fecha, hora, cantidad_personas) {
  const modalHTML = `
    <div class="modal fade" id="modalExitoReserva" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
          <div class="modal-header" style="background: linear-gradient(135deg, #198754 0%, #157347 100%); color: white; border: none; padding: 2rem;">
            <h5 class="modal-title w-100 text-center" style="font-weight: 700; font-size: 1.5rem;">
              <i class="fas fa-check-circle me-2" style="font-size: 2rem;"></i>
              <br>¡Reserva Confirmada!
            </h5>
          </div>
          <div class="modal-body" style="padding: 2rem; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
            <div class="text-center mb-4">
              <p class="lead mb-4" style="color: #555;">Tu reserva se ha realizado exitosamente</p>
            </div>
            <div class="card" style="border: 2px solid #198754; border-radius: 15px; background: white;">
              <div class="card-body" style="padding: 1.5rem;">
                <h6 style="color: #198754; font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid #198754; padding-bottom: 0.5rem;">
                  <i class="fas fa-info-circle me-2"></i>Detalles de tu Reserva
                </h6>
                <div class="row g-3">
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-ship me-3" style="color: #198754; font-size: 1.2rem;"></i>
                      <div>
                        <small class="text-muted d-block">Tour</small>
                        <strong style="color: #333;">${tour}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-calendar-alt me-3" style="color: #198754; font-size: 1.2rem;"></i>
                      <div>
                        <small class="text-muted d-block">Fecha</small>
                        <strong style="color: #333;">${fecha}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-clock me-3" style="color: #198754; font-size: 1.2rem;"></i>
                      <div>
                        <small class="text-muted d-block">Hora</small>
                        <strong style="color: #333;">${hora}</strong>
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-flex align-items-center">
                      <i class="fas fa-users me-3" style="color: #198754; font-size: 1.2rem;"></i>
                      <div>
                        <small class="text-muted d-block">Cantidad de personas</small>
                        <strong style="color: #333;">${cantidad_personas} ${cantidad_personas === 1 ? 'persona' : 'personas'}</strong>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="alert alert-info mt-3 mb-0" style="border-radius: 10px; border-left: 4px solid #0dcaf0;">
              <i class="fas fa-info-circle me-2"></i>
              <small>Recibirás un correo de confirmación con todos los detalles de tu reserva.</small>
            </div>
          </div>
          <div class="modal-footer" style="border: none; padding: 1.5rem 2rem; background: #f8f9fa;">
            <button type="button" class="btn btn-success w-100" data-bs-dismiss="modal" style="border-radius: 10px; padding: 0.75rem; font-weight: 600;">
              <i class="fas fa-check me-2"></i>Entendido
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
  
  // Eliminar modal anterior si existe
  const modalAnterior = document.getElementById('modalExitoReserva');
  if (modalAnterior) {
    modalAnterior.remove();
  }
  
  // Agregar modal al body
  document.body.insertAdjacentHTML('beforeend', modalHTML);
  
  // Mostrar modal
  const modal = new bootstrap.Modal(document.getElementById('modalExitoReserva'));
  modal.show();
  
  // Limpiar el modal del DOM cuando se cierre
  document.getElementById('modalExitoReserva').addEventListener('hidden.bs.modal', function () {
    this.remove();
  });
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

function reservarTour(tour) {
    const usuario = localStorage.getItem('usuario');
    if (!usuario) {
        // No está logueado, redirige a login
        window.location.href = 'page/login.html';
        return;
    }
    // Sí está logueado, redirige o abre el formulario
    window.location.href = '#reservasSection'; // o mostrar la sección de reservas
    document.getElementById('tour').value = tour; // auto-selecciona el tour en el formulario
}
