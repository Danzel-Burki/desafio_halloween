<?php
// Incluir la conexión a la base de datos
include '../includes/conexion.php'; // Ajusta la ruta si es necesario

// Verificar si se envió el formulario con el nombre de usuario
if (isset($_POST['usuario'])) {
    $usuario = $_POST['usuario'];

    // Verificar si la conexión es válida
    if ($conn) {
        // Cambiar el nombre de la columna a "nombre_usuario" si ese es el nombre correcto en la base de datos
        $sql = "UPDATE usuarios SET is_admin = 0 WHERE nombre_usuario = '$usuario'";

        // Ejecutar la consulta y verificar si fue exitosa
        if ($conn->query($sql) === TRUE) {
            // Redirigir al usuario al index.php después de la acción
            header("Location: ../index.php");
            exit(); // Asegúrate de detener el script después de la redirección
        } else {
            echo "Error al actualizar el rol: " . $conn->error;
        }
    } else {
        echo "Error: No se pudo conectar a la base de datos.";
    }
}
?>
