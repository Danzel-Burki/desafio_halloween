<?php
// conexion.php
$host = 'localhost'; // Dirección del servidor de la base de datos
$usuario = 'root'; // Usuario de la base de datos
$contraseña = ''; // Contraseña del usuario (deja vacío si no hay)
$nombre_base_datos = 'halloween'; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Comprobar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres
$conn->set_charset("utf8");

// Aquí puedes agregar más consultas o lógica si es necesario

// Cerrar la conexión al final del script
// $conn->close(); // Descomenta esta línea si quieres cerrar la conexión manualmente
?>
