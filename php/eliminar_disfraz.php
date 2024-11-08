<?php
session_start();
include '../includes/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit();
}

// Verificar si se envió el ID del disfraz
if (isset($_POST['disfraz_id'])) {
    $disfraz_id = $_POST['disfraz_id'];

    // Eliminar el disfraz de la base de datos
    $sql = "DELETE FROM disfraces WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $disfraz_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Disfraz borrado con éxito.";
    } else {
        $_SESSION['message'] = "Error al borrar el disfraz.";
    }

    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
