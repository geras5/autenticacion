<?php
require 'conexion.php';

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE codigo_verificacion = ? AND verificado = 0");
    $stmt->execute([$codigo]);

    if ($stmt->rowCount() == 1) {
        $update = $conexion->prepare("UPDATE usuarios SET verificado = 1 WHERE codigo_verificacion = ?");
        $update->execute([$codigo]);
        echo "Cuenta verificada correctamente. <a href='index.html'>Inicia sesión</a>";
    } else {
        echo "Código inválido o ya verificado.";
    }
}
?>
