// URL base del endpoint
const API_URL = "http://localhost/PROYECTO-SQUAD-ZERO/backend/routes/api.php"; // Cambia esta URL según corresponda

// Obtener todos los productos (GET)
function listarProductos() {
  fetch(API_URL)
    .then(res => res.json()) // Convierte la respuesta a JSON
    .then(data => {
      console.log("Productos:", data); // Muestra los productos en consola
      mostrarTablaProductos(data); // Llama a la función para mostrar la tabla en el HTML
    })
    .catch(err => console.error("Error al obtener productos:", err));
}

// Función para mostrar la tabla de productos en el div 'productosContainer'
// ...existing code...
function mostrarTablaProductos(productos) {
  const container = document.getElementById('productosContainer');
  if (!Array.isArray(productos) || productos.length === 0) {
    container.innerHTML = '<p>No hay productos para mostrar.</p>';
    return;
  }
  let html = '<div style="display: flex; flex-wrap: wrap; gap: 16px;">';
  productos.forEach(p => {
    let src = p.imagen;
    if (src && !src.startsWith('data:image')) {
      src = 'data:image/jpeg;base64,' + src;
    }
    html += `
      <div style="border:1px solid #ccc; border-radius:8px; padding:16px; width:220px; box-shadow:2px 2px 8px #eee;">
        ${src ? `<img src="${src}" alt="Imagen de ${p.nombre}" style="width:120px;height:120px;object-fit:cover;aspect-ratio:1/1;display:block;margin:0 auto 8px auto;border-radius:8px;">` : ''}
        <h3>${p.nombre}</h3>
        <p><strong>ID:</strong> ${p.id}</p>
        <p><strong>Descripción:</strong> ${p.descripcion}</p>
        <p><strong>Precio:</strong> $${p.precio}</p>
        <p><strong>Stock:</strong> ${p.stock}</p>
        <button onclick="cargarProductoEnFormulario(${p.id})">Modificar</button>
        <button onclick="eliminarProducto(${p.id})">Eliminar</button>
      </div>
    `;
  });
  html += '</div>';
  container.innerHTML = html;
}
// ...existing code...

// Obtener un producto por ID (GET)
function mostrarProducto(id) {
  fetch(`${API_URL}/id/${id}`)
    .then(res => res.json()) // Convierte la respuesta a JSON
    .then(data => console.log("Producto:", data)) // Muestra el producto en consola
    .catch(err => console.error("Error al obtener producto:", err));
}

// Agregar un producto nuevo (POST)
function agregarProducto(nombre, descripcion, precio, stock = 0, imagen = null) {
  fetch(API_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ nombre, descripcion, precio, stock, imagen })
  })
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        mostrarError("Error al agregar producto: " + data.error);
      } else {
        listarProductos();
      }
    })
    .catch(err => mostrarError("Error al agregar producto: " + err));
}



  function agregarProductoDesdeFormulario() {
  let nombre = document.getElementById('nombreProducto').value;
  let descripcion = document.getElementById('descripcionProducto').value;
  let precio = parseFloat(document.getElementById('precioProducto').value);
  let stock = parseInt(document.getElementById('stockProducto').value); // Valor por defecto 0 si no se ingresa
  agregarProducto(nombre, descripcion, precio, stock);
        }

// Modificar un producto (PUT)
function modificarProducto(id, nombre, descripcion, precio, stock = 0, imagen = null) {
  fetch(API_URL, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, nombre, descripcion, precio, stock, imagen })
  })
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        mostrarError("Error al modificar producto: " + data.error);
      } else {
        listarProductos();
      }
    })
    .catch(err => mostrarError("Error al modificar producto: " + err));
}

// Llenar el formulario con los datos del producto seleccionado para modificar
// Mostrar errores en el <pre id="output">
function mostrarError(msg) {
  document.getElementById('output').textContent = msg;
}

// Llenar el formulario con los datos del producto seleccionado para modificar
function cargarProductoEnFormulario(id) {
  fetch(`${API_URL}?id=${id}`)
    .then(res => res.json())
    .then(producto => {
      document.getElementById('productoId').value = producto.id;
      document.getElementById('nombreProducto').value = producto.nombre;
      document.getElementById('descripcionProducto').value = producto.descripcion;
      document.getElementById('precioProducto').value = producto.precio;
      document.getElementById('stockProducto').value = producto.stock;
      // Mostrar preview de imagen si existe
      let imgPreview = document.getElementById('imgPreview');
      if (!imgPreview) {
        imgPreview = document.createElement('img');
        imgPreview.id = 'imgPreview';
        imgPreview.style.maxWidth = '120px';
        imgPreview.style.display = 'block';
        document.getElementById('formAgregarProducto').appendChild(imgPreview);
      }
      imgPreview.src = producto.imagen || '';
      imgPreview.style.display = producto.imagen ? 'block' : 'none';
    })
    .catch(err => mostrarError("Error al cargar producto: " + err));
}

// Guardar producto: agrega o modifica según si hay ID
function guardarProductoDesdeFormulario() {
  document.getElementById('output').textContent = ""; // Limpia errores previos
  const id = document.getElementById('productoId').value;
  const nombre = document.getElementById('nombreProducto').value;
  const descripcion = document.getElementById('descripcionProducto').value;
  const precio = parseFloat(document.getElementById('precioProducto').value);
  const stock = parseInt(document.getElementById('stockProducto').value);
  const imagenInput = document.getElementById('imagenProducto');
  const imagenFile = imagenInput.files[0];

  // Validación básica
  if (!nombre || !descripcion || isNaN(precio) || isNaN(stock)) {
    mostrarError("Completa todos los campos correctamente.");
    return;
  }

  if (imagenFile) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const imagen = e.target.result; // base64
      enviarProducto(id, nombre, descripcion, precio, stock, imagen);
      // Solo resetea el form después de enviar
      document.getElementById('formAgregarProducto').reset();
      document.getElementById('productoId').value = '';
      let imgPreview = document.getElementById('imgPreview');
      if (imgPreview) imgPreview.style.display = 'none';
    };
    reader.onerror = function() {
      mostrarError("Error al leer la imagen.");
    };
    reader.readAsDataURL(imagenFile);
  } else {
    enviarProducto(id, nombre, descripcion, precio, stock, null);
    document.getElementById('formAgregarProducto').reset();
    document.getElementById('productoId').value = '';
    let imgPreview = document.getElementById('imgPreview');
    if (imgPreview) imgPreview.style.display = 'none';
  }
}

// Nueva función para decidir agregar o modificar
function enviarProducto(id, nombre, descripcion, precio, stock, imagen) {
  if (id) {
    modificarProducto(id, nombre, descripcion, precio, stock, imagen);
  } else {
    agregarProducto(nombre, descripcion, precio, stock, imagen);
  }
}


// Cambia el botón "Modificar" en la tabla para que llame a cargarProductoEnFormulario
// Eliminar un producto (DELETE)
function eliminarProducto(id) {
  fetch(`${API_URL}?id=${id}`, {
    method: "DELETE"
  })
    .then(res => res.json())
    .then(data => {
      console.log("Producto eliminado:", data);
      listarProductos(); // Refresca la tabla después de eliminar
    })
    .catch(err => console.error("Error al eliminar producto:", err));
}

// Ejemplos de uso
// listarProductos();
// mostrarProducto(1);
// agregarProducto("Producto X", "Descripción X", 99.99);
// modificarProducto(1, "Nuevo nombre", "Nueva descripción", 123.45);
// eliminarProducto(1);