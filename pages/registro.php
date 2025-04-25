<?php
require_once 'conexiones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $es_admin = isset($_POST['es_admin']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, es_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $password, $es_admin]);
        echo "Usuario registrado exitosamente.";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error al registrar: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Registro</title></head>
<body>
<h1>Registro de Usuario</h1>
<form method="post">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br>
    <label>¿Es administrador?</label>
    <input type="checkbox" name="es_admin"><br><br>
    <input type="submit" value="Registrar">
</form>
</body>
</html>