<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo "<h1>NO INICIASTE SESIÓN</h1>";
    exit;
}
$rol = $_SESSION['usuario']['rol'] ?? 'usuario'; // valor por defecto si no existe
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Reportes</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
        .leyenda {
            background: white;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 999;
            box-shadow: 0 0 5px rgba(0,0,0,0.3);
        }
        .leyenda div {
            margin: 4px 0;
        }
    </style>
</head>
<body>

<h2>Mapa de Reportes</h2>

<div>
    <button onclick="filtrar('todos')">Todos</button>
    <button onclick="filtrar('abiertos')">Abiertos</button>
    <button onclick="filtrar('resueltos')">Resueltos</button>
    <a href="nuevo_reporte.php"><button style="float:right; background-color:blue; color:white;">Nuevo Reporte</button></a>
    <a href="logout.php"><button style="float:right; background-color:red; color:white;">Cerrar sesión</button></a>
</div>

<div id="map"></div>

<div class="leyenda">
    <strong>Leyenda:</strong>
    <div><span style="color:#d7191c;">●</span> Alta (Abierto)</div>
    <div><span style="color:#fdae61;">●</span> Media (Abierto)</div>
    <div><span style="color:#1a9641;">●</span> Baja (Abierto)</div>
    <div><span style="color:#800026;">●</span> Alta (Resuelto)</div>
    <div><span style="color:#fd8d3c;">●</span> Media (Resuelto)</div>
    <div><span style="color:#006837;">●</span> Baja (Resuelto)</div>
</div>

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([9.9281, -84.0907], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const colores = {
        'Alta':   { 'Abierto': '#d7191c', 'Resuelto': '#800026' },
        'Media':  { 'Abierto': '#fdae61', 'Resuelto': '#fd8d3c' },
        'Baja':   { 'Abierto': '#1a9641', 'Resuelto': '#006837' },
        'Sin prioridad': { 'Abierto': '#999', 'Resuelto': '#666' },
        'Sin estado': { 'Abierto': '#999', 'Resuelto': '#666' }
    };

    let marcadores = [];

    function cargarReportes() {
        fetch('get_reportes.php')
            .then(res => res.json())
            .then(data => {
                marcadores.forEach(m => map.removeLayer(m));
                marcadores = [];

                data.forEach(reporte => {
                    const color = colores[reporte.prioridad]?.[reporte.estado] || '#666';

                    const marker = L.circleMarker([reporte.latitud, reporte.longitud], {
                        radius: 8,
                        fillColor: color,
                        color: '#000',
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(map);

                    marker.bindPopup(reporte.popup);
                    marcadores.push(marker);
                });
            });
    }

    function filtrar(tipo) {
        fetch('get_reportes.php')
            .then(res => res.json())
            .then(data => {
                marcadores.forEach(m => map.removeLayer(m));
                marcadores = [];

                data.forEach(reporte => {
                    if (
                        tipo === 'todos' ||
                        (tipo === 'abiertos' && reporte.estado === 'Abierto') ||
                        (tipo === 'resueltos' && reporte.estado === 'Resuelto')
                    ) {
                        const color = colores[reporte.prioridad]?.[reporte.estado] || '#666';

                        const marker = L.circleMarker([reporte.latitud, reporte.longitud], {
                            radius: 8,
                            fillColor: color,
                            color: '#000',
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(map);

                        marker.bindPopup(reporte.popup);
                        marcadores.push(marker);
                    }
                });
            });
    }

    cargarReportes();
</script>

</body>
</html>