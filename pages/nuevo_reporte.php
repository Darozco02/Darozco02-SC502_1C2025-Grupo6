<?php
session_start();
require_once 'conexiones.php';

if (!isset($_SESSION['usuario']['id_usuario'])) {
    die("Usuario no autenticado.");
}

// Verificar que todos los campos requeridos estén presentes
if (
    !isset($_POST['descripcion'], $_POST['latitud'], $_POST['longitud'],
        $_POST['id_prioridad'], $_POST['id_problema'])
) {
    die("Datos incompletos.");
}

$id_usuario   = $_SESSION['usuario']['id_usuario'];
$descripcion  = $_POST['descripcion'];
$latitud      = $_POST['latitud'];
$longitud     = $_POST['longitud'];
$id_prioridad = $_POST['id_prioridad'];
$id_problema  = $_POST['id_problema'];
$imagen_url   = null;

// Procesar imagen si el usuario adjuntó una
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    // Validar tipo de archivo imagen
    $mime = $_FILES['imagen']['type'];
    $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($mime, $allowed)) {
        die("Formato de imagen no válido.");  // termina si no es jpg/png
    }
    // Asegurar directorio uploads
    $target_dir = __DIR__ . "/../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    // Generar nombre único y mover archivo
    $filename = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
    $target_file = $target_dir . $filename;
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        // Guardar ruta relativa para la BD (desde pages/)
        $imagen_url = "uploads/" . $filename;
    }
}

try {
    // Insertar nuevo reporte en la BD (estado por defecto = Pendiente id 1)
    $stmt = $pdo->prepare("INSERT INTO reportes 
        (descripcion, latitud, longitud, imagen_url, id_usuario, id_prioridad, id_estado, id_problema) 
        VALUES (:desc, :lat, :lng, :img, :user, :prio, 1, :prob)");
    $stmt->execute([
        ':desc'  => $descripcion,
        ':lat'   => $latitud,
        ':lng'   => $longitud,
        ':img'   => $imagen_url,
        ':user'  => $id_usuario,
        ':prio'  => $id_prioridad,
        ':prob'  => $id_problema
    ]);
    // Responder según el contexto (AJAX o no)
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo "ok";  // llamado vía AJAX, respondemos texto plano
    } else {
        header("Location: mapa_reportes.php");  // llamado normal, redirigir a mapa
    }
    exit;
} catch (PDOException $e) {
    die("Error al guardar el reporte: " . $e->getMessage());
}