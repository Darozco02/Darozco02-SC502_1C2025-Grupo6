<?php
session_start();
require_once 'conexiones.php';

// Validar sesión
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    http_response_code(403);
    die("Acceso no autorizado.");
}

// Validar datos
if (!isset($_POST['id'], $_POST['estado'], $_POST['prioridad'])) {
    http_response_code(400);
    die("Datos incompletos.");
}

$id_reporte = intval($_POST['id']);
$id_estado = intval($_POST['estado']);
$id_prioridad = intval($_POST['prioridad']);

try {
    $stmt = $pdo->prepare("UPDATE reportes SET id_estado = :estado, id_prioridad = :prioridad WHERE id_reporte = :id");
    $stmt->execute([
        ':estado' => $id_estado,
        ':prioridad' => $id_prioridad,
        ':id' => $id_reporte
    ]);
    header("Location: mapa_reportes.php");
    exit;
} catch (PDOException $e) {
    echo "Error al actualizar el reporte: " . $e->getMessage();
}