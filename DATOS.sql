CREATE DATABASE IF NOT EXISTS sistema_averias;
USE sistema_averias;

-- Tabla de prioridades (bajo, medio, alto)
CREATE TABLE prioridad (
    id_prioridad INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    color VARCHAR(20)
);

-- Tabla de estados (pendiente, en proceso, resuelto)
CREATE TABLE estado (
    id_estado INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    color VARCHAR(20)
);

-- Tabla de tipos de problema (bache, hundimiento, etc.)
CREATE TABLE tipo_problema (
    id_problema INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Tabla de organizaciones (municipalidades, CONAVI, etc.)
CREATE TABLE organizacion (
    id_organizacion INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100)
);

-- Tabla de usuarios (ciudadanos y organizaciones)
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
    id_comentario INT,
    FOREIGN KEY (id_organizacion) REFERENCES organizacion(id_organizacion),
    FOREIGN KEY (id_comentario) REFERENCES comentarios(id_comentario)
);

-- Tabla de comentarios (opcional)
CREATE TABLE comentarios (
    id_comentario INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    contenido TEXT,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- Tabla de reportes
CREATE TABLE reportes (
    id_reporte INT PRIMARY KEY AUTO_INCREMENT,
    descripcion TEXT,
    latitud VARCHAR(50) NOT NULL,
    longitud VARCHAR(50) NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_prioridad INT,
    id_organizacion INT,
    id_imagen INT,
    id_problema INT,
    id_estado INT,
    FOREIGN KEY (id_prioridad) REFERENCES prioridad(id_prioridad),
    FOREIGN KEY (id_organizacion) REFERENCES organizacion(id_organizacion),
    FOREIGN KEY (id_problema) REFERENCES tipo_problema(id_problema),
    FOREIGN KEY (id_estado) REFERENCES estado(id_estado)
    -- id_imagen se agrega luego porque depende de la tabla Imagen
);

-- Tabla de imágenes asociadas a reportes
CREATE TABLE imagen (
    id_imagen INT PRIMARY KEY AUTO_INCREMENT,
    url TEXT NOT NULL,
    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_reporte INT,
    FOREIGN KEY (id_reporte) REFERENCES reportes(id_reporte)
);

-- Ahora sí, agregamos la FK de imagen en reportes
ALTER TABLE reportes
    ADD CONSTRAINT fk_imagen
    FOREIGN KEY (id_imagen) REFERENCES imagen(id_imagen);