<?php
session_start();
include '../includes/conexion.php';

// Verificar si se recibió un voto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disfraz_id'])) {
    $disfraz_id = $_POST['disfraz_id'];
    $usuario = $_SESSION['username'];  // Asegúrate de que el nombre de usuario esté guardado en la sesión

    // Verificar si el usuario ya ha votado por este disfraz
    $sql_check_vote = "SELECT * FROM votos WHERE usuario = '$usuario' AND disfraz_id = $disfraz_id";
    $voted_result = $conn->query($sql_check_vote);

    if ($voted_result->num_rows == 0) {
        // Insertar el voto en la base de datos
        $sql_vote = "INSERT INTO votos (usuario, disfraz_id) VALUES ('$usuario', $disfraz_id)";
        if ($conn->query($sql_vote) === TRUE) {
            // Actualizar el contador de votos en la tabla de disfraces
            $sql_update_votes = "UPDATE disfraces SET votos = votos + 1 WHERE id = $disfraz_id";
            $conn->query($sql_update_votes);

            $_SESSION['message'] = 'Gracias por tu voto!';
        } else {
            $_SESSION['message'] = 'Hubo un error al registrar tu voto.';
        }
    } else {
        $_SESSION['message'] = 'Ya has votado por este disfraz.';
    }

    // Redirigir al index.php
    header("Location: ../index.php");
    exit();
}
?>
