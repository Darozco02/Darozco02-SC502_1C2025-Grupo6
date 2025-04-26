<?php
session_start();
require_once 'conexiones.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT 
            r.id_reporte AS id,
            r.descripcion,
            r.latitud,
            r.longitud,
            r.imagen_url,
            COALESCE(p.nombre, 'Sin prioridad') AS prioridad,
            COALESCE(e.nombre, 'Sin estado') AS estado,
            r.id_estado,
            r.id_prioridad
        FROM reportes r
        LEFT JOIN prioridades p ON r.id_prioridad = p.id
        LEFT JOIN estados e ON r.id_estado = e.id
    ");

    $reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reportes as &$reporte) {
        $contenido = "<strong>{$reporte['descripcion']}</strong><br>";
        if (!empty($reporte['imagen_url'])) {
            $contenido .= "<img src='{$reporte['imagen_url']}' width='100'><br>";
        }
        $contenido .= "Prioridad: {$reporte['prioridad']}<br>";
        $contenido .= "Estado: {$reporte['estado']}<br>";

        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin') {
            $contenido .= "
                <form method='POST' action='cambiar_estado.php'>
                    <input type='hidden' name='id_reporte' value='{$reporte['id']}'>
                    <label>Estado:
                        <select name='id_estado'>
                            <option value='1' " . ($reporte['id_estado'] == 1 ? "selected" : "") . ">Abierto</option>
                            <option value='2' " . ($reporte['id_estado'] == 2 ? "selected" : "") . ">Resuelto</option>
                        </select>
                    </label><br>
                    <label>Prioridad:
                        <select name='id_prioridad'>
                            <option value='1' " . ($reporte['id_prioridad'] == 1 ? "selected" : "") . ">Alta</option>
                            <option value='2' " . ($reporte['id_prioridad'] == 2 ? "selected" : "") . ">Media</option>
                            <option value='3' " . ($reporte['id_prioridad'] == 3 ? "selected" : "") . ">Baja</option>
                        </select>
                    </label><br>
                    <button type='submit'>Actualizar</button>
                </form>";
        }

        $reporte['popup'] = $contenido;
    }

    echo json_encode($reportes);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la consulta: " . $e->getMessage()]);
}