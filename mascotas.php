<?php
session_start();

if (!isset($_SESSION['usuario'])) { 
    echo "
    <script>
        alert('Acceso denegado. Por favor, inicia sesión.');
        window.location.href = 'index.php';
    </script>
    ";
    exit();
}

include 'conexion.php';

$nombreUsuario = $_SESSION['nombre_usuario'] ?? $_SESSION['usuario'] ?? "Usuario";
$idUsuario = $_SESSION['id_usuario'] ?? 0;

$query = "SELECT * FROM mascotas WHERE id_usuario = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MR. Firulays - Mis Mascotas</title>
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="css/estilos_mascotas.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="panel-body">

    <header class="panel-header">
        <nav class="navbar-panel">
            <div class="logo">
                <i class="fas fa-paw logo-icon"></i>
                <div class="logo-text">
                    <span>MR.Firulays</span>
                    <small class="sub-logo">Clínica Veterinaria</small>
                </div>
            </div>

            <ul class="panel-tabs">
                <li class="tab-item"><a href="principal.php"><i class="fas fa-calendar-alt"></i> MIS CITAS</a></li>
                <li class="tab-item active"><a href="mascotas.php"><i class="fas fa-dog"></i> MIS MASCOTAS</a></li>
                <li class="tab-item"><a href="pagos.php"><i class="fas fa-credit-card"></i> PAGOS</a></li>
                <li class="tab-item"><a href="reclamos.php"><i class="fas fa-comment-dots"></i> RECLAMOS/QUEJA</a></li>
            </ul>

            <div class="user-profile-menu">
                <div class="avatar-circle" id="userAvatar">
                    <i class="fas fa-user"></i>
                </div>
                <span class="user-name-text" id="userNameTop"><?php echo htmlspecialchars($nombreUsuario); ?></span>
                <button id="btnLogout" class="btn-small-logout" title="Cerrar Sesión" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </nav>
    </header>

    <main class="panel-content-wrapper">
        <section class="welcome-container-pets">
            <h2 class="pets-main-title">Mis mascotas</h2>
            <p class="pets-sub-title">Aquí puedes ver a tus compañeros y toda su información</p>
        </section>

        <div class="pets-grid">
            <?php
            if ($resultado->num_rows > 0) {
                while ($mascota = $resultado->fetch_assoc()) {
                    $clasesColores = ['card-purple', 'card-pink', 'card-green', 'card-yellow', 'card-blue'];
                    $claseMascota = $clasesColores[$mascota['id'] % count($clasesColores)];
                    if (!empty($mascota['foto']) && $mascota['foto'] != 'default_pet.png' && file_exists($mascota['foto'])) {
                        $fotoMascota = $mascota['foto']; // Usa la ruta guardada (ej: uploads/foto.png)
                    } else {
                        $fotoMascota = 'img/default_pet.png';
                    }
                    ?>
                    <div class="pet-card <?php echo $claseMascota; ?>">
                        <div class="pet-image" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #fff;">
                            <img src="<?php echo $fotoMascota; ?>" alt="<?php echo htmlspecialchars($mascota['nombre']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="pet-info">
                            <h3 style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span><?php echo strtoupper(htmlspecialchars($mascota['nombre'])); ?> <i class="fas fa-paw"></i></span>
                                <a href="#"
                                    onclick="confirmarEliminar(<?php echo $mascota['id']; ?>, '<?php echo htmlspecialchars(addslashes($mascota['nombre'])); ?>')"
                                    style="color: #ef4444; font-size: 0.95rem; transition: transform 0.2s; margin-left: 10px;"
                                    onmouseover="this.style.transform='scale(1.2)'"
                                    onmouseout="this.style.transform='scale(1)'"
                                    title="Eliminar Mascota">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </h3>
                            <p><i class="far fa-calendar-alt"></i> <strong>Edad:</strong> <?php echo htmlspecialchars($mascota['edad']); ?></p>
                            <p><i class="fas fa-ribbon"></i> <strong>Raza:</strong> <?php echo htmlspecialchars($mascota['raza']); ?></p>
                            <p><i class="far fa-edit"></i> <strong>Descripción:</strong> <?php echo htmlspecialchars($mascota['caracteristicas']); ?></p>
                            <?php if(!empty($mascota['ultima_atencion'])): ?>
                                <p class="last-visit"><i class="far fa-clock"></i> <strong>Última atención:</strong> <?php echo htmlspecialchars($mascota['ultima_atencion']); ?></p>
                            <?php else: ?>
                                <p class="last-visit"><i class="far fa-clock"></i> <strong>Última atención:</strong> Sin consultas aún</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="add-pet-card" onclick="window.location.href='agregar_mascota.php'" style="cursor: pointer;">
                <div class="add-content">
                    <i class="fas fa-plus icon-plus"></i>
                    <p>Agregar nueva mascota <i class="fas fa-paw"></i></p>
                </div>
            </div>
        </div>
        <footer class="family-footer">
            <div class="footer-box">
                <i class="fas fa-heart heart-icon"></i>
                <div class="footer-text">
                    <h4>ELLOS SON PARTE DE NUESTRA FAMILIA</h4>
                    <ul>
                        <li>Gracias por confiar en nosotros para su cuidado y bienestar</li>
                    </ul>
                </div>
            </div>
        </footer>
    </main>
    <script>
    function confirmarEliminar(id, nombre) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará permanentemente los datos de " + nombre + ".",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00468c',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'eliminar_mascota.php?id=' + id;
            }
        });
    }
    </script>
    <?php if (isset($_GET['eliminado']) && $_GET['eliminado'] == 1): ?>
    <script>
        Swal.fire({
            title: '¡Éxito!',
            text: 'Mascota eliminada correctamente.',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#00468c' 
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>
    <?php endif; ?>
</body>
</html>