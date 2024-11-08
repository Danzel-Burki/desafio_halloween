<?php
session_start();
include '../includes/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php");
    exit();
}

// Verificar si se enviaron los datos
if (isset($_POST['disfraz_id'], $_POST['nombre'], $_POST['descripcion'])) {
    $disfraz_id = $_POST['disfraz_id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Actualizar el disfraz en la base de datos
    $sql = "UPDATE disfraces SET nombre = ?, descripcion = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $descripcion, $disfraz_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Disfraz actualizado con éxito.";
    } else {
        $_SESSION['message'] = "Error al actualizar el disfraz.";
    }

    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
