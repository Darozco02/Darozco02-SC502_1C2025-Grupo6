
DROP DATABASE IF EXISTS sistema_averias;
CREATE DATABASE sistema_averias;
USE sistema_averias;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    es_admin BOOLEAN DEFAULT FALSE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de prioridad
CREATE TABLE prioridad (
    id_prioridad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla de estado
CREATE TABLE estado (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla tipo_problema
CREATE TABLE tipo_problema (
    id_problema INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla de reportes
CREATE TABLE reportes (
    id_reporte INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT NOT NULL,
    latitud VARCHAR(50) NOT NULL,
    longitud VARCHAR(50) NOT NULL,
    imagen_url TEXT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT,
    id_prioridad INT,
    id_estado INT DEFAULT 1,
    id_problema INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_prioridad) REFERENCES prioridad(id_prioridad),
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado),
    FOREIGN KEY (id_problema) REFERENCES tipo_problema(id_problema)
);

-- Insertar prioridades
INSERT INTO prioridad (nombre) VALUES ('Alta'), ('Media'), ('Baja');

-- Insertar estados
INSERT INTO estado (nombre) VALUES ('Pendiente'), ('En proceso'), ('Resuelto');

-- Insertar tipos de problema
INSERT INTO tipo_problema (nombre) VALUES ('Hueco'), ('Alcantarilla tapada'), ('Semáforo dañado');

-- Insertar usuario admin y usuario regular
INSERT INTO usuarios (nombre, apellido, email, password, es_admin)
VALUES 
('Isaac', 'Admin', 'admin@ejemplo.com', 'admin123', TRUE),
('Carlos', 'Pérez', 'user@ejemplo.com', 'user123', FALSE);

-- Insertar reportes de ejemplo
INSERT INTO reportes (descripcion, latitud, longitud, imagen_url, id_usuario, id_prioridad, id_estado, id_problema)
VALUES 
('Hueco profundo cerca del parque central', '9.935', '-84.091', 'https://via.placeholder.com/150', 2, 1, 1, 1),
('Alcantarilla sin tapa en la avenida 10', '9.937', '-84.085', 'https://via.placeholder.com/150', 2, 2, 1, 2);
