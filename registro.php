<?php
/*require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $usuario = trim($_POST['username']);
    $contrasena = trim($_POST['password']);

    // Verifica si el usuario ya existe
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuarios = ?");
    $stmt->execute([$usuario]);

    if ($stmt->rowCount() > 0) {
        echo "El nombre de usuario ya está en uso. <a href='registro.html'>Intentar de nuevo</a>";
    } else {
        // Encriptar contraseña
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar todos los datos
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombres, apellidos, correo, usuarios, clave) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombres, $apellidos, $correo, $usuario, $hash])) {
            echo "Registro exitoso. <a href='index.html'>Iniciar sesion</a>";
        } else {
            echo "Error al registrar el usuario.";
        }
    }
}

require 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $usuario = trim($_POST['username']);
    $contrasena = trim($_POST['password']);

    // Verifica si el usuario ya existe
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuarios = ?");
    $stmt->execute([$usuario]);

    if ($stmt->rowCount() > 0) {
        echo "El nombre de usuario ya existe. <a href='registro.html'>Intentar de nuevo</a>";
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $codigo = bin2hex(random_bytes(16)); // código único

        $stmt = $conexion->prepare("INSERT INTO usuarios (nombres, apellidos, correo, usuarios, clave, codigo_verificacion) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombres, $apellidos, $correo, $usuario, $hash, $codigo])) {

            // Enlace de verificación
            $link = "http://localhost/Autenticacion/verificar.php?codigo=$codigo";

            $asunto = "Verifica tu cuenta";
            $mensaje = "Hola $nombres, haz clic en el siguiente enlace para verificar tu cuenta:\n\n$link";
            $cabeceras = "From: noreply@tusitio.com";

            // Enviar correo (puedes usar PHPMailer si no funciona mail())
            if (mail($correo, $asunto, $mensaje, $cabeceras)) {
                echo "Registro exitoso. Revisa tu correo para verificar tu cuenta.";
            } else {
                echo "Error al enviar el correo de verificación.";
            }

        } else {
            echo "Error al registrar el usuario.";
        }
    }
}
*/


require 'conexion.php';
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $usuario = trim($_POST['username']);
    $contrasena = trim($_POST['password']);

    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuarios = ?");
    $stmt->execute([$usuario]);

    if ($stmt->rowCount() > 0) {
        echo "El nombre de usuario ya está en uso.";
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $codigo = bin2hex(random_bytes(16));

        $stmt = $conexion->prepare("INSERT INTO usuarios (nombres, apellidos, correo, usuarios, clave, codigo_verificacion) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombres, $apellidos, $correo, $usuario, $hash, $codigo])) {

            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // o el servidor que uses
                $mail->SMTPAuth = true;
                $mail->Username = 'erascastillogeorgealexander@gmail.com'; // tu correo
                $mail->Password = 'mpkjfnocyysftuqn'; // usa contraseña de app si es Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Receptor y remitente
                $mail->setFrom('erascastillogeorgealexander@gmail.com', 'AUTENTICACION');
                $mail->addAddress($correo, $nombres);

                $mail->isHTML(true);
                $mail->Subject = 'Verifica tu cuenta';
                $mail->Body = "Hola <b>$nombres</b>, haz clic en el siguiente enlace para verificar tu cuenta:<br><br>
                <a href='http://localhost/Autenticacion/verificar.php?codigo=$codigo'>Verificar cuenta</a>";

                $mail->send();
                echo "Registro exitoso. Revisa tu correo para verificar tu cuenta.";
            } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error al registrar el usuario.";
        }
    }
}


?>


