<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Sesión inválida.'); window.location.href='index.php';</script>";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    if ($especie === 'Otro' && !empty($_POST['otra_especie'])) {
        $especie = trim($_POST['otra_especie']); 
    }
    $raza = $_POST['raza'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $edad = !empty($_POST['edad']) ? $_POST['edad'] : null;
    $genero = $_POST['genero'];
    $color = !empty($_POST['color']) ? $_POST['color'] : null;
    $peso = !empty($_POST['peso']) ? floatval($_POST['peso']) : null;
    $esterilizado = !empty($_POST['esterilizado']) ? $_POST['esterilizado'] : null;
    $caracteristicas = !empty($_POST['caracteristicas']) ? $_POST['caracteristicas'] : null;
    $veterinario = !empty($_POST['veterinario']) ? $_POST['veterinario'] : null;
    $notas = !empty($_POST['notes']) ? $_POST['notes'] : null;
    $foto = 'default_pet.png';
    if (isset($_FILES['foto_mascota']) && $_FILES['foto_mascota']['error'] == 0) {
        $nombre_archivo = $_FILES['foto_mascota']['name'];
        $archivo_temporal = $_FILES['foto_mascota']['tmp_name'];
        $directorio_destino = "uploads/";

        if (!file_exists($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }

        $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png'];
        if (in_array($extension, $extensiones_permitidas)) {
            $nuevo_nombre_foto = time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
            $ruta_final = $directorio_destino . $nuevo_nombre_foto;

            if (move_uploaded_file($archivo_temporal, $ruta_final)) {
                $foto = $ruta_final;
            }
        }
    }
    $sql = "INSERT INTO mascotas (id_usuario, nombre, especie, raza, fecha_nacimiento, edad, genero, color, peso, esterilizado, caracteristicas, veterinario, notas, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conexion, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isssssssdsssss",
            $id_usuario, $nombre, $especie, $raza, $fecha_nacimiento,
            $edad, $genero, $color, $peso, $esterilizado,
            $caracteristicas, $veterinario, $notas, $foto
        );
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Procesando...</title>
            <link rel="stylesheet" href="css/panel.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body style="background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif;">
        <?php

        if (mysqli_stmt_execute($stmt)) {
            echo "
            <script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: '¡Mascota agregada exitosamente!',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#00468c'
                }).then(() => {
                    window.location.href = 'mascotas.php';
                });
            </script>
            ";
        } else {
            echo "
            <script>
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo registrar la mascota: " . mysqli_stmt_error($stmt) . "',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    window.history.back();
                });
            </script>
            ";
        }
        ?>
        </body>
        </html>
        <?php
        mysqli_stmt_close($stmt);
    } else {
        echo "Error al preparar la consulta: " . mysqli_error($conexion);
    }
    mysqli_close($conexion);
}
?>
