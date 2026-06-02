<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
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
                <li class="tab-item active"><a href="principal.php"><i class="fas fa-calendar-alt"></i> MIS CITAS</a></li>
                <li class="tab-item"><a href="mascotas.php"><i class="fas fa-dog"></i> MIS MASCOTAS</a></li>
                <li class="tab-item"><a href="pagos.php"><i class="fas fa-credit-card"></i> PAGOS</a></li>
                <li class="tab-item"><a href="reclamos.php"><i class="fas fa-comment-dots"></i> RECLAMOS/QUEJA</a></li>
            </ul>
            <div class="user-profile-menu">
                <span class="user-name-text"><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            </div>
        </nav>
    </header>

    <div class="agendar-container">
        <div class="agendar-header">
            <h2>Agendar cita y gestión de mascotas</h2>
        </div>

        <?php
        // Detectamos si estamos reprogramando
        $es_reprogramacion = isset($_GET['reprogramar']);
        $action_url = $es_reprogramacion ? 'procesar_reprogramacion.php' : 'pagar.php';
        $texto_boton = $es_reprogramacion ? 'Guardar nueva fecha' : 'Confirmar reserva y pago';
        $icono_boton = $es_reprogramacion ? 'fas fa-save' : 'far fa-calendar-check';
        $id_cita_reprogramar = $es_reprogramacion ? intval($_GET['reprogramar']) : 0;
        ?>
        <form action="<?php echo $action_url; ?>" method="POST" class="agendar-grid">
            
            <?php if ($es_reprogramacion): ?>
                <input type="hidden" name="id_cita_reprogramar" value="<?php echo $id_cita_reprogramar; ?>">
            <?php endif; ?>
            
            <div class="agendar-col">
                <div class="agendar-card">
                    <h3>1. Elige servicio</h3>
                    <label class="agendar-label">Servicio</label>
                    <select name="servicio" id="servicioSelect" class="agendar-select" required>
                        <option value="" disabled selected>Selecciona un servicio</option>
                        <option value="Consulta General">Consulta General</option>
                        <option value="Cirugía">Cirugía</option>
                        <option value="Peluquería y Baño">Peluquería y Baño</option>
                    </select>
                </div>

                <div class="agendar-card">
                    <h3>2. Selecciona fecha</h3>
                    <input type="date" name="fecha" class="agendar-date" required>
                </div>
            </div>

            <div class="agendar-col">
                <div class="agendar-card">
                    <h3>3. Selecciona horario disponible</h3>
                    
                    <div class="horarios-grid">
                        <label class="hora-radio">
                            <input type="radio" name="hora" value="08:00">
                            <span>8:00 AM</span>
                        </label>
                        <label class="hora-radio">
                            <input type="radio" name="hora" value="09:00" checked>
                            <span>9:00 AM</span>
                        </label>
                        <label class="hora-radio">
                            <input type="radio" name="hora" value="10:00">
                            <span>10:00 AM</span>
                        </label>
                        <label class="hora-radio">
                            <input type="radio" name="hora" value="11:00">
                            <span>11:00 AM</span>
                        </label>
                        <label class="hora-radio">
                            <input type="radio" name="hora" value="13:00">
                            <span>1:00 PM</span>
                        </label>
                        <label class="hora-radio">
                            <input type="radio" name="hora" value="14:00">
                            <span>2:00 PM</span>
                        </label>
                    </div>

                </div>
            </div>

            <div class="agendar-col">
                <div class="agendar-card">
                    <h3>Mascotas registradas</h3>
                    
                    <?php
                    // Obtenemos el ID del usuario
                    $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 1; 

                    // Consultamos las mascotas
                    $sql_mascotas = "SELECT id, nombre, raza, foto FROM mascotas WHERE id_usuario = '$id_usuario'";
                    $resultado_mascotas = mysqli_query($conexion, $sql_mascotas);

                    if ($resultado_mascotas && mysqli_num_rows($resultado_mascotas) > 0) {
                        $es_primero = true; 
                        
                        while ($mascota = mysqli_fetch_assoc($resultado_mascotas)) {
        
                    if (!empty($mascota['foto']) && $mascota['foto'] != 'default_pet.png' && file_exists($mascota['foto'])) {
                    $ruta_foto = $mascota['foto'];
                    } else {
            
                    $ruta_foto = "https://cdn-icons-png.flaticon.com/512/1076/1076928.png";
                    }

                    $checked = $es_primero ? 'checked' : '';

                    echo '<label class="mascota-radio-card">';
                    echo '    <input type="radio" name="id_mascota" value="' . $mascota['id'] . '" ' . $checked . ' required>';
                    echo '    <div class="mascota-card-content">';
                    echo '        <img src="' . htmlspecialchars($ruta_foto) . '" alt="Foto Mascota" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">';
                    echo '        <div class="mascota-info">';
                    echo '            <h4>' . strtoupper(htmlspecialchars($mascota['nombre'])) . '</h4>';
                    echo '            <p>' . htmlspecialchars($mascota['raza']) . '</p>';
                    echo '        </div>';
                    echo '        <div class="radio-indicator"></div>';
                    echo '    </div>';
                    echo '</label>';

                    $es_primero = false;
                }
                    } else {
                        echo '<p style="font-size: 13px; color: #666; text-align: center; padding: 20px 0;">No tienes mascotas registradas.</p>';
                    }
                    ?>
        <div id="tarjeta-especialista" style="display: none; margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
            <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #64748b;">4. Especialista encargado</h4>
            <div style="display: flex; align-items: center; gap: 15px; background: #f8fafc; padding: 10px; border-radius: 8px;">
                <img id="esp-foto" src="" alt="Especialista" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                <div>
                    <h4 id="esp-nombre" style="margin: 0; color: #00468c; font-size: 15px;"></h4>
                    <p id="esp-rol" style="margin: 0; font-size: 12px; color: #475569; font-weight: bold;"></p>
                </div>
            </div>
        </div>


    </div> <div class="agendar-acciones">
        <button type="submit" class="btn-confirmar">
            <i class="<?php echo $icono_boton; ?>"></i> <?php echo $texto_boton; ?>
        </button>
        <a href="principal.php" class="btn-cancelar">Cancelar</a>
    </div>
            
        

        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Usamos el querySelector original que tenías para no romper nada
    const servicioSelect = document.querySelector('select[name="servicio"]');
    const tarjetaEspecialista = document.getElementById('tarjeta-especialista');
    const espNombre = document.getElementById('esp-nombre');
    const espRol = document.getElementById('esp-rol');
    const espFoto = document.getElementById('esp-foto');

    // Base de datos de nuestros doctores quemada en JS
    // Base de datos de nuestros doctores con tus imágenes locales
    const especialistas = {
        'Consulta General': {
            nombre: 'Dra. Carmen Soto',
            rol: 'Veterinaria General',
            foto: 'img/carmen.jpg' 
        },
        'Cirugía': {
            nombre: 'Dr. Alejandro Perez',
            rol: 'Cirujano Veterinario',
            foto: 'img/alejandro.jpg' 
        },
        'Peluquería y Baño': {
            nombre: 'Sofia Gomez',
            rol: 'Groomer',
            foto: 'img/sofia.jpg' 
        }
    };

    // Escuchamos cuando el cliente elige un servicio
    servicioSelect.addEventListener('change', function() {
        const servicioElegido = this.value;
        
        if (especialistas[servicioElegido]) {
            // Llenamos los datos y mostramos la tarjeta
            espNombre.textContent = especialistas[servicioElegido].nombre;
            espRol.textContent = especialistas[servicioElegido].rol;
            espFoto.src = especialistas[servicioElegido].foto;
            tarjetaEspecialista.style.display = 'block';
        } else {
            tarjetaEspecialista.style.display = 'none';
        }
    });
});
</script>


</body>
</html>