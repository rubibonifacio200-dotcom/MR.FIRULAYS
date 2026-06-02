<?php
include 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pregunta = $_POST['id_pregunta'];
    $respuesta = mysqli_real_escape_string($conexion, $_POST['respuesta']);
    $sql_insert = "INSERT INTO respuestas (id_pregunta, contenido) VALUES ('$id_pregunta', '$respuesta')";
    if (mysqli_query($conexion, $sql_insert)) {
        $sql_update = "UPDATE consejocomunidad SET respuestas_count = respuestas_count + 1 WHERE id = $id_pregunta";
        mysqli_query($conexion, $sql_update);
        header("Location: ver_consejo.php?id=" . $id_pregunta);
    }
}
?>