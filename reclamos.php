<?php
session_start();

// 1. Validar inicio de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// 2. Variables obtenidas de la sesión (usando las llaves correctas de tu login)
$nombreUsuario = $_SESSION['usuario'];
$correoUsuario = $_SESSION['correo_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MR. Firulays - Reclamos y Quejas</title>
    <link rel="stylesheet" href="css/panel.css"> 
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
                <li class="tab-item"><a href="mascotas.php"><i class="fas fa-dog"></i> MIS MASCOTAS</a></li>
                <li class="tab-item"><a href="pagos.php"><i class="fas fa-credit-card"></i> PAGOS</a></li>
                <li class="tab-item active"><a href="reclamos.php"><i class="fas fa-comment-dots"></i> RECLAMOS/QUEJA</a></li>
            </ul>
            <div class="user-profile-menu">
                <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($nombreUsuario); ?></span>
                <button onclick="window.location.href='logout.php'"><i class="fas fa-sign-out-alt"></i></button>
            </div>
        </nav>
    </header>

    <main class="panel-content-wrapper">
        <div class="welcome-banner" style="margin-bottom: 20px; background: white; padding: 20px; border-radius: 15px;">
            <h2 style="color: #004aad;">Bienvenido al panel de reclamos y quejas</h2>
            <p>Tu opinión nos ayuda a mejorar el servicio de nuestros especialistas.</p>
        </div>

        <form id="formReclamo" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <h3 style="color: #004aad; margin-bottom: 15px;"><i class="fas fa-user-edit"></i> Datos de contacto</h3>
                    
                    <label>Nombre completo:</label>
                    <input type="text" name="nombre_completo" value="<?php echo htmlspecialchars($nombreUsuario); ?>" readonly style="width:100%; margin-bottom:15px; padding:10px; border-radius:8px; border:1px solid #ddd; background:#f9f9f9;">

                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label>DNI:</label>
                            <input type="text" name="dni" required maxlength="8" style="width:100%; margin-bottom:15px; padding:10px; border-radius:8px; border:1px solid #ddd;">
                        </div>
                        <div style="flex: 1;">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" required maxlength="9" style="width:100%; margin-bottom:15px; padding:10px; border-radius:8px; border:1px solid #ddd;">
                        </div>
                    </div>

                    <label>Correo electrónico:</label>
                    <input type="email" name="correo" value="<?php echo htmlspecialchars($correoUsuario); ?>" readonly style="width:100%; margin-bottom:15px; padding:10px; border-radius:8px; border:1px solid #ddd; background:#f9f9f9;">

                    <label>Tipo de incidencia:</label>
                    <select name="tipo_incidencia" id="tipo" onchange="checkOtro()" style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd; margin-bottom: 15px;">
                        <option value="Atención en Recepción">Atención de Personal (Recepción/Operador)</option>
                        <option value="Servicio Médico">Servicio Médico (Dra. Carmen Soto)</option>
                        <option value="Cirugía Veterinaria">Cirugía Veterinaria (Dr. Alejandro)</option>
                        <option value="Grooming">Grooming - Baño/Corte (Sofia Gomez)</option>
                        <option value="Otro">U otro (Especificar motivo)</option>
                    </select>

                    <div id="div_otro" style="display:none;">
                        <input type="text" name="otro_motivo" id="input_otro" placeholder="¿Cuál es el motivo?" style="width:100%; padding:10px; border-radius:8px; border:2px solid #004aad;">
                    </div>
                </div>

                <div style="background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display:flex; flex-direction:column; align-items: center;">
                    <h3 style="color: #004aad; margin-bottom: 15px; width: 100%; text-align: left;"><i class="fas fa-keyboard"></i> Detalle</h3>
                    <textarea name="descripcion" placeholder="Escribe aquí tu queja..." required style="width:100%; height:120px; padding:15px; border-radius:8px; border:1px solid #ddd; resize:none; margin-bottom:20px;"></textarea>
                    
                    <label style="border: 2px dashed #b5c7e3; border-radius: 12px; padding: 15px; text-align: center; background: #f8faff; width: 280px; cursor: pointer;">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 1.8rem; color: #004aad;"></i>
                        <p style="color: #004aad; font-weight: bold;">Adjuntar evidencia</p>
                        <input type="file" name="evidencia" id="file-input" style="display: none;" onchange="updateFileName()">
                    </label>
                    <div id="file-name" style="margin-top: 8px; font-size: 0.8rem; color: #555;">Ningún archivo seleccionado</div>
                    
                    <button type="submit" style="margin-top: 25px; width: 180px; padding: 10px; background: #004aad; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-paper-plane"></i> ENVIAR
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
    function updateFileName() {
        const input = document.getElementById('file-input');
        const output = document.getElementById('file-name');
        output.innerHTML = input.files.length > 0 ? `<i class='fas fa-check-circle' style='color: #28a745;'></i> ${input.files[0].name}` : "Ningún archivo seleccionado";
    }

    function checkOtro() {
        document.getElementById('div_otro').style.display = (document.getElementById('tipo').value === 'Otro') ? 'block' : 'none';
    }

    document.getElementById('formReclamo').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({ title: 'Enviando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        // IMPORTANTE: Aquí apuntamos al nuevo archivo procesador
        fetch('procesar_reclamo.php', { method: 'POST', body: new FormData(this) })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") {
                Swal.fire({ title: '¡Éxito!', text: 'Reclamo enviado correctamente', icon: 'success', confirmButtonColor: '#004aad' })
                .then(() => location.reload());
            } else {
                Swal.fire('Error', data, 'error');
            }
        })
        .catch(err => Swal.fire('Error', 'Error de conexión', 'error'));
    });
    </script>
</body>
</html>