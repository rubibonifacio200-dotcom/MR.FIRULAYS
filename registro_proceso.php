<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php'; 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre    = $_POST['nombre'];
    $dni       = $_POST['dni'];
    $telefono  = $_POST['telefono'];
    $correo    = $_POST['correo'];
    $password  = $_POST['password'];

    try {
        $sql = "INSERT INTO usuarios (nombre, dni, telefono, correo, password) 
                VALUES ('$nombre', '$dni', '$telefono', '$correo', '$password')";

        if (mysqli_query($conexion, $sql)) {
            $_SESSION['usuario'] = $nombre;
            $_SESSION['id_usuario'] = mysqli_insert_id($conexion);
            header("Location: principal.php");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "<h3>Error: El correo electrónico o DNI ya se encuentra registrado.</h3>";
            echo "<a href='index.html'>Volver al registro</a>";
        } else {
            echo "Error inesperado: " . $e->getMessage();
        }
    }

    mysqli_close($conexion);
} else {
    echo "Acceso no autorizado.";
}
?>