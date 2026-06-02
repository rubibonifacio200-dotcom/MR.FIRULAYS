<?php
session_start();
// Esto nos ayudará a ver si hay algún error oculto de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conexion.php';

// Validamos que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Validamos que los datos vengan del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recibimos todos los datos del formulario
    $id_cita = mysqli_real_escape_string($conexion, $_POST['id_cita_reprogramar']);
    $servicio = mysqli_real_escape_string($conexion, $_POST['servicio']);
    $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
    $hora = mysqli_real_escape_string($conexion, $_POST['hora']);
    $id_mascota = mysqli_real_escape_string($conexion, $_POST['id_mascota']);

    // Mismo cálculo de veterinario que tienes en tu archivo pagar.php
    $id_veterinario = ($servicio === 'Cirugía') ? 2 : 1;

    // Hacemos el UPDATE incluyendo al veterinario
    $sql_update = "UPDATE citas SET 
                    servicio = '$servicio', 
                    fecha = '$fecha', 
                    hora = '$hora', 
                    id_mascota = '$id_mascota',
                    id_veterinario = '$id_veterinario'
                   WHERE id = '$id_cita'";

    if (mysqli_query($conexion, $sql_update)) {
        // Cerramos PHP temporalmente para escribir HTML puro y evitar errores de comillas
        ?>
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Reprogramando cita...</title>
            <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        </head>
        <body style='background-color: #f3f0f6; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;'>
            
            <script>
                // Esperamos a que todo el HTML cargue antes de lanzar la alerta
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '¡Reprogramada!',
                        text: 'Tu cita ha sido actualizada con éxito.',
                        icon: 'success',
                        confirmButtonColor: '#00468c',
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false // Evita que se cierre si tocan fuera
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'principal.php';
                        }
                    });
                });
            </script>

        </body>
        </html>
        <?php
        exit();
    } else {
        // Si hay un error en la base de datos, lo imprimimos en pantalla
        echo "<h3>Error al actualizar la cita en la base de datos:</h3>";
        echo "<p>" . mysqli_error($conexion) . "</p>";
    }
} else {
    // Si entran por la URL directa
    header("Location: principal.php");
    exit();
}
?>