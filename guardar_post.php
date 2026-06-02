<?php
include 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $sql = "INSERT INTO consejocomunidad (titulo) VALUES ('$titulo')";
    if (mysqli_query($conexion, $sql)) {
        header("Location: consejocomunidad.php");
    } else {
        echo "Error al publicar: " . mysqli_error($conexion);
    }
}
?>