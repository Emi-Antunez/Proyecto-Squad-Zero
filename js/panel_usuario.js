const API_URL = "http://localhost/Proyecto-Squad-Zero/backend/routes/api.php";

// Cargar datos del usuario al iniciar
document.addEventListener('DOMContentLoaded', function() {
    cargarDatosUsuario();
    cargarReservasUsuario();
});

// Función para cargar datos del usuario
function cargarDatosUsuario() {
    const usuario = localStorage.getItem('usuario');
    if (!usuario) {
        window.location.href = 'page/login.html';
        return;
    }

    // Por ahora, usaremos el método existente para obtener datos del usuario
    // Más adelante implementaremos un endpoint específico
    fetch(`${API_URL}?action=getUsuarios`)
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data)) {
                const usuarioData = data.find(u => u.usuario === usuario);
                if (usuarioData) {
                    document.getElementById('nombrePerfil').value = usuarioData.nombre;
                    document.getElementById('apellidoPerfil').value = usuarioData.apellido;
                    document.getElementById('emailPerfil').value = usuarioData.gmail;
                    document.getElementById('usuarioPerfil').value = usuarioData.usuario;

                    // Mostrar foto de perfil si existe
                    if (usuarioData.foto_perfil) {
                        const preview = document.getElementById('previewFotoPerfil');
                        preview.src = usuarioData.foto_perfil;
                        preview.style.display = 'block';
                    }
                }
            }
        })
        .catch(err => console.error('Error cargando datos del usuario:', err));
}

// Función para cargar reservas del usuario
function cargarReservasUsuario() {
    const usuario = localStorage.getItem('usuario');
    if (!usuario) return;

    // Usar el nuevo endpoint específico para obtener reservas del usuario
    fetch(`${API_URL}?action=misReservas`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                mostrarReservasUsuario([]);
                return;
            }
            if (Array.isArray(data)) {
                mostrarReservasUsuario(data);
            }
        })
        .catch(err => {
            console.error('Error cargando reservas:', err);
            mostrarReservasUsuario([]);
        });
}

// Función para mostrar reservas del usuario
function mostrarReservasUsuario(reservas) {
    const container = document.getElementById('reservasUsuarioContainer');

    if (!Array.isArray(reservas) || reservas.length === 0) {
        container.innerHTML = '<div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle me-2"></i>No tienes reservas activas.</div></div>';
        return;
    }

    let html = '';
    reservas.forEach(reserva => {
        const fechaReserva = new Date(reserva.fecha);
        const hoy = new Date();
        const esPasada = fechaReserva < hoy;

        html += `
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm ${esPasada ? 'border-secondary' : 'border-primary'}">
                    <div class="card-header ${esPasada ? 'bg-secondary' : 'bg-primary'} text-white">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-ship me-2"></i>${reserva.tour}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            <strong>Fecha:</strong> ${formatearFecha(reserva.fecha)}
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            <strong>Hora:</strong> ${reserva.hora}
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-users me-2 text-primary"></i>
                            <strong>Personas:</strong> ${reserva.cantidad_personas}
                        </div>
                        ${esPasada ? `
                            <div class="alert alert-secondary py-2 mb-3">
                                <i class="fas fa-check-circle me-2"></i>Reserva completada
                            </div>
                        ` : `
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="reprogramarReserva(${reserva.id})">
                                    <i class="fas fa-edit me-1"></i>Reprogramar
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="cancelarReserva(${reserva.id})">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="descargarCalendario(${reserva.id})">
                                    <i class="fas fa-calendar-plus me-1"></i>Agregar a Calendario
                                </button>
                            </div>
                        `}
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// Función para formatear fecha
function formatearFecha(fecha) {
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}

// Función para reprogramar reserva
function reprogramarReserva(idReserva) {
    // Implementar modal para reprogramar
    const modalHTML = `
        <div class="modal fade" id="modalReprogramar" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reprogramar Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formReprogramar">
                            <input type="hidden" id="idReservaReprogramar" value="${idReserva}">
                            <div class="mb-3">
                                <label for="nuevaFecha" class="form-label">Nueva Fecha</label>
                                <input type="date" class="form-control" id="nuevaFecha" required>
                            </div>
                            <div class="mb-3">
                                <label for="nuevaHora" class="form-label">Nueva Hora</label>
                                <select class="form-control" id="nuevaHora" required>
                                    <option value="">Selecciona una hora</option>
                                    <option value="11:00">11:00</option>
                                    <option value="13:30">13:30</option>
                                    <option value="15:30">15:30</option>
                                    <option value="17:00">17:00</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarReprogramacion()">Reprogramar</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = new bootstrap.Modal(document.getElementById('modalReprogramar'));
    modal.show();

    // Configurar fecha mínima
    const fechaInput = document.getElementById('nuevaFecha');
    fechaInput.min = new Date().toISOString().split('T')[0];
}

// Función para confirmar reprogramación
function confirmarReprogramacion() {
    const idReserva = document.getElementById('idReservaReprogramar').value;
    const nuevaFecha = document.getElementById('nuevaFecha').value;
    const nuevaHora = document.getElementById('nuevaHora').value;

    if (!nuevaFecha || !nuevaHora) {
        alert('Completa todos los campos');
        return;
    }

    // Llamar a la API para reprogramar
    fetch(`${API_URL}?action=reprogramarReserva`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: idReserva, fecha: nuevaFecha, hora: nuevaHora })
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
        } else {
            alert('Reserva reprogramada exitosamente');
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalReprogramar'));
            modal.hide();
            cargarReservasUsuario();
        }
    })
    .catch(err => {
        console.error('Error reprogramando reserva:', err);
        alert('Error al reprogramar la reserva');
    });
}

// Función para cancelar reserva
function cancelarReserva(idReserva) {
    if (confirm('¿Estás seguro de que deseas cancelar esta reserva?')) {
        // Llamar a la API para cancelar
        fetch(`${API_URL}?action=deleteReserva&id=${idReserva}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ motivo_cancelacion: 'Cancelada por el usuario' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Reserva cancelada exitosamente');
                cargarReservasUsuario();
            } else {
                alert('Error: ' + (data.error || 'No se pudo cancelar la reserva'));
            }
        })
        .catch(err => {
            console.error('Error cancelando reserva:', err);
            alert('Error al cancelar la reserva');
        });
    }
}

// Función para descargar calendario
function descargarCalendario(idReserva) {
    // Aquí iría la generación del archivo .ics
    alert('Funcionalidad de calendario en desarrollo');
}

// Función para guardar cambios del perfil
document.getElementById('formPerfil').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData();
    formData.append('nombre', document.getElementById('nombrePerfil').value);
    formData.append('apellido', document.getElementById('apellidoPerfil').value);
    formData.append('gmail', document.getElementById('emailPerfil').value);
    formData.append('usuario', document.getElementById('usuarioPerfil').value);

    const fotoPerfil = document.getElementById('fotoPerfil').files[0];
    if (fotoPerfil) {
        formData.append('fotoPerfil', fotoPerfil);
    }

    // Llamar a la API para actualizar perfil
    fetch(`${API_URL}?action=modificarPerfil`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
        } else {
            alert('Perfil actualizado exitosamente');
            // Actualizar localStorage si cambió el usuario
            if (data.usuario) {
                localStorage.setItem('usuario', data.usuario);
            }
            // Recargar datos del usuario
            cargarDatosUsuario();
        }
    })
    .catch(err => {
        console.error('Error actualizando perfil:', err);
        alert('Error al actualizar el perfil');
    });
});

// Función para previsualizar foto de perfil
document.getElementById('fotoPerfil').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewFotoPerfil');
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
