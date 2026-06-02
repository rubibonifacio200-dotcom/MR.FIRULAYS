<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo "
    <script>
        alert('Acceso denegado. Por favor, inicia sesión para entrar al panel.');
        window.location.href = 'index.php';
    </script>
    ";
    exit();
}
include 'conexion.php';
$nombreUsuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : "Usuario";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MR. Firulays - Agregar Mascota</title>
    <link rel="stylesheet" href="css/panel.css">
    <link rel="stylesheet" href="css/formulario_mascota.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .grid-dinamico {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
            transition: all 0.3s ease;
        }
        .grid-dinamico.con-otra-especie {
            grid-template-columns: repeat(4, 1fr);
        }
        
        #contenedor_otra_especie {
            animation: fadeIn 0.4s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
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
                <li class="tab-item"><a href="#"><i class="fas fa-credit-card"></i> PAGOS</a></li>
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
    <main class="panel-content-wrapper" style="margin-top: 2rem;">
        <div class="container">
            <div class="section-header">
                <div class="icon-bg">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="section-title">
                    <h1>Agregar nueva mascota</h1>
                    <p>Completa la información de tu nuevo compañero</p>
                </div>
            </div>
            <form action="guardar_mascotas.php" method="POST" enctype="multipart/form-data" class="pet-form">
                <div class="form-sidebar">
                    <label for="foto_mascota" style="cursor: pointer;">
                        <div class="photo-upload-box" id="previewContainer">
                            <div id="uploadPlaceholder">
                                <div class="upload-icon">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div class="upload-label">
                                    <span>Agregar foto</span>
                                    <small>Formatos permitidos:<br>JPG, PNG (máx. 5MB)</small>
                                </div>
                            </div>
                        </div>
                    </label>
                    <input type="file" id="foto_mascota" name="foto_mascota" accept="image/*" style="display: none;" onchange="previsualizarImagen(this)">
                </div>
                <div class="form-content">
                    <fieldset class="form-section">
                        <legend><i class="fas fa-paw icon-blue"></i> Información básica</legend>
                        <div class="grid-dinamico" id="grillaBasica">
                            <div class="form-group">
                                <label for="nombre">Nombre de la mascota *</label>
                                <input type="text" id="nombre" name="nombre" placeholder="Ej: Luna" required>
                            </div>
                            <div class="form-group">
                                <label for="especie">Especie *</label>
                                <select id="especie" name="especie" onchange="detectarOtraEspecie(this)" required>
                                    <option value="" disabled selected>Selecciona la especie</option>
                                    <option value="Perro">Perro</option>
                                    <option value="Gato">Gato</option>
                                    <option value="Ave">Ave</option>
                                    <option value="Roedor">Roedor</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <div class="form-group" id="contenedor_otra_especie" style="display: none;">
                                <label for="otra_especie">¿Qué especie es? *</label>
                                <input type="text" id="otra_especie" name="otra_especie" placeholder="Ej: Conejo, Hurón">
                            </div>
                            <div class="form-group">
                                <label for="raza">Raza *</label>
                                <input type="text" id="raza" name="raza" placeholder="Ej: Golden Retriever" required>
                            </div>
                        </div>
                        <div class="grid-3-col" style="margin-top: 1.2rem;">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de nacimiento *</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            </div>
                            <div class="form-group">
                                <label for="edad">Edad</label>
                                <input type="text" id="edad" name="edad" placeholder="Ej: 1 año">
                            </div>
                            <div class="form-group">
                                <label for="genero">Género *</label>
                                <select id="genero" name="genero" required>
                                    <option value="" disabled selected>Selecciona el género</option>
                                    <option value="Macho">Macho</option>
                                    <option value="Hembra">Hembra</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="form-section">
                        <legend><i class="fas fa-file-alt icon-purple"></i> Información adicional</legend>

                        <div class="grid-3-col">
                            <div class="form-group">
                                <label for="color">Color</label>
                                <input type="text" id="color" name="color" placeholder="Ej: Crema">
                            </div>
                            <div class="form-group">
                                <label for="peso">Peso (kg)</label>
                                <input type="number" id="peso" name="peso" step="0.01" placeholder="Ej: 20">
                            </div>
                            <div class="form-group">
                                <label for="esterilizado">Esterilizado</label>
                                <select id="esterilizado" name="esterilizado">
                                    <option value="" disabled selected>Selecciona una opción</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="caracteristicas">Características / Señales particulares</label>
                            <textarea id="caracteristicas" name="caracteristicas" rows="2" placeholder="Ej: Mancha en su barriga..."></textarea>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend><i class="fas fa-address-book icon-orange"></i> Información de contacto</legend>
                        <div class="grid-2-col">
                            <div class="form-group">
                                <label for="veterinario">Veterinario de confianza</label>
                                <select id="veterinario" name="veterinario">
                                    <option value="" disabled selected>Selecciona un specialist</option>
                                    <option value="Dr. Carlos Mendoza">Dr. Carlos Mendoza</option>
                                    <option value="Dra. Ana Martínez">Dra. Ana Martínez</option>
                                    <option value="Dr. Luis Suárez">Dr. Luis Suárez</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notas adicionales</label>
                                <textarea id="notes" name="notes" rows="2" placeholder="Cualquier información importante sobre su salud..."></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="window.location.href='mascotas.php'">Cancelar</button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paw"></i> Guardar mascota
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <script>
    function detectarOtraEspecie(selectElement) {
        var contenedor = document.getElementById('contenedor_otra_especie');
        var inputOtra = document.getElementById('otra_especie');
        var grilla = document.getElementById('grillaBasica');

        if (selectElement.value === 'Otro') {
            contenedor.style.display = 'block';
            grilla.classList.add('con-otra-especie');
            inputOtra.required = true;
            inputOtra.focus();
        } else {
            contenedor.style.display = 'none';
            grilla.classList.remove('con-otra-especie');
            inputOtra.required = false;
            inputOtra.value = '';
        }
    }
    function previsualizarImagen(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var container = document.getElementById('previewContainer');
                var placeholder = document.getElementById('uploadPlaceholder');
                var imagenPrevia = container.querySelector('img');
                if (imagenPrevia) {
                    imagenPrevia.remove();
                }
                placeholder.style.display = 'none';

                var nuevaImg = document.createElement('img');
                nuevaImg.src = e.target.result;
                nuevaImg.alt = "Vista previa";
                container.insertBefore(nuevaImg, placeholder);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>
</html>
