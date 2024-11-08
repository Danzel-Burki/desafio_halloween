<?php
session_start();
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);

    // Verificar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION['message'] = 'El nombre de usuario ya está en uso.';
        header("Location: ../index.php");
        exit();
    } else {
        // Insertar el nuevo usuario
        $sql = "INSERT INTO usuarios (nombre_usuario, contraseña) VALUES ('$usuario', '$contraseña')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $usuario;
            $_SESSION['message'] = 'Bienvenido, ' . $usuario . '!';
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['message'] = 'Error al registrar usuario.';
            header("Location: ../index.php");
            exit();
        }
    }
}
?>
