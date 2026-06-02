<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['usuario'])) {
    echo "
    <script>
        alert('Acceso denegado. Por favor, inicia sesión para entrar al panel.');
        window.location.href = 'index.php';
    </script>
    ";
    exit();
}

$nombreUsuario = $_SESSION['usuario'] ?? 'Usuario';?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MR. Firulays - Panel de Citas</title>
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <li class="tab-item active"><a href="principal.php"><i class="fas fa-calendar-alt"></i> MIS CITAS</a></li>
                <li class="tab-item"><a href="mascotas.php"><i class="fas fa-dog"></i> MIS MASCOTAS</a></li>
                <li class="tab-item"><a href="pagos.php"><i class="fas fa-credit-card"></i> PAGOS</a></li>
                <li class="tab-item"><a href="reclamos.php"><i class="fas fa-comment-dots"></i> RECLAMOS/QUEJA</a></li>
            </ul>

            <div class="user-profile-menu">
                <div class="avatar-circle" id="userAvatar">
                    <i class="fas fa-user"></i>
                </div>
                <span class="user-name-text" id="userNameTop"><?php echo $nombreUsuario; ?></span>
                
                <button id="btnLogout" class="btn-small-logout" title="Cerrar Sesión" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </nav>
    </header>

    <main class="panel-content-wrapper">
        <section class="welcome-container">
            <div class="welcome-banner">
                <i class="fas fa-calendar-check banner-icon"></i>
                <h2 id="welcomeMessage">¡Hola, <?php echo $nombreUsuario; ?>! Bienvenido a tu panel.</h2>
            </div>
                <a href="agendar_cita.php" class="btn-add-appointment" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fas fa-plus"></i> Agendar nueva cita
                </a>
        </section>

        <section class="appointments-section">
            <h3 class="section-title">Próximas citas</h3>
            <div class="appointments-grid" style="display: flex; flex-wrap: wrap; gap: 25px; align-items: flex-start;">
                
<?php
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1; 

$sql_citas = "SELECT c.id as id_cita, c.fecha, c.hora, c.servicio, c.estado, 
                        m.nombre as nombre_mascota, m.foto, 
                        v.nombre as nombre_vet 
                FROM citas c 
                INNER JOIN mascotas m ON c.id_mascota = m.id 
                INNER JOIN veterinarios v ON c.id_veterinario = v.id 
                WHERE c.id_usuario = '$id_usuario' 
                ORDER BY c.fecha ASC, c.hora ASC";
$resultado_citas = mysqli_query($conexion, $sql_citas);

if ($resultado_citas && mysqli_num_rows($resultado_citas) > 0) {
    while ($cita = mysqli_fetch_assoc($resultado_citas)) {
        
        $fecha_formateada = date("d/m/Y", strtotime($cita['fecha']));
        $hora_formateada = date("h:i A", strtotime($cita['hora']));

        $foto_bd = $cita['foto'];
        // Quitamos el "img/" y agregamos el file_exists por seguridad
        if (!empty($foto_bd) && $foto_bd != 'default_pet.png' && file_exists($foto_bd)) {
            $foto_mascota = htmlspecialchars($foto_bd); 
        } else {
            // Mantenemos la foto de Unsplash por defecto si falla
            $foto_mascota = "https://images.unsplash.com/photo-1543466835-00a7907e9de1?auto=format&fit=crop&q=80&w=200";
        }
        
        echo '<div class="appointment-card" style="width: 350px; max-width: 100%;">';
        
        echo '  <div class="card-top-info">';
        echo '      <span>' . htmlspecialchars($fecha_formateada) . '</span>';
        echo '      <span>' . htmlspecialchars($hora_formateada) . '</span>';
        echo '  </div>';
        
        echo '  <div class="card-main-content" style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">';
        
        echo '      <div class="pet-photo-placeholder" style="flex-shrink: 0;">';
        echo '          <img src="' . $foto_mascota . '" alt="Mascota" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">';
        echo '      </div>';
        
        echo '      <div class="pet-details">';
        echo '          <h4>' . strtoupper(htmlspecialchars($cita['nombre_mascota'])) . '</h4>';
        echo '          <p class="med-reason" style="margin-bottom: 5px;">' . htmlspecialchars($cita['servicio']) . '</p>';
        echo '          <p class="doc-assigned" style="margin-bottom: 5px;">Veterinaria: ' . htmlspecialchars($cita['nombre_vet']) . '</p>';
        
        $color_estado = ($cita['estado'] == 'Confirmado') ? '#10b981' : '#f59e0b';
        echo '          <span class="status-badge" style="color: ' . $color_estado . '; font-weight: bold; font-size: 13px;">Estado: ' . htmlspecialchars($cita['estado']) . '</span>';
        
        echo '      </div>';
        echo '  </div>';
        
        echo '      <div class="card-actions" style="justify-content: center;">'; 
        echo '          <button class="btn-reprogram" onclick="window.location.href=\'agendar_cita.php?reprogramar=' . $cita['id_cita'] . '\'" style="width: 100%;">Reprogramar cita</button>';
        echo '      </div>';
        
        echo '</div>';
    }
} else {
    echo '<div style="grid-column: 1 / -1; padding: 40px; text-align: center; color: #64748b; background: #f8fafc; border-radius: 10px; border: 1px dashed #cbd5e1;">';
    echo '  <i class="far fa-calendar-times" style="font-size: 32px; margin-bottom: 15px; color: #94a3b8;"></i>';
    echo '  <p style="margin: 0; font-size: 15px;">No tienes citas próximas programadas.</p>';
    echo '</div>';
}
?>
        </section>
    </main>

    <script>
        console.log("Sesión activa de: <?php echo $_SESSION['usuario']; ?>");
    </script>
</body>
</html>