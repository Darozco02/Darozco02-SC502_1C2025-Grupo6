<?php
session_start();
require_once 'conexiones.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=Debe completar todos los campos");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario["password"])) {
            $_SESSION["usuario"] = [
                "id_usuario" => $usuario["id_usuario"],
                "email" => $usuario["email"],
                "rol" => $usuario["rol"] // ⚠️ IMPORTANTE: esto es clave para controlar acceso admin
            ];
            header("Location: mapa_reportes.php");
            exit;
        } else {
            header("Location: login.php?error=Credenciales incorrectas");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: login.php?error=Error en el servidor");
        exit;
    }
} else {
    header("Location: login.php?error=Acceso no permitido");
    exit;
}