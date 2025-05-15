<?php
session_start();
require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['username'];
    $contrasena = $_POST['password'];

    // Consultar el usuario
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuarios = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verificar contraseña
        if (password_verify($contrasena, $user['clave'])) {
            // Verificar si la cuenta está activada
            if ($user['verificado'] == 1) {
                $_SESSION['usuario'] = $usuario;
                header("Location: bienvenida.php");
                exit();
            } else {
                echo "<p style='color:orange;'>⚠️ Por favor verifica tu correo antes de iniciar sesión.</p>";
                echo "<br><a href='index.html'>Volver al Login</a>";
            }
        } else {
            // Contraseña incorrecta
            echo "<p style='color:red;'>❌ Usuario o contraseña incorrectos.</p>";
            mostrarBotonRegistro();
        }
    } else {
        // Usuario no encontrado
        echo "<p style='color:red;'>❌ El usuario no existe.</p>";
        mostrarBotonRegistro();
    }
}

// Función para mostrar botón de registro
function mostrarBotonRegistro() {
    echo "<form action='registro.html' method='get'>
            <button type='submit'>Registrarse</button>
          </form>";
    echo "<br><a href='index.html'>Volver al Login</a>";
}
?>

