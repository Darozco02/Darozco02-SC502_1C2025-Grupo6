<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reportes</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Gestión de Reportes</h1>
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
        <h2>Nuevo Reporte</h2>
        <form id="reporteForm">
            <label for="tipo">Tipo de incidente:</label>
            <select id="tipo" name="tipo">
                <option value="bache">Bache</option>
                <option value="grieta">Grieta</option>
                <option value="hundimiento">Hundimiento</option>
                <option value="drenaje">Drenaje</option>
                <option value="senializacion">Señalización</option>
            </select>

            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" required>

            <label for="descripcionReporte">Descripción:</label>
            <input type="text" id="descripcionReporte" name="descripcionReporte" required>

            <label for="prioridad">Prioridad:</label>
            <select id="prioridad" name="prioridad">
                <option value="bajo">Bajo</option>
                <option value="medio">Medio</option>
                <option value="alto">Alto</option>
            </select>
            <br>
            <button type="submit">Ingresar Reporte</button>
            </form>
        </main>
</body>
</html>