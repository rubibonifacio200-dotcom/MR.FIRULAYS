<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_mascota = $_GET['id'];
    $id_usuario = $_SESSION['id_usuario'];

    $query_foto = "SELECT foto FROM mascotas WHERE id = ? AND id_usuario = ?";
    $stmt_foto = $conexion->prepare($query_foto);
    $stmt_foto->bind_param("ii", $id_mascota, $id_usuario);
    $stmt_foto->execute();
    $resultado_foto = $stmt_foto->get_result();

    if ($resultado_foto->num_rows > 0) {
        $mascota = $resultado_foto->fetch_assoc();
        $nombre_foto = $mascota['foto'];

        if (!empty($nombre_foto) && $nombre_foto != 'default_pet.png') {
            $ruta_foto = "uploads/" . $nombre_foto;
            if (file_exists($ruta_foto)) {
                unlink($ruta_foto); 
            }
        }
    }
    $stmt_foto->close();

    $query_delete = "DELETE FROM mascotas WHERE id = ? AND id_usuario = ?";
    $stmt_delete = $conexion->prepare($query_delete);
    $stmt_delete->bind_param("ii", $id_mascota, $id_usuario);

    if ($stmt_delete->execute()) {
        header("Location: mascotas.php?eliminado=1");
        exit();
    } else {
        
        echo "Error al eliminar: " . $conexion->error;
    }
    $stmt_delete->close();
} else {
    header("Location: mascotas.php");
    exit();
}

$conexion->close();
?>
