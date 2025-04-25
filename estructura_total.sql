DROP DATABASE IF EXISTS sistema_averias;
CREATE DATABASE sistema_averias;
USE sistema_averias;

-- Tabla prioridad
CREATE TABLE prioridad (
    id_prioridad INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    color VARCHAR(20)
);

-- Tabla estado
CREATE TABLE estado (
    id_estado INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    color VARCHAR(20)
);

-- Tabla tipo_problema
CREATE TABLE tipo_problema (
    id_problema INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Tabla organizacion
CREATE TABLE organizacion (
    id_organizacion INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100)
);

-- Tabla usuarios (sin FK a comentarios)
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    es_admin BOOLEAN DEFAULT FALSE,
    es_organizacion BOOLEAN DEFAULT FALSE,
    id_organizacion INT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    token_recovery VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    ultima_conexion DATETIME,
    FOREIGN KEY (id_organizacion) REFERENCES organizacion(id_organizacion)
);

-- Tabla comentarios
CREATE TABLE comentarios (
    id_comentario INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    contenido TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla reportes
CREATE TABLE reportes (
    id_reporte INT PRIMARY KEY AUTO_INCREMENT,
    descripcion TEXT,
    latitud VARCHAR(50) NOT NULL,
    longitud VARCHAR(50) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_prioridad INT,
    id_organizacion INT,
    id_problema INT,
    id_estado INT,
    id_imagen INT,
    FOREIGN KEY (id_prioridad) REFERENCES prioridad(id_prioridad),
    FOREIGN KEY (id_organizacion) REFERENCES organizacion(id_organizacion),
    FOREIGN KEY (id_problema) REFERENCES tipo_problema(id_problema),
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado)
);

-- Tabla imagen
CREATE TABLE imagen (
    id_imagen INT PRIMARY KEY AUTO_INCREMENT,
    url TEXT NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_reporte INT,
    FOREIGN KEY (id_reporte) REFERENCES reportes(id_reporte)
);

-- Agregar FK a imagen (opcional si no se agregó arriba)
ALTER TABLE reportes
  ADD CONSTRAINT fk_imagen FOREIGN KEY (id_imagen) REFERENCES imagen(id_imagen);

-- Datos iniciales para prioridad
INSERT INTO prioridad (nombre, descripcion, color) VALUES
('Alta', 'Urgente y crítica', 'rojo'),
('Media', 'Importancia moderada', 'amarillo'),
('Baja', 'No urgente', 'verde');

-- Datos iniciales para estado
INSERT INTO estado (nombre, descripcion, color) VALUES
('Pendiente', 'En espera de atención', 'gris'),
('En proceso', 'Atendiendo el problema', 'azul'),
('Resuelto', 'Problema solucionado', 'verde');

-- Datos iniciales para tipo_problema
INSERT INTO tipo_problema (nombre, descripcion) VALUES
('Bache', 'Hueco en la vía'),
('Hundimiento', 'Hundimiento del terreno'),
('Falta de señalización', 'Ausencia de señales viales');