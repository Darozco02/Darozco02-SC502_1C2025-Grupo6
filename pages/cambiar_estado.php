<?php
session_start();
require_once 'conexiones.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    die("Acceso no autorizado");
}

if (!isset($_POST['id_reporte'], $_POST['id_estado'], $_POST['id_prioridad'])) {
    die("Datos incompletos");
}

$id_reporte = $_POST['id_reporte'];
$id_estado = $_POST['id_estado'];
$id_prioridad = $_POST['id_prioridad'];

try {
    $stmt = $pdo->prepare("UPDATE reportes SET id_estado = ?, id_prioridad = ? WHERE id_reporte = ?");
    $stmt->execute([$id_estado, $id_prioridad, $id_reporte]);
    header("Location: mapa_reportes.php");
    exit();
} catch (PDOException $e) {
    die("Error al actualizar reporte: " . $e->getMessage());
}