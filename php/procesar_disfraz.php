<?php
session_start();
include '../includes/conexion.php';

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['disfraz-foto'])) {
        $nombre = mysqli_real_escape_string($conn, $_POST['disfraz-nombre']);
        $descripcion = mysqli_real_escape_string($conn, $_POST['disfraz-descripcion']);
        $imagen = file_get_contents($_FILES['disfraz-foto']['tmp_name']);

        // Insertar disfraz en la base de datos
        $sql = "INSERT INTO disfraces (nombre, descripcion, imagen) VALUES ('$nombre', '$descripcion', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $imagen);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Disfraz agregado exitosamente!';
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['message'] = 'Error al agregar el disfraz.';
            header("Location: ../index.php");
            exit();
        }
    }
}
?>
