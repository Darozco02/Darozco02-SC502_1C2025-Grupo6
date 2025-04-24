<?php
include 'conexiones.php'; 

// Recoger datos del formulario
$tipo = $_POST['tipo'];
$ubicacion = $_POST['ubicacion'];
$descripcion = $_POST['descripcionReporte'];
$prioridad = $_POST['prioridad'];


$sql = "INSERT INTO reportes (tipo, ubicacion, descripcion, prioridad) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $tipo, $ubicacion, $descripcion, $prioridad);

if ($stmt->execute()) {
    echo "Reporte guardado correctamente";
} else {
    echo "Error al guardar el reporte: " . $conn->error;
}

$stmt->close();
$conn->close();
?>