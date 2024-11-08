<?php
session_start();
include '../includes/conexion.php'; // Conexión a la base de datos

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    if (isset($_POST['disfraz_id'])) {
        $disfraz_id = intval($_POST['disfraz_id']);

        $sql = "DELETE FROM disfraces WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $disfraz_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Disfraz eliminado exitosamente.";
        } else {
            $_SESSION['message'] = "Error al eliminar el disfraz: " . $conn->error;
        }

        $stmt->close();
    }
} else {
    $_SESSION['message'] = "No tienes permisos para realizar esta acción.";
}

// Redireccionar al index.php
header("Location: ../index.php");
exit();
?>
