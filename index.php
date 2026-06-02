<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MR. Firulays - Clínica Veterinaria</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <i class="fas fa-paw logo-icon"></i>
                <div class="logo-text">
                    <span>MR.Firulays</span>
                    <p class="sub-logo">Clínica Veterinaria</p>
                </div>
            </div>
            
            <div class="nav-right">
                <ul class="nav-links">
                    <li><a href="sobrenosotros.html" class="nav-link-item">Sobre nosotros</a></li>
                    <li><a href="staff.html" class="nav-link-item">Staff</a></li>
                    <li><a href="servicios.html" class="nav-link-item">Servicios</a></li>
                    <li><a href="consejocomunidad.php" class="nav-link-item">Consejos/Comunidad</a></li>
                    <li><a href="dashboard.php" class="nav-link-item">Dashboard</a></li>
                </ul>
                <button class="btn-login" id="openLogin">
                    <i class="fas fa-user-circle"></i> Iniciar sesión
                </button>
            </div>
        </nav>
    </header>

    <main class="hero-wrapper">
        <section class="hero">
            <div class="hero-content">
                <h1>Bienvenido a la<br><span class="clinic-text">Clínica Veterinaria</span><br><span class="brand-name">MR.FIRULAYS <i class="fas fa-paw paw-light"></i></span></h1>
                <p class="hero-p">Cuidamos a tu mascota<br>como parte de nuestra familia <i class="far fa-heart"></i></p>
                
                <div class="hero-buttons">
                    <div class="info-pill-blue">Regístrate en MR. Firulays</div>
                    <button class="btn-secondary" id="openRegisterHero2">Crear mi cuenta <i class="fas fa-arrow-right"></i></button>
                </div>

                <div class="features-pill">
                    <div class="f-item"><i class="fas fa-wrench"></i><div class="f-text"><strong>Atención</strong><span>Profesional</span></div></div>
                    <div class="f-item"><i class="fas fa-heart"></i><div class="f-text"><strong>Cuidado</strong><span>Con amor</span></div></div>
                    <div class="f-item"><i class="fas fa-shield-alt"></i><div class="f-text"><strong>Bienestar</strong><span>Garantizado</span></div></div>
                </div>
            </div>

            <div class="hero-image">
                <img src="img/perrito y gato.png" alt="Mascotas MR Firulays">
            </div>
        </section>
    </main>

    <!-- MODAL DE INICIO DE SESIÓN -->
    <div id="loginModal" class="modal-overlay">
        <div class="login-box">
            <span class="close-modal" id="closeLogin">&times;</span>
            <h2>Iniciar sesión</h2>
            <p class="subtitle">Veterinaria MR. FIRULAYS</p>

            <form action="login_proceso.php" method="POST">
                <div class="input-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="correo" placeholder="ejemplo@correo.com" required>
                </div>
                <div class="input-group">
                    <label>Contraseña</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required>
                        <i class="fas fa-eye toggle-icon" id="togglePassword"></i>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Entrar</button>
            </form>
        </div>
    </div>

    <!-- MODAL DE REGISTRO OPTIMIZADO -->
    <div id="registerModal" class="modal-overlay">
        <div class="login-box">
            <span class="close-modal" id="closeRegister">&times;</span>
            <h2>Registrarse</h2>
            <p class="subtitle">Veterinaria MR. FIRULAYS</p>

            <form action="registro_proceso.php" method="POST">
                <div class="input-group">
                    <label><i class="fas fa-user"></i> Nombre completo</label>
                    <input type="text" name="nombre" placeholder="Ingresa tu nombre completo" required>
                </div>
                
                <!-- CASILLA DNI CON VALIDACIÓN DE 8 DÍGITOS EXÁCTOS -->
                <div class="input-group">
                    <label><i class="fas fa-id-card"></i> DNI</label>
                    <input type="text" 
                           name="dni" 
                           placeholder="Ingresa los 8 dígitos" 
                           maxlength="8" 
                           minlength="8" 
                           pattern="[0-9]+" 
                           title="El DNI debe contener exactamente 8 números" 
                           required>
                </div>
                
                <!-- CASILLA TELÉFONO CON VALIDACIÓN DE 9 DÍGITOS EXÁCTOS -->
                <div class="input-group">
                    <label><i class="fas fa-phone"></i> Teléfono</label>
                    <input type="text" 
                           name="telefono" 
                           placeholder="Ingresa los 9 dígitos" 
                           maxlength="9" 
                           minlength="9" 
                           pattern="[0-9]+" 
                           title="El número de teléfono debe contener exactamente 9 números" 
                           required>
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-envelope"></i> Correo electrónico</label>
                    <input type="email" name="correo" placeholder="ejemplo@correo.com" required>
                </div>
                <div class="input-group">
                    <label><i class="fas fa-lock"></i> Contraseña</label>
                    <div class="password-container">
                        <input type="password" name="password" id="regPassword" placeholder="Crea una contraseña" required>
                        <i class="fas fa-eye toggle-icon" id="toggleRegPassword"></i>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Crear cuenta</button>
            </form>

            <div class="bottom-text">
                ¿Ya tienes una cuenta? <a href="#" id="toLogin">Iniciar sesión</a>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-group">
                <span class="footer-label">Búscanos en nuestras redes:</span>
                <div class="footer-social-icons">
                    <a href="https://www.instagram.com/mrfirulaysxs?igsh=MXFhOHdtZjV3cTB6Zw=="><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="https://www.facebook.com/share/1Dugv77nDb/"><i class="fab fa-facebook"></i> Facebook</a>
                </div>
            </div>
            <div class="footer-group">
                <span class="footer-label">Mándanos un mensaje:</span>
                <a href="https://wa.me/51923878253" target="_blank" class="footer-link"><i class="fab fa-whatsapp"></i> WhatsApp</a>
            </div>
            <div class="footer-group">
                <span class="footer-label">Correo:</span>
                <a href="mailto:mrfirulaysxs@gmail.com" class="footer-link"><i class="far fa-envelope"></i> mrfirulaysxs@gmail.com</a>
            </div>
        </div>
    </footer>

    <script>
        const loginModal = document.getElementById("loginModal");
        const regModal = document.getElementById("registerModal");

        // Abrir modales
        document.getElementById("openLogin").onclick = () => { loginModal.style.display = "flex"; };
        document.getElementById("openRegisterHero2").onclick = () => { regModal.style.display = "flex"; };

        // Cerrar modales
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.onclick = () => {
                loginModal.style.display = "none";
                regModal.style.display = "none";
            };
        });

        // Cambiar de Registro a Login
        document.getElementById("toLogin").onclick = (e) => {
            e.preventDefault();
            regModal.style.display = "none";
            loginModal.style.display = "flex";
        };

        // Ver/Ocultar contraseña
        const setupEye = (iconId, inputId) => {
            const icon = document.getElementById(iconId);
            const input = document.getElementById(inputId);
            if (icon && input) {
                icon.onclick = () => {
                    const isPassword = input.type === "password";
                    input.type = isPassword ? "text" : "password";
                    icon.classList.toggle("fa-eye");
                    icon.classList.toggle("fa-eye-slash");
                };
            }
        };
        setupEye("togglePassword", "password");
        setupEye("toggleRegPassword", "regPassword");
    </script>
</body>
</html>