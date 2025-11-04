const API_URL_PUBLICACIONES = "http://localhost/Proyecto-Squad-Zero/backend/routes/api.php";

// Cargar publicaciones públicas
async function cargarPublicacionesPublicas() {
    try {
        const response = await fetch(`${API_URL_PUBLICACIONES}?action=publicaciones`);
        const publicaciones = await response.json();
        
        const container = document.getElementById('publicacionesPublicasContainer');
        if (!container) return;

        if (publicaciones.length === 0) {
            container.innerHTML = '<p class="text-center text-muted">No hay publicaciones disponibles en este momento.</p>';
            return;
        }

        container.innerHTML = publicaciones.map(pub => `
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    ${pub.imagen ? `
                        <img src="${pub.imagen}" class="card-img-top" alt="${pub.titulo}" style="height: 200px; object-fit: cover;">
                    ` : ''}
                    <div class="card-body">
                        <h5 class="card-title">${pub.titulo}</h5>
                        <p class="card-text">${pub.descripcion}</p>
                        <p class="text-muted small">
                            <i class="far fa-calendar"></i> ${formatearFecha(pub.fecha)}
                        </p>
                    </div>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error al cargar publicaciones:', error);
    }
}

// Cargar publicaciones para administración
async function cargarPublicacionesAdmin() {
    try {
        const response = await fetch(`${API_URL_PUBLICACIONES}?action=publicaciones`);
        const publicaciones = await response.json();
        
        const container = document.getElementById('adminPublicacionesContainer');
        if (!container) return;

        if (publicaciones.length === 0) {
            container.innerHTML = '<p class="text-center text-muted">No hay publicaciones. Crea la primera.</p>';
            return;
        }

        container.innerHTML = publicaciones.map(pub => `
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        ${pub.imagen ? `
                            <div class="col-md-2">
                                <img src="${pub.imagen}" class="img-fluid rounded" alt="${pub.titulo}" style="max-height: 80px; object-fit: cover;">
                            </div>
                        ` : ''}
                        <div class="col-md-${pub.imagen ? '7' : '9'}">
                            <h5 class="mb-1">${pub.titulo}</h5>
                            <p class="mb-1 text-muted">${pub.descripcion.substring(0, 100)}${pub.descripcion.length > 100 ? '...' : ''}</p>
                            <small class="text-muted">
                                <i class="far fa-calendar"></i> ${formatearFecha(pub.fecha)}
                            </small>
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-sm btn-primary me-2" onclick="editarPublicacion(${pub.id})">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarPublicacion(${pub.id})">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error al cargar publicaciones admin:', error);
    }
}

// Mostrar modal para crear/editar publicación
window.mostrarModalPublicacion = function(id = null) {
    const modal = document.getElementById('modalPublicacion');
    const form = document.getElementById('formPublicacion');
    const titulo = document.getElementById('tituloModalPublicacion');
    
    form.reset();
    document.getElementById('publicacionId').value = '';
    document.getElementById('imagenActual').value = '';
    document.getElementById('previsualizacionImagen').style.display = 'none';
    
    if (id) {
        titulo.textContent = 'Editar Publicación';
        cargarDatosPublicacion(id);
    } else {
        titulo.textContent = 'Nueva Publicación';
    }
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// Cargar datos de una publicación para editar
async function cargarDatosPublicacion(id) {
    try {
        const response = await fetch(`${API_URL_PUBLICACIONES}?action=publicaciones&id=${id}`);
        const pub = await response.json();
        
        if (!pub.error) {
            document.getElementById('publicacionId').value = pub.id;
            document.getElementById('tituloPublicacion').value = pub.titulo;
            document.getElementById('descripcionPublicacion').value = pub.descripcion;
            document.getElementById('fechaPublicacion').value = pub.fecha;
            document.getElementById('imagenActual').value = pub.imagen || '';
            
            if (pub.imagen) {
                const preview = document.getElementById('previsualizacionImagen');
                preview.src = pub.imagen;
                preview.style.display = 'block';
            }
        }
    } catch (error) {
        console.error('Error al cargar publicación:', error);
    }
}

// Previsualizar imagen seleccionada
function previsualizarImagen(input) {
    const preview = document.getElementById('previsualizacionImagen');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Guardar publicación
async function guardarPublicacion(event) {
    event.preventDefault();
    
    const id = document.getElementById('publicacionId').value;
    const titulo = document.getElementById('tituloPublicacion').value;
    const descripcion = document.getElementById('descripcionPublicacion').value;
    const fecha = document.getElementById('fechaPublicacion').value;
    const imagenInput = document.getElementById('imagenPublicacion');
    const imagenActual = document.getElementById('imagenActual').value;
    
    let rutaImagen = imagenActual;
    
    // Si hay una nueva imagen, subirla primero
    if (imagenInput.files.length > 0) {
        const formData = new FormData();
        formData.append('imagen', imagenInput.files[0]);
        
        try {
            const uploadResponse = await fetch(`${API_URL_PUBLICACIONES}?action=upload-imagen`, {
                method: 'POST',
                body: formData
            });
            const uploadResult = await uploadResponse.json();
            
            if (uploadResult.error) {
                alert('Error al subir la imagen: ' + uploadResult.error);
                return;
            }
            
            rutaImagen = uploadResult.ruta;
        } catch (error) {
            console.error('Error al subir imagen:', error);
            alert('Error al subir la imagen');
            return;
        }
    }
    
    // Guardar o actualizar publicación
    const data = {
        titulo,
        descripcion,
        imagen: rutaImagen,
        fecha
    };
    
    try {
        let response;
        if (id) {
            data.id = id;
            response = await fetch(`${API_URL_PUBLICACIONES}?action=publicaciones`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
        } else {
            response = await fetch(`${API_URL_PUBLICACIONES}?action=publicaciones`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
        }
        
        const result = await response.json();
        
        if (result.error) {
            alert('Error: ' + result.error);
        } else {
            alert(result.mensaje);
            bootstrap.Modal.getInstance(document.getElementById('modalPublicacion')).hide();
            cargarPublicacionesAdmin();
            cargarPublicacionesPublicas();
        }
    } catch (error) {
        console.error('Error al guardar publicación:', error);
        alert('Error al guardar la publicación');
    }
}

// Editar publicación
window.editarPublicacion = function(id) {
    window.mostrarModalPublicacion(id);
}

// Eliminar publicación
window.eliminarPublicacion = async function(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta publicación?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_URL_PUBLICACIONES}?action=publicaciones&id=${id}`, {
            method: 'DELETE'
        });
        const result = await response.json();
        
        if (result.error) {
            alert('Error: ' + result.error);
        } else {
            alert(result.mensaje);
            cargarPublicacionesAdmin();
            cargarPublicacionesPublicas();
        }
    } catch (error) {
        console.error('Error al eliminar publicación:', error);
        alert('Error al eliminar la publicación');
    }
}

// Formatear fecha
function formatearFecha(fecha) {
    const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Cargar publicaciones públicas si existe el contenedor
    if (document.getElementById('publicacionesPublicasContainer')) {
        cargarPublicacionesPublicas();
    }
    
    // Cargar publicaciones admin si existe el contenedor
    if (document.getElementById('adminPublicacionesContainer')) {
        cargarPublicacionesAdmin();
    }
    
    // Event listener para el formulario
    const form = document.getElementById('formPublicacion');
    if (form) {
        form.addEventListener('submit', guardarPublicacion);
    }
    
    // Event listener para previsualizar imagen
    const imagenInput = document.getElementById('imagenPublicacion');
    if (imagenInput) {
        imagenInput.addEventListener('change', function() {
            previsualizarImagen(this);
        });
    }
});