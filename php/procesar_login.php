<?php
session_start();
include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);

    // Verificar si el usuario existe y la contraseña es correcta
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$usuario' AND contraseña = '$contraseña'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $usuario;

        // Verificar si es administrador
        $row = $result->fetch_assoc();
        $_SESSION['is_admin'] = $row['is_admin'];

        $_SESSION['message'] = 'Bienvenido, ' . $usuario . '!';
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['message'] = 'Usuario o contraseña incorrectos.';
        header("Location: ../index.php");
        exit();
    }
}
?>
