<?php
session_start();
include 'includes/conexion.php';

// Verificar si se recibió un voto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disfraz_id'])) {
    $disfraz_id = $_POST['disfraz_id'];
    $usuario = $_SESSION['username']; // Asegúrate de que el nombre de usuario esté guardado en la sesión

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
    header("Location: index.php");
    exit();
}

// Obtener disfraces desde la base de datos
$sql = "SELECT * FROM disfraces ORDER BY votos DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concurso de Disfraces de Halloween</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#disfraces-list">Ver Disfraces</a></li>
            <?php if (!isset($_SESSION["username"])): ?>
                <li><a href="#registro">Registro</a></li>
                <li><a href="#login">Iniciar Sesión</a></li>
            <?php else: ?>  
                <li><a href="./php/logout.php">Cerrar Sesión</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <li><a href="#admin">Panel de Administración</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 2): ?>
                    <li><a href="#superadmin">Panel de Super Administrador</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>

    <header>
        <h1>Concurso de Disfraces de Halloween</h1>
    </header>

    <main>
        <!-- Mostrar mensajes de sesión si existen -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        ?>

        <h2>Lista de Disfraces</h2>
        <section id="disfraces-list">
            <?php
            if ($result->num_rows > 0) {
                // Mostrar cada disfraz
                while ($row = $result->fetch_assoc()) {
                    $imagenData = base64_encode($row['imagen']);
                    $src = 'data:image/jpeg;base64,' . $imagenData;

                    echo "<div class='disfraz'>
                            <h3>" . htmlspecialchars($row['nombre']) . "</h3>
                            <p>" . htmlspecialchars($row['descripcion']) . "</p>
                            <img src='" . $src . "' class='disfraz-img' alt='Imagen de disfraz' onclick='openLightbox(this)'>";

                    // Mostrar el número de votos
                    echo "<p>Votos: " . $row['votos'] . "</p>";

                    // Mostrar botón de votar solo para usuarios no administradores
                    if (isset($_SESSION['username']) && (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] == 0)) {
                        // Verificar si el usuario ya ha votado por este disfraz
                        $sql_check_vote = "SELECT * FROM votos WHERE usuario = '" . $_SESSION['username'] . "' AND disfraz_id = " . $row['id'];
                        $voted_result = $conn->query($sql_check_vote);

                        if ($voted_result->num_rows == 0) {
                            echo "
                                <form action='./php/votar_disfraz.php' method='POST'>
                                    <input type='hidden' name='disfraz_id' value='" . $row['id'] . "'>
                                    <button type='submit'>¡Votar por este disfraz!</button>
                                </form>";
                        } else {
                            echo "<p>Ya has votado por este disfraz.</p>";
                        }
                    }

                    // Mostrar botones de editar y borrar si es un administrador
                    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
                        echo "
                            <form action='./php/editar_disfraz.php' method='GET' style='display:inline;'>
                                <input type='hidden' name='disfraz_id' value='" . $row['id'] . "'>
                                <button type='submit'>Editar</button>
                            </form>
                            <form action='./php/borrar_disfraz.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='disfraz_id' value='" . $row['id'] . "'>
                                <button type='submit' onclick='return confirm(\"¿Estás seguro de que quieres borrar este disfraz?\");'>Borrar</button>
                            </form>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<p>No hay disfraces aún.</p>";
            }
            ?>
        </section>

        <!-- Formulario de Registro -->
        <?php if (!isset($_SESSION['username'])): ?>
        <section id="registro">
            <h2>Registro</h2>
            <form action="./php/procesar_registro.php" method="POST">
                <label for="usuario">Nombre de usuario:</label>
                <input type="text" name="usuario" id="usuario" required>
                
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" required>

                <button type="submit">Registrarse</button>
            </form>
        </section>
        <?php endif; ?>

        <!-- Formulario de Login -->
        <?php if (!isset($_SESSION['username'])): ?>
        <section id="login">
            <h2>Iniciar sesión</h2>
            <form action="./php/procesar_login.php" method="POST">
                <label for="usuario">Nombre de usuario:</label>
                <input type="text" name="usuario" id="usuario" required>

                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" required>

                <button type="submit">Iniciar sesión</button>
            </form>
        </section>
        <?php endif; ?>

        <!-- Sección de administración (solo para admins) -->
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
        <section id="admin">
            <h2>Panel de Administración</h2>
            <h3>Agregar un nuevo disfraz</h3>
            <form action="./php/procesar_disfraz.php" method="POST" enctype="multipart/form-data">
                <label for="disfraz-nombre">Nombre del Disfraz:</label>
                <input type="text" name="disfraz-nombre" id="disfraz-nombre" required>

                <label for="disfraz-descripcion">Descripción del Disfraz:</label>
                <textarea name="disfraz-descripcion" id="disfraz-descripcion" required></textarea>

                <label for="disfraz-foto">Foto del Disfraz:</label>
                <input type="file" name="disfraz-foto" id="disfraz-foto" required>

                <button type="submit">Agregar Disfraz</button>
            </form>
        </section>
        <?php endif; ?>

        <!-- Sección de administración (solo para superadmins) -->
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 2): ?>
        <section id="superadmin">
            <h3>Nombrar Administrador</h3>
            <form action="./php/nombrar_admin.php" method="POST">
                <label for="usuario">Nombre de Usuario:</label>
                <input type="text" name="usuario" id="usuario" required>
                <button type="submit">Nombrar Admin</button>
            </form>

            <h3>Degradar Administrador</h3>
            <form action="./php/degradar_admin.php" method="POST">
                <label for="usuario">Nombre de Usuario:</label>
                <input type="text" name="usuario" id="usuario" required>
                <button type="submit">Degradar Admin</button>
            </form>
        </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>Concurso de Disfraces - Todos los derechos reservados &copy; 2024</p>
    </footer>

    <script>
        function openLightbox(image) {
            // Crear un lightbox simple para ver las imágenes
            const lightbox = document.createElement('div');
            lightbox.id = 'lightbox';
            lightbox.style.position = 'fixed';
            lightbox.style.top = '0';
            lightbox.style.left = '0';
            lightbox.style.width = '100vw';
            lightbox.style.height = '100vh';
            lightbox.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            lightbox.style.display = 'flex';
            lightbox.style.justifyContent = 'center';
            lightbox.style.alignItems = 'center';

            const img = document.createElement('img');
            img.src = image.src;
            img.style.maxWidth = '90%';
            img.style.maxHeight = '90%';

            lightbox.appendChild(img);
            document.body.appendChild(lightbox);

            lightbox.addEventListener('click', () => {
                lightbox.remove();
            });
        }
    </script>
</body>
</html>
