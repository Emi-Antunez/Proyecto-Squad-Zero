-- Script para agregar columna foto_perfil a la tabla usuarios
-- Ejecutar este script en la base de datos

ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL;

-- Verificar que la columna se agregó correctamente
DESCRIBE usuarios;