USE sistema_averias;

-- Datos para la tabla "prioridad"
INSERT INTO prioridad (id_prioridad, nombre, descripcion, color) VALUES
(1, 'Alta', 'Urgente y crítica', 'rojo'),
(2, 'Media', 'Importancia moderada', 'amarillo'),
(3, 'Baja', 'No urgente', 'verde');

-- Datos para la tabla "tipo_problema"
INSERT INTO tipo_problema (id_problema, nombre, descripcion) VALUES
(1, 'Bache', 'Hueco en la vía'),
(2, 'Hundimiento', 'Hundimiento de terreno'),
(3, 'Falta de señalización', 'Ausencia de señales viales');

-- Datos para la tabla "estado"
INSERT INTO estado (id_estado, nombre, descripcion, color) VALUES
(1, 'Pendiente', 'En espera de atención', 'gris'),
(2, 'En proceso', 'Atendiendo el problema', 'azul'),
(3, 'Resuelto', 'Problema solucionado', 'verde');

