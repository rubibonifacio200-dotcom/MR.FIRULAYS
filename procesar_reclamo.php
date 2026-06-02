<?php
include 'conexion.php';
session_start();

// 1. Validar sesión
if (!isset($_SESSION['usuario'])) {
    exit("No autorizado");
}

// 2. Limpieza de datos (seguridad básica)
$nombre    = mysqli_real_escape_string($conexion, $_POST['nombre_completo']);
$dni       = mysqli_real_escape_string($conexion, $_POST['dni']);
$telefono  = mysqli_real_escape_string($conexion, $_POST['telefono']);
$correo    = mysqli_real_escape_string($conexion, $_POST['correo']);
$tipo      = mysqli_real_escape_string($conexion, $_POST['tipo_incidencia']);
$desc      = mysqli_real_escape_string($conexion, $_POST['descripcion']);

// 3. Manejo de "Otro"
if ($tipo === "Otro" && !empty($_POST['otro_motivo'])) {
    $tipo = "Otro: " . mysqli_real_escape_string($conexion, $_POST['otro_motivo']);
}

// 4. Manejo de archivo
$nombreArchivoDb = ""; 
if (isset($_FILES['evidencia']) && $_FILES['evidencia']['error'] == 0) {
    // Asegurar que la carpeta exista
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    $nuevoNombre = time() . "_" . basename($_FILES['evidencia']['name']);
    $carpetaDestino = "uploads/" . $nuevoNombre;
    
    if (move_uploaded_file($_FILES['evidencia']['tmp_name'], $carpetaDestino)) {
        $nombreArchivoDb = $nuevoNombre; 
    }
}

// 5. Inserción
$sql = "INSERT INTO reclamos (nombre_completo, dni, telefono, correo_electronico, tipo_incidencia, descripcion, evidencia) 
        VALUES ('$nombre', '$dni', '$telefono', '$correo', '$tipo', '$desc', '$nombreArchivoDb')";

if (mysqli_query($conexion, $sql)) {
    // IMPORTANTE: Devolvemos "success" para que el JS en reclamos.php muestre el SweetAlert
    echo "success";
} else {
    echo "Error interno de base de datos: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>