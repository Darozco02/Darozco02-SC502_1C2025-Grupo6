<?php
require_once 'conexiones.php';
header('Content-Type: application/json');

$stmt = $pdo->query("SELECT r.id_reporte, r.descripcion, r.latitud, r.longitud, r.imagen_url,
                            p.nombre AS prioridad, e.nombre AS estado, 
                            t.nombre AS tipo, u.nombre AS usuario
                     FROM reportes r
                     JOIN prioridad p ON r.id_prioridad = p.id_prioridad
                     JOIN estado e ON r.id_estado = e.id_estado
                     JOIN tipo_problema t ON r.id_problema = t.id_problema
                     JOIN usuarios u ON r.id_usuario = u.id_usuario");

echo json_encode($stmt->fetchAll());
