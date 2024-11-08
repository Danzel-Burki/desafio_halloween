<?php
session_start();
include '../includes/conexion.php';

// Verificar si el usuario es superadmin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 2) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usuario'])) {
        $usuario = $_POST['usuario'];

        // Verificar si el usuario existe
        $sql_check_user = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
        $result = $conn->query($sql_check_user);

        if ($result->num_rows > 0) {
            // Nombrar como admin o degradar a usuario
            $row = $result->fetch_assoc();
            $nuevo_rol = ($row['is_admin'] == 1) ? 0 : 1;  // Alternar entre admin (1) y usuario (0)

            // Actualizar el rol en la base de datos
            $sql_update_role = "UPDATE usuarios SET is_admin = $nuevo_rol WHERE nombre_usuario = '$usuario'";
            if ($conn->query($sql_update_role) === TRUE) {
                $_SESSION['message'] = $nuevo_rol == 1 ? 'Usuario nombrado como Administrador.' : 'Usuario degradado a Usuario normal.';
            } else {
                $_SESSION['message'] = 'Error al actualizar el rol del usuario.';
            }
        } else {
            $_SESSION['message'] = 'El usuario no existe.';
        }
    }
} else {
    $_SESSION['message'] = 'No tienes permiso para realizar esta acción.';
}

// Redirigir de vuelta a la página del superadmin
header("Location: ../index.php#superadmin");
exit();
?>
