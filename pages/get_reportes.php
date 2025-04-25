<?php
require_once 'conexiones.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT 
        r.latitud, r.longitud, r.descripcion, r.fecha_creacion AS fecha,
        tp.nombre AS tipo,
        p.nombre AS prioridad,
        e.nombre AS estado,
        i.url AS imagen_url
        FROM reportes r
        LEFT JOIN tipo_problema tp ON r.id_problema = tp.id_problema
        LEFT JOIN prioridad p ON r.id_prioridad = p.id_prioridad
        LEFT JOIN estado e ON r.id_estado = e.id_estado
        LEFT JOIN imagen i ON r.id_imagen = i.id_imagen
        ORDER BY r.fecha_creacion DESC");

    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reportes);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>