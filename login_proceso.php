<?php

session_start();
include 'conexion.php'; 



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo   = mysqli_real_escape_string($conexion, $_POST['correo']);
    $password = $_POST['password'];


    $consulta = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);


        if ($password === $usuario['password']) {
            

            $_SESSION['usuario'] = $usuario['nombre']; 
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['correo_usuario'] = $usuario['correo'];

            session_write_close();
            header("Location: principal.php");
            exit();

        } else {
            echo '<script>
                    alert("La contraseña es incorrecta");
                    window.location = "index.php";
                </script>';
        }
    } else {
        echo '<script>
                alert("Este correo no está registrado");
                window.location = "index.php";
            </script>';
    }

    mysqli_close($conexion);
} else {
    header("Location: index.php");
}
?>