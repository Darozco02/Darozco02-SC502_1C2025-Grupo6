<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa Interactivo de Reportes Viales</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #map { height: 600px; margin-top: 20px; }
    </style>
</head>
<body class="container py-3">

<h2 class="text-center">Mapa Interactivo de Reportes Viales</h2>
<div id="map"></div>

<!-- Modal de nuevo reporte -->
<div class="modal fade" id="reporteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="nuevo_reporte.php" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Reporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="latitud" id="latitud">
                <input type="hidden" name="longitud" id="longitud">

                <div class="mb-3">
                    <label class="form-label">Tipo de problema</label>
                    <input type="text" name="tipo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prioridad</label>
                    <select name="prioridad" class="form-select" required>
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Reporte</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const map = L.map('map').setView([9.9333, -84.0833], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data © OpenStreetMap'
    }).addTo(map);

    // Cargar reportes existentes
    fetch('get_reportes.php')
        .then(res => res.json())
        .then(data => {
            data.forEach(rep => {
                const marker = L.marker([rep.latitud, rep.longitud]).addTo(map);
                let popup = `<b>${rep.tipo || 'Reporte'}</b><br>
                     Estado: ${rep.estado || 'N/A'}<br>
                     Prioridad: ${rep.prioridad || 'N/A'}<br>
                     ${rep.descripcion || ''}`;
                marker.bindPopup(popup);
            });
        });

    // Clic en mapa → abrir modal con coordenadas
    map.on('click', function(e) {
        document.getElementById('latitud').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitud').value = e.latlng.lng.toFixed(6);
        const modal = new bootstrap.Modal(document.getElementById('reporteModal'));
        modal.show();
    });
</script>

</body>
</html>