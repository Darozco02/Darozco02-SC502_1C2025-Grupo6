<?php
session_start();
require_once 'conexiones.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['es_admin'] != 1) {
    die("Acceso denegado. Solo administradores pueden realizar esta acción.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de reporte inválido.");
}

$id_reporte = $_GET['id'];

try {
    // Actualizar el estado del reporte a 'Resuelto' (id_estado = 3 en la BD)
    $stmt = $pdo->prepare("UPDATE reportes SET id_estado = 3 WHERE id_reporte = :id");
    $stmt->execute([':id' => $id_reporte]);
    // Respuesta según contexto (AJAX o normal)
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo "ok";
    } else {
        header("Location: mapa_reportes.php");
    }
    exit;
} catch (PDOException $e) {
    die("Error al actualizar el reporte: " . $e->getMessage());
}