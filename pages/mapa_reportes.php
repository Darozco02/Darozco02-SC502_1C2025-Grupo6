<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$es_admin = $_SESSION['usuario']['es_admin'];
$nombre_usuario = $_SESSION['usuario']['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mapa de Reportes Viales</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map { height: 500px; width: 100%; }
        .leaflet-popup-content p { margin: 4px 0; }
    </style>
</head>
<body>
<!-- Barra de navegación superior -->
<nav class="navbar navbar-light bg-light px-3">
    <span class="navbar-text">
      Bienvenido, <b><?= htmlspecialchars($nombre_usuario) ?></b>
    </span>
    <a href="logout.php" class="btn btn-outline-secondary btn-sm">Cerrar sesión</a>
</nav>

<div class="container mt-3">
    <!-- Título y botones de acciones -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="mb-0">Mapa de Reportes</h3>
        <div>
            <!-- Botón Nuevo Reporte que abre el modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReporte">Nuevo Reporte</button>
            <!-- Botón para actualizar mapa manualmente -->
            <?php if ($es_admin): ?>
                <button id="refreshBtn" class="btn btn-secondary">Actualizar Mapa</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contenedor del mapa -->
    <div id="map"></div>
</div>

<!-- Modal de Nuevo Reporte -->
<div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formReporte" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalReporteLabel">Nuevo Reporte de Avería</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del problema</label>
                        <input type="text" name="descripcion" id="descripcion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_problema" class="form-label">Tipo de problema</label>
                        <select name="id_problema" id="id_problema" class="form-select" required>
                            <option value="1">Hueco</option>
                            <option value="2">Alcantarilla tapada</option>
                            <option value="3">Semáforo dañado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_prioridad" class="form-label">Prioridad</label>
                        <select name="id_prioridad" id="id_prioridad" class="form-select" required>
                            <!-- Ajustado para que 1=Alta, 2=Media, 3=Baja -->
                            <option value="1">Alta (Roja)</option>
                            <option value="2" selected>Media (Amarilla)</option>
                            <option value="3">Baja (Verde)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="imagen" class="form-label">Imagen (opcional)</label>
                        <input type="file" name="imagen" id="imagen" class="form-control" accept=".jpg, .jpeg, .png">
                    </div>
                    <p class="text-muted small">* Haga clic en el mapa para seleccionar la ubicación.</p>
                    <div class="mb-3">
                        <label for="latitud" class="form-label">Latitud</label>
                        <input type="text" name="latitud" id="latitud" class="form-control" readonly required>
                    </div>
                    <div class="mb-3">
                        <label for="longitud" class="form-label">Longitud</label>
                        <input type="text" name="longitud" id="longitud" class="form-control" readonly required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Reporte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts de Leaflet y Bootstrap JS Bundle -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const esAdmin = <?= $es_admin ? 'true' : 'false'; ?>;
    const map = L.map('map').setView([9.935, -84.091], 13);

    // Capa de mapas (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Grupo de marcadores
    let markersLayer = L.layerGroup().addTo(map);
    let tempMarker = null;

    // Manejar clic en el mapa para selección de coordenadas
    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        // Colocar marcador temporal
        if (tempMarker) {
            map.removeLayer(tempMarker);
        }
        tempMarker = L.circleMarker([lat, lng], {color: 'blue', radius: 6}).addTo(map);
        // Llenar campos de coordenadas en el formulario
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
        // Abrir el modal automáticamente si no está abierto
        // (opcional: solo si deseamos que el click abra el modal)
        // new bootstrap.Modal(document.getElementById('modalReporte')).show();
    });

    // Cargar todos los reportes y mostrarlos en el mapa
    async function loadReportes() {
        try {
            const response = await fetch('get_reportes.php');
            const data = await response.json();
            // Limpiar capa de marcadores
            markersLayer.clearLayers();
            // Recorrer cada reporte y agregar al mapa
            data.forEach(rep => {
                const lat = parseFloat(rep.latitud);
                const lng = parseFloat(rep.longitud);
                // Elegir color según estado: verde = resuelto, rojo = pendiente/en proceso
                let color = (rep.estado.toLowerCase() === 'resuelto') ? 'green' : 'red';
                // Crear marcador (círculo) con el color correspondiente
                const marker = L.circleMarker([lat, lng], {
                    color: color, fillColor: color, radius: 8
                }).addTo(markersLayer);
                // Construir contenido del popup
                let popupContent = `<p><b>${rep.tipo}</b><br>${rep.descripcion}</p>
                              <p>Prioridad: ${rep.prioridad}<br>Estado: ${rep.estado}</p>`;
                if (rep.imagen_url) {
                    popupContent += `<img src="${rep.imagen_url}" alt="Evidencia" style="max-width:100%; height:auto;">`;
                }
                // Si es admin, agregar botones de acciones
                if (esAdmin) {
                    popupContent += `<p class="mt-2 text-center">
                                <button class="btn btn-sm btn-success me-2" onclick="resolverReporte(${rep.id_reporte})">Marcar Resuelto</button>
                                <select onchange="cambiarPrioridad(${rep.id_reporte}, this.value)" class="form-select form-select-sm d-inline-block w-auto">
                                  <option disabled selected>Cambiar Prioridad...</option>
                                  <option value="1">Alta</option>
                                  <option value="2">Media</option>
                                  <option value="3">Baja</option>
                                </select>
                              </p>`;
                }
                marker.bindPopup(popupContent);
            });
        } catch (error) {
            console.error('Error cargando reportes:', error);
        }
    }

    // Función para resolver reporte (llama vía AJAX al backend)
    async function resolverReporte(idReporte) {
        if (!confirm('¿Confirma marcar este reporte como resuelto?')) return;
        try {
            const resp = await fetch(`resolver_reporte.php?id=${idReporte}`, { method: 'GET', headers: {'X-Requested-With': 'XMLHttpRequest'} });
            if (resp.ok) {
                // Refrescar marcadores en mapa
                loadReportes();
            } else {
                alert('No se pudo resolver el reporte (estatus HTTP ' + resp.status + ')');
            }
        } catch (error) {
            console.error('Error resolviendo reporte:', error);
        }
    }

    // Función para cambiar prioridad de un reporte
    async function cambiarPrioridad(idReporte, nuevaPrioridad) {
        try {
            const resp = await fetch(`cambiar_prioridad.php?id=${idReporte}&priority=${nuevaPrioridad}`, { method: 'GET', headers: {'X-Requested-With': 'XMLHttpRequest'} });
            if (resp.ok) {
                loadReportes();
            } else {
                alert('No se pudo cambiar la prioridad (estatus HTTP ' + resp.status + ')');
            }
        } catch (error) {
            console.error('Error cambiando prioridad:', error);
        }
    }

    // Manejar envío del formulario de nuevo reporte vía AJAX
    document.getElementById('formReporte').addEventListener('submit', async function(e) {
        e.preventDefault();
        // Validar que se haya seleccionado ubicación
        if (!document.getElementById('latitud').value || !document.getElementById('longitud').value) {
            alert('Por favor seleccione una ubicación en el mapa antes de enviar el reporte.');
            return;
        }
        // Preparar datos del formulario
        const formData = new FormData(this);
        try {
            const resp = await fetch('nuevo_reporte.php', { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
            if (resp.ok) {
                // Cerrar modal y limpiar marcador temporal
                const modalEl = document.getElementById('modalReporte');
                bootstrap.Modal.getInstance(modalEl).hide();
                if (tempMarker) {
                    map.removeLayer(tempMarker);
                    tempMarker = null;
                }
                this.reset();
                // Recargar marcadores para incluir el nuevo
                loadReportes();
            } else {
                alert('Error al enviar el reporte. Intente de nuevo.');
            }
        } catch (error) {
            console.error('Error enviando nuevo reporte:', error);
        }
    });

    // Botón manual de refrescar mapa (visible solo para admin)
    const refreshBtn = document.getElementById('refreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', loadReportes);
    }

    // Cargar los reportes al iniciar la página
    loadReportes();
</script>
</body>
</html>