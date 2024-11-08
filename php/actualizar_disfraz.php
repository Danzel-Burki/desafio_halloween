<?php
session_start();
include '../includes/conexion.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "No tienes permiso para realizar esta acción.";
    exit;
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se ha enviado el ID del disfraz
    if (isset($_POST['disfraz_id'])) {
        $disfraz_id = $_POST['disfraz_id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $imagen = $_FILES['imagen']['tmp_name'];

        // Actualizar el disfraz en la base de datos
        if (!empty($imagen)) {
            // Si se cargó una nueva imagen, también actualizarla
            $imagenData = file_get_contents($imagen);
            $sql = "UPDATE disfraces SET nombre = ?, descripcion = ?, imagen = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssbi", $nombre, $descripcion, $imagenData, $disfraz_id);
        } else {
            // Si no se cargó nueva imagen, solo actualizar nombre y descripción
            $sql = "UPDATE disfraces SET nombre = ?, descripcion = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nombre, $descripcion, $disfraz_id);
        }

        if ($stmt->execute()) {
            $_SESSION['message'] = "Disfraz actualizado exitosamente.";
        } else {
            $_SESSION['message'] = "Error al actualizar el disfraz.";
        }

        // Redirigir al index después de la actualización
        header("Location: ../index.php");
        exit();
    } else {
        echo "No se ha especificado ningún disfraz.";
        exit();
    }
}
?>
