<?php
session_start();
require_once 'conexiones.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    http_response_code(403);
    exit("No autorizado");
}

$id = $_POST['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("UPDATE reportes SET id_estado = 2 WHERE id_reporte = ?");
    $stmt->execute([$id]);
    echo "ok";
} else {
    http_response_code(400);
    echo "Falta ID";
}