<?php
session_start();
require_once 'conexiones.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$es_admin = $_SESSION['usuario']['es_admin'];
$nombre_usuario = $_SESSION['usuario']['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Reportes Viales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <style>
        #map { height: 600px; width: 100%; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-light bg-light px-3">
    <span class="navbar-text">
        Bienvenido, <b><?= htmlspecialchars($nombre_usuario) ?></b>
    </span>
    <a href="logout.php" class="btn btn-outline-secondary btn-sm">Cerrar sesión</a>
</nav>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Mapa de Reportes</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReporte">Nuevo Reporte</button>
    </div>
    <div id="map"></div>
</div>

<!-- Modal para Nuevo Reporte -->
<div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formReporte" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReporteLabel">Nuevo Reporte de Avería</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de Problema</label>
                    <select name="id_problema" class="form-select" required>
                        <option value="1">Hueco</option>
                        <option value="2">Alcantarilla Tapada</option>
                        <option value="3">Semáforo Dañado</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prioridad</label>
                    <select name="id_prioridad" class="form-select" required>
                        <option value="1">Alta</option>
                        <option value="2" selected>Media</option>
                        <option value="3">Baja</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Imagen (opcional)</label>
                    <input type="file" name="imagen" class="form-control" accept="image/jpeg, image/png">
                </div>
                <div class="mb-3">
                    <label class="form-label">Latitud</label>
                    <input type="text" name="latitud" id="latitud" class="form-control" readonly required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Longitud</label>
                    <input type="text" name="longitud" id="longitud" class="form-control" readonly required>
                </div>
                <div class="alert alert-info small">
                    Haga clic en el mapa para seleccionar ubicación.
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Reportar</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
    const map = L.map('map').setView([9.935, -84.091], 13);
    const esAdmin = <?= $es_admin ? 'true' : 'false' ?>;
    let markersLayer = L.layerGroup().addTo(map);
    let tempMarker = null;

    // Cargar capa base
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Activar barra de búsqueda
    L.Control.geocoder({
        defaultMarkGeocode: true
    }).addTo(map);

    // Al hacer clic en mapa, capturar lat/lng
    map.on('click', function(e) {
        const { lat, lng } = e.latlng;
        document.getElementById('latitud').value = lat.toFixed(6);
        document.getElementById('longitud').value = lng.toFixed(6);

        if (tempMarker) {
            map.removeLayer(tempMarker);
        }
        tempMarker = L.marker([lat, lng]).addTo(map);
    });

    // Cargar todos los reportes
    async function loadReportes() {
        try {
            const response = await fetch('get_reportes.php');
            const data = await response.json();

            markersLayer.clearLayers();
            data.forEach(rep => {
                const lat = parseFloat(rep.latitud);
                const lng = parseFloat(rep.longitud);
                const color = (rep.estado.toLowerCase() === 'resuelto') ? 'green' : 'red';

                const marker = L.circleMarker([lat, lng], {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.8,
                    radius: 8
                }).addTo(markersLayer);

                let popupContent = `<b>${rep.tipo}</b><br>${rep.descripcion}<br>Prioridad: ${rep.prioridad}<br>Estado: ${rep.estado}<br>`;

                if (rep.imagen_url) {
                    popupContent += `<img src="${window.location.origin}/${rep.imagen_url}" style="max-width:100%; height:auto;" alt="Evidencia">`;
                }

                if (esAdmin) {
                    popupContent += `<br><button class="btn btn-sm btn-success mt-2" onclick="resolverReporte(${rep.id_reporte})">Marcar Resuelto</button>
                                 <select class="form-select form-select-sm mt-2" onchange="cambiarPrioridad(${rep.id_reporte}, this.value)">
                                   <option disabled selected>Cambiar Prioridad</option>
                                   <option value="1">Alta</option>
                                   <option value="2">Media</option>
                                   <option value="3">Baja</option>
                                 </select>`;
                }

                marker.bindPopup(popupContent);
            });
        } catch (error) {
            console.error('Error cargando reportes:', error);
        }
    }

    // Resolver reporte
    async function resolverReporte(id) {
        if (!confirm('¿Confirmar marcar como resuelto?')) return;
        const resp = await fetch(`resolver_reporte.php?id=${id}`, { method: 'GET', headers: {'X-Requested-With': 'XMLHttpRequest'} });
        if (resp.ok) {
            loadReportes();
        } else {
            alert('Error resolviendo reporte.');
        }
    }

    // Cambiar prioridad
    async function cambiarPrioridad(id, prioridad) {
        const resp = await fetch(`cambiar_prioridad.php?id=${id}&priority=${prioridad}`, { method: 'GET', headers: {'X-Requested-With': 'XMLHttpRequest'} });
        if (resp.ok) {
            loadReportes();
        } else {
            alert('Error cambiando prioridad.');
        }
    }

    // Enviar nuevo reporte
    document.getElementById('formReporte').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const resp = await fetch('nuevo_reporte.php', { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });

        if (resp.ok) {
            bootstrap.Modal.getInstance(document.getElementById('modalReporte')).hide();
            if (tempMarker) {
                map.removeLayer(tempMarker);
                tempMarker = null;
            }
            this.reset();
            loadReportes();
        } else {
            alert('Error enviando reporte.');
        }
    });

    // Cargar reportes inicial
    loadReportes();
</script>
</body>
</html>