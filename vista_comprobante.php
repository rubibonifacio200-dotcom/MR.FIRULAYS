<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    header("Location: pagos.php");
    exit();
}

$id_pago = mysqli_real_escape_string($conexion, $_GET['id']);
$sql = "SELECT p.id, p.fecha, p.nombre_mascota, p.servicio, p.monto, p.estado, m.foto 
        FROM pagos p 
        LEFT JOIN mascotas m ON p.nombre_mascota = m.nombre AND m.id_usuario = '{$_SESSION['id_usuario']}' 
        WHERE p.id = '$id_pago'";
$resultado = mysqli_query($conexion, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $pago = mysqli_fetch_assoc($resultado);
    $fecha_formateada = date("d/m/Y", strtotime($pago['fecha']));
    $monto_formateado = number_format($pago['monto'], 2);
    $numero_cita = "CITA-" . date("Y") . "-" . str_pad($pago['id'] + 800, 5, "0", STR_PAD_LEFT);
    $historia_clinica = "HC-" . date("Y") . "-00100" . $pago['id'];
    // Lógica para la foto de la mascota
    if (!empty($pago['foto']) && $pago['foto'] != 'default_pet.png' && file_exists($pago['foto'])) {
        $ruta_foto = $pago['foto'];
    } else {
        $ruta_foto = "https://images.unsplash.com/photo-1543466835-00a7907e9de1?q=80&w=200&auto=format&fit=crop"; // Dejo la que tenías por si acaso
    }
} else {
    echo "Comprobante no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Cita</title>
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="panel-body">
    <header class="panel-header">
        <nav class="navbar-panel">
            <div class="logo">
                <i class="fas fa-paw logo-icon"></i>
                <div class="logo-text">
                    <span>MR.Firulays</span><small class="sub-logo">Clínica Veterinaria</small>
                </div>
            </div>
            <ul class="panel-tabs">
                <li class="tab-item"><a href="principal.php"><i class="fas fa-calendar-alt"></i> MIS CITAS</a></li>
                <li class="tab-item"><a href="mascotas.php"><i class="fas fa-dog"></i> MIS MASCOTAS</a></li>
                <li class="tab-item active"><a href="pagos.php"><i class="fas fa-credit-card"></i> PAGOS</a></li>
                <li class="tab-item"><a href="reclamos.php"><i class="fas fa-comment-dots"></i> RECLAMOS/QUEJA</a></li>
            </ul>
            <div class="user-profile-menu">
                <span class="user-name-text"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            </div>
        </nav>
    </header>
    <div class="vista-container">
        <div class="card-oscura">
            <div class="titulo-comprobante">
                <h2>Comprobante de cita</h2>
                <p>Tu cita ha sido agendada correctamente.</p>
            </div>

            <div class="info-grid">
                <div class="perfil-mascota">
                    <img src="<?php echo htmlspecialchars($ruta_foto); ?>" alt="Foto de <?php echo htmlspecialchars($pago['nombre_mascota']); ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                    <h3><?php echo htmlspecialchars($pago['nombre_mascota']); ?></h3>
                    <p><?php echo htmlspecialchars($pago['servicio']); ?></p>
                </div>

                <div class="detalles-cita">
                    <div class="detalle-item">
                        <i class="far fa-calendar-alt"></i>
                        <div class="detalle-texto">
                            <span>Fecha de la cita:</span>
                            <strong><?php echo $fecha_formateada; ?></strong>
                        </div>
                    </div>
                    <div class="detalle-item">
                        <i class="fas fa-receipt"></i>
                        <div class="detalle-texto">
                            <span>Orden/Total:</span>
                            <strong>S/ <?php echo $monto_formateado; ?></strong>
                        </div>
                    </div>
                    <div class="detalle-item">
                        <i class="far fa-clock"></i>
                        <div class="detalle-texto">
                            <span>Hora de la cita:</span>
                            <strong>10:00 AM</strong>
                        </div>
                    </div>
                    <div class="detalle-item">
                        <i class="far fa-check-circle"></i>
                        <div class="detalle-texto">
                            <span>Estado:</span>
                            <strong class="estado-verde"><?php echo ucfirst($pago['estado']); ?></strong>
                        </div>
                    </div>
                    <div class="detalle-item">
                        <i class="fas fa-stethoscope"></i>
                        <div class="detalle-texto">
                            <span>Tipo de atención:</span>
                            <strong>Consulta General</strong>
                        </div>
                    </div>
                    <div class="detalle-item">
                        <i class="fas fa-qrcode"></i>
                        <div class="detalle-texto">
                            <span>Código de cita:</span>
                            <strong><?php echo $numero_cita; ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-paciente">
                <div class="detalle-item">
                    <i class="far fa-user-circle"></i>
                    <div class="detalle-texto">
                        <span>Nombre del paciente:</span>
                        <strong><?php echo htmlspecialchars($pago['nombre_mascota']); ?></strong>
                    </div>
                </div>
                <div class="detalle-item">
                    <i class="far fa-folder-open"></i>
                    <div class="detalle-texto">
                        <span>Historia clínica:</span>
                        <strong><?php echo $historia_clinica; ?></strong>
                    </div>
                </div>
            </div>

            <div class="botones-accion">
                <a href="generar_boleta.php?id=<?php echo $id_pago; ?>" class="btn btn-descargar">
                    <i class="fas fa-download"></i> Descargar comprobante
                </a>
            <a href="https://wa.me/51923878253?text=Hola,%20tengo%20una%20consulta%20sobre%20mi%20reclamo%20en%20MR.%20Firulays" 
            class="btn btn-whatsapp" 
target="_blank" 
style="display: inline-block; padding: 10px 20px; background-color: #25d366; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">
    <i class="fab fa-whatsapp"></i> Escribenos por Whatsapp
</a>
                <a href="pagos.php" class="btn btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="card-clara">
            <h3>Información importante</h3>
            <div class="regla-item">
                <i class="far fa-clock"></i>
                <span>Llega 10 minutos antes de tu cita.</span>
            </div>
            <div class="regla-item">
                <i class="far fa-calendar-check"></i>
                <span>No olvides traer el carnet de vacunación de tu mascota.</span>
            </div>
            <div class="regla-item">
                <i class="far fa-edit"></i>
                <span>Si necesitas reprogramar, hazlo con anticipación.</span>
            </div>
        </div>
    </div>

</body>
</html>