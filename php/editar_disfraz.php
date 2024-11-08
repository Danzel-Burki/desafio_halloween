<?php
session_start();
include '../includes/conexion.php';

// Verificar si se ha proporcionado el ID del disfraz
if (isset($_GET['disfraz_id'])) {
    $disfraz_id = $_GET['disfraz_id'];

    // Obtener el disfraz de la base de datos
    $sql = "SELECT * FROM disfraces WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $disfraz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $disfraz = $result->fetch_assoc();
    } else {
        echo "Disfraz no encontrado.";
        exit;
    }
} else {
    echo "No se ha especificado ningún disfraz.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Disfraz</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h2>Editar Disfraz</h2>
    <form action="actualizar_disfraz.php" method="POST" enctype="multipart/form-data">
        <!-- Campo oculto para enviar el ID del disfraz -->
        <input type="hidden" name="disfraz_id" value="<?php echo htmlspecialchars($disfraz_id); ?>">

        <label for="nombre">Nombre del Disfraz:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($disfraz['nombre']); ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required><?php echo htmlspecialchars($disfraz['descripcion']); ?></textarea>

        <button type="submit">Actualizar Disfraz</button>
    </form>
</body>
</html>
