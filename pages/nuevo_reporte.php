<?php
require_once 'conexiones.php';

if (
    isset($_POST['latitud'], $_POST['longitud'], $_POST['tipo'], $_POST['descripcion'], $_POST['prioridad'])
) {
    $lat = $_POST['latitud'];
    $lng = $_POST['longitud'];
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $prioridad = $_POST['prioridad'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO reportes (descripcion, latitud, longitud, fecha_creacion, id_prioridad, id_problema, id_estado)
            VALUES (
                :descripcion,
                :lat,
                :lng,
                NOW(),
                (SELECT id_prioridad FROM prioridad WHERE nombre = :prioridad LIMIT 1),
                (SELECT id_problema FROM tipo_problema WHERE nombre = :tipo LIMIT 1),
                1
            )
        ");

        $stmt->execute([
            ':descripcion' => $descripcion,
            ':lat' => $lat,
            ':lng' => $lng,
            ':prioridad' => $prioridad,
            ':tipo' => $tipo
        ]);

        // Redirigir al mapa si todo fue bien
        header('Location: mapa_reportes.php');
        exit;

    } catch (PDOException $e) {
        echo "Error al guardar el reporte: " . $e->getMessage();
    }
} else {
    echo "Faltan datos del formulario.";
}