<?php
include 'conexion.php';

if (isset($_GET['fecha']) && isset($_GET['servicio'])) {
    $fecha = mysqli_real_escape_string($conexion, $_GET['fecha']);
    $servicio = mysqli_real_escape_string($conexion, $_GET['servicio']);

    // Asignamos los datos fijos del veterinario según el servicio
    $id_veterinario = ($servicio === 'Cirugía') ? 2 : 1;

    // Buscamos las citas confirmadas de ese doctor en esa fecha
    $sql = "SELECT hora FROM citas WHERE fecha = '$fecha' AND id_veterinario = '$id_veterinario' AND estado = 'Confirmado'";
    $resultado = mysqli_query($conexion, $sql);

    $horas_ocupadas = [];
    if ($resultado) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            // Guardamos la hora formateada (ej. "09:00")
            $horas_ocupadas[] = substr($fila['hora'], 0, 5);
        }
    }

    // Devolvemos la respuesta en formato JSON para que JavaScript la lea
    header('Content-Type: application/json');
    echo json_encode($horas_ocupadas);
}
// Devolvemos la respuesta en formato JSON para que JavaScript la lea
    header('Content-Type: application/json');
    echo json_encode($horas_ocupadas);
}
?>
?>