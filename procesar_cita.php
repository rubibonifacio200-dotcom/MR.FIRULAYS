<?php
session_start();
include 'conexion.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  

 $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1;

 $id_mascota = mysqli_real_escape_string($conexion, $_POST['id_mascota']);

 $servicio = mysqli_real_escape_string($conexion, $_POST['servicio']);

 $fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);

 $hora = mysqli_real_escape_string($conexion, $_POST['hora']);

 $id_veterinario = mysqli_real_escape_string($conexion, $_POST['id_veterinario']);



 $nombre_imagen = "";

 if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {

  $carpeta_destino = 'uploads/';

  $extension = pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION);

  $nombre_imagen = 'voucher_' . time() . '_' . $id_usuario . '.' . $extension;

  $ruta_final = $carpeta_destino . $nombre_imagen;



  if (!move_uploaded_file($_FILES['comprobante']['tmp_name'], $ruta_final)) {

   die("Error al guardar la imagen en el servidor.");

  }

 } else {

  die("Debes adjuntar un comprobante de pago válido.");

 }



 $sql_cita = "INSERT INTO citas (id_usuario, id_mascota, id_veterinario, servicio, fecha, hora, comprobante, estado) 

     VALUES ('$id_usuario', '$id_mascota', '$id_veterinario', '$servicio', '$fecha', '$hora', '$nombre_imagen', 'Confirmado')";



 if (mysqli_query($conexion, $sql_cita)) {

   

  // Obtenemos el nombre de la mascota

  $res_mascota = mysqli_query($conexion, "SELECT nombre FROM mascotas WHERE id = '$id_mascota'");

  $row_mascota = mysqli_fetch_assoc($res_mascota);

  $nombre_mascota_str = $row_mascota['nombre'];

   

  // --- LÓGICA DE PRECIOS DINÁMICOS SEGÚN EL SERVICIO ---

  if ($servicio === 'Cirugía') {

    $monto = "120.00";

  } elseif ($servicio === 'Peluquería y Baño') {

    $monto = "60.00";

  } else {

    // Por defecto para Consulta General u otros

    $monto = "80.00"; 

  }

  // -----------------------------------------------------

   

  $sql_pago = "INSERT INTO pagos (id_usuario, fecha, nombre_mascota, servicio, monto, estado, boleta_url) 

      VALUES ('$id_usuario', '$fecha', '$nombre_mascota_str', '$servicio', '$monto', 'Confirmado', NULL)";

   

  mysqli_query($conexion, $sql_pago);



  echo "

  <!DOCTYPE html>

  <html lang='es'>

  <head>

   <meta charset='UTF-8'>

   <meta name='viewport' content='width=device-width, initial-scale=1.0'>

   <title>Procesando pago...</title>

   <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

  </head>

  <body style='background-color: #f3f0f6; font-family: sans-serif;'>

   <script>

    Swal.fire({

     title: '¡Éxito!',

     text: '¡Cita y pago registrados con éxito!',

     icon: 'success',

     confirmButtonColor: '#00468c',

     confirmButtonText: 'Aceptar'

    }).then(() => {

     window.location.href = 'pagos.php';

    });

   </script>

  </body>

  </html>

  ";

  exit();

 } else {

  echo "Error en la BD al registrar la cita: " . mysqli_error($conexion);

 }



} else {

 header("Location: agendar_cita.php");

 exit();

}

?>