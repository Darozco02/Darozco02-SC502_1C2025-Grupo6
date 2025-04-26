<?php
session_start();
require_once 'conexiones.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        empty($_POST['descripcion']) || empty($_POST['latitud']) || empty($_POST['longitud']) ||
        empty($_POST['id_prioridad']) || empty($_POST['id_problema'])
    ) {
        die("Faltan datos obligatorios.");
    }

    $id_usuario   = $_SESSION['usuario']['id_usuario'];
    $descripcion  = $_POST['descripcion'];
    $latitud      = $_POST['latitud'];
    $longitud     = $_POST['longitud'];
    $id_prioridad = $_POST['id_prioridad'];
    $id_problema  = $_POST['id_problema'];
    $imagen_url   = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $mime = $_FILES['imagen']['type'];
        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($mime, $allowed)) {
            $target_dir = __DIR__ . "/uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $filename = uniqid() . "_" . basename($_FILES["imagen"]["name"]);
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $imagen_url = "uploads/" . $filename;
            }
        }
    }

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

    header("Location: mapa_reportes.php");
    exit;
}

$stmt = $pdo->query("SELECT id_problema, nombre FROM tipo_problema");
$problemas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"/>
    <style>
        #map { height: 400px; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="mb-4">Nuevo Reporte</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Descripción:</label>
            <input type="text" name="descripcion" class="form-control" required>
        </div>

        <input type="hidden" id="latitud" name="latitud">
        <input type="hidden" id="longitud" name="longitud">

        <div class="mb-3">
            <label class="form-label">Prioridad:</label>
            <select name="id_prioridad" class="form-select" required>
                <option value="1">Alta</option>
                <option value="2">Media</option>
                <option value="3">Baja</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de problema:</label>
            <select name="id_problema" class="form-select" required>
                <?php foreach ($problemas as $p): ?>
                    <option value="<?= $p['id_problema'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen:</label>
            <input type="file" name="imagen" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Selecciona la ubicación en el mapa:</label>
            <div id="map"></div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Enviar reporte</button>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([9.93, -84.09], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;

        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
    });
</script>
</body>
</html>