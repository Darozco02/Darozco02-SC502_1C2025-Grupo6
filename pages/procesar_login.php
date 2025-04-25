<?php
session_start();
require_once 'conexiones.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $password_guardada = $usuario['password'];

        // Primero intenta verificar como contraseña encriptada
        if (password_verify($password, $password_guardada)) {
            $autenticado = true;
        }
        // Si no es encriptada, compara como texto plano
        elseif ($password === $password_guardada) {
            $autenticado = true;
        } else {
            $autenticado = false;
        }

        if ($autenticado) {
            $_SESSION['usuario'] = [
                'id_usuario' => $usuario['id_usuario'],
                'nombre' => $usuario['nombre'],
                'es_admin' => $usuario['es_admin']
            ];
            header('Location: mapa_reportes.php');
            exit;
        }
    }

    // Si llega aquí es porque falló
    header('Location: login.php?error=Correo%20o%20contraseña%20incorrectos');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>