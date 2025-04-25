<?php
session_start();
require_once 'conexiones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['usuario'] = [
            'id_usuario' => $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'es_admin' => $usuario['es_admin']
        ];
        header("Location: mapa_reportes.php");
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
<h1>Iniciar sesión</h1>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post" action="login.php">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Contraseña:</label><br>
    <input type="password" name="password" required><br><br>
    <input type="submit" value="Ingresar">
</form>
</body>
</html>