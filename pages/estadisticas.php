<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Estadísticas y Métricas</h1>
        <nav>
            <ul>
              <li><a href="dashboard.html">Inicio</a></li>
              <li><a href="reportes.html">Reportes</a></li>
              <li><a href="estadisticas.html">Estadísticas</a></li>
              <li><a href="historial.html">Historial</a></li>
              <li><a href="mapaTiempoReal.html">Mapa</a></li>
              <li><a href="dashboard.html">Admin</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <article id="cartas">
            <div class="card" style="width: 18rem; margin: 10px;">
                <div class="card-body">
                  <h5 class="card-title" style="color: #C31E35;">Prioridad Alta</h5>
                  <ul>
                    <li>3 Baches</li>
                    <li>1 Grieta</li>
                    <li>2 Hundimientos</li>
                    <li>1 Drenaje</li>
                    <li>2 Señalizacion</li>
                  </ul>
                  <a href="prioridadAlta.html" class="btn btn-primary">Ver Reporte Por Categoria</a>
                </div>
              </div>

            <div class="card" style="width: 18rem; margin: 10px;">
                <div class="card-body">
                  <h5 class="card-title" style="color: #FFCC00;">Prioridad Media</h5>
                  <ul>
                    <li>3 Baches</li>
                    <li>3 Grieta</li>
                    <li>2 Hundimientos</li>
                    <li>1 Drenaje</li>
                    <li>2 Señalizacion</li>
                  </ul>
                  <a href="prioridadMedia.html" class="btn btn-primary">Ver Reporte Por Categoria</a>
                </div>
              </div>

            <div class="card" style="width: 18rem; margin: 10px;">
                <div class="card-body">
                  <h5 class="card-title" style="color: #0B8F1C;">Prioridad Baja</h5>
                  <ul>
                    <li>2 Baches</li>
                    <li>1 Grieta</li>
                    <li>4 Hundimientos</li>
                    <li>1 Drenaje</li>
                    <li>2 Señalizacion</li>
                  </ul>
                  <a href="prioridadBaja.html" class="btn btn-primary">Ver Reporte Por Categoria</a>
                </div>
              </div>
        </article>

        <canvas id="reporteChart"></canvas>
    </main>

    <script>
        const ctx = document.getElementById('reporteChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Baches', 'Grietas', 'Hundimientos', 'Drenaje', 'Señalización'],
                datasets: [{
                    label: 'Reportes por tipo',
                    data: [10, 5, 8, 3, 6], 
                    backgroundColor: 'blue'
                }]
            }
        });
    </script>
</body>
</html>