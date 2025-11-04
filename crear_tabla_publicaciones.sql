-- Script SQL para crear la tabla de publicaciones
-- Ejecutar este script en phpMyAdmin o en la consola MySQL

CREATE TABLE IF NOT EXISTS publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(500) DEFAULT NULL,
    fecha DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunas publicaciones de ejemplo (opcional)
INSERT INTO publicaciones (titulo, descripcion, fecha) VALUES
('¡Bienvenidos a nuestra nueva temporada!', 'Estamos emocionados de anunciar el inicio de nuestra temporada 2024. Nuevos tours, nuevas experiencias y los mejores precios para disfrutar del río.', '2024-01-15'),
('Descuento especial para grupos', 'Reserva para grupos de 6 o más personas y obtén un 15% de descuento en cualquiera de nuestros tours. ¡No te lo pierdas!', '2024-01-20'),
('Nuevo tour: Atardecer en el Río', 'Presentamos nuestro nuevo tour al atardecer. Disfruta de las vistas más espectaculares mientras navegas por el río. Incluye bebidas y snacks.', '2024-01-25');