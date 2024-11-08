<?php
session_start();
require '../includes/conexion.php';

if (!isset($_SESSION["is_admin"]) || !$_SESSION["is_admin"]) {
    header("Location: index.php?error=acceso_denegado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

    <header>
        <h1>Panel de Administración</h1>
    </header>

    <main>
        <h2>Agregar Nuevo Disfraz</h2>
        <form action="procesar_disfraz.php" method="POST" enctype="multipart/form-data">
            <label for="disfraz-nombre">Nombre del Disfraz:</label>
            <input type="text" id="disfraz-nombre" name="disfraz-nombre" required>

            <label for="disfraz-descripcion">Descripción del Disfraz:</label>
            <textarea id="disfraz-descripcion" name="disfraz-descripcion" required></textarea>

            <label for="disfraz-foto">Foto:</label>
            <input type="file" id="disfraz-foto" name="disfraz-foto" accept="image/*" required>

            <button type="submit">Agregar Disfraz</button>
        </form>
    </main>
</body>
</html>
