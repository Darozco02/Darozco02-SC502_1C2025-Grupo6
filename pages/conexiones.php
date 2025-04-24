<?php

$hostname = "localhost";
$username = "root";
$password = "";  //Ingresar la contraseña de la base de datos
$database = "proyectodb";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn-> connect_error) {
    echo "Error de conexión <br>";
} else {
    echo "Conexion OKAY <br>";
}

//Ingresar codigo para agregar datos a la base de datos
