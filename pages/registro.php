<?php
session_start();
require_once 'conexiones.php';

// Si ya está logueado, redirigir a mapa
if (isset($_SESSION['usuario'])) {
    header('Location: mapa_reportes.php');
    exit;
}

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $telefono = trim($_POST['telefono']) ?? '';
    $es_admin = isset($_POST['es_admin']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, apellido, email, password, telefono, es_admin) 
                               VALUES (:nombre, :apellido, :email, :password, :telefono, :es_admin)');
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':email' => $email,
            ':password' => $password,
            ':telefono' => $telefono,
            ':es_admin' => $es_admin
        ]);

        $exito = 'Usuario registrado exitosamente. ¡Ahora puedes iniciar sesión!';
    } catch (PDOException $e) {
        $error = 'Error al registrar: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Sistema de Averías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 500px;">
        <h3 class="mb-3 text-center">Registrar Nuevo Usuario</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($exito): ?>
            <div class="alert alert-success"><?= htmlspecialchars($exito) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" name="apellido" id="apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="form-control">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="es_admin" id="es_admin" class="form-check-input">
                <label for="es_admin" class="form-check-label">¿Administrador?</label>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Registrar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>