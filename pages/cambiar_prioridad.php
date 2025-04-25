<?php
session_start();
require_once 'conexiones.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['es_admin'] != 1) {
    die("Acceso denegado.");
}

if (!isset($_GET['id']) || !isset($_GET['priority']) ||
    !is_numeric($_GET['id']) || !is_numeric($_GET['priority'])) {
    die("Datos inválidos.");
}

$id_reporte   = $_GET['id'];
$nueva_prioridad = $_GET['priority'];

try {
    // Actualizar la prioridad del reporte
    $stmt = $pdo->prepare("UPDATE reportes SET id_prioridad = :prio WHERE id_reporte = :id");
    $stmt->execute([':prio' => $nueva_prioridad, ':id' => $id_reporte]);
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo "ok";
    } else {
        echo "Prioridad actualizada.";
    }
    exit;
} catch (PDOException $e) {
    die("Error al cambiar la prioridad: " . $e->getMessage());
}