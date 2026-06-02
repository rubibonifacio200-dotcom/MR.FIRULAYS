<?php 
include 'conexion.php'; 
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res_pregunta = mysqli_query($conexion, "SELECT * FROM consejocomunidad WHERE id = $id");
$post = mysqli_fetch_assoc($res_pregunta);
$sql_respuestas = "SELECT * FROM respuestas WHERE id_pregunta = $id ORDER BY id DESC";
$res_respuestas = mysqli_query($conexion, $sql_respuestas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MR. Firulays - Respuestas</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --azul: #003366; --celeste: #0057C2; --fondo: #fcfdfe; }
        body { font-family: 'Poppins', sans-serif; background: var(--fondo); margin: 0; padding: 30px; }
        .container { max-width: 750px; margin: auto; }
        .header-pregunta { background: white; padding: 30px; border-radius: 25px; border: 1px solid #eef2f6; margin-bottom: 25px; }
        .header-pregunta h1 { color: var(--azul); font-family: 'Montserrat'; font-size: 24px; margin: 0; }
        .caja-comentario { background: white; padding: 25px; border-radius: 25px; border: 1px solid #eef2f6; margin-bottom: 30px; }
        textarea { width: 100%; height: 100px; padding: 15px; border-radius: 15px; border: 1px solid #e2e8f0; background: #f8fafc; font-family: inherit; resize: none; box-sizing: border-box; }
        .btn-enviar { background: var(--azul); color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; cursor: pointer; margin-top: 10px; }
        .respuesta-item { background: #fff; padding: 20px; border-radius: 20px; border: 1px solid #eef2f6; margin-bottom: 15px; display: flex; gap: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.01); }
        .user-icon { color: #cbd5e0; font-size: 25px; }
        .texto-comentario { color: #4a5568; font-size: 15px; line-height: 1.5; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <a href="consejocomunidad.php" style="color: var(--celeste); text-decoration: none; font-weight: 700; font-size: 14px;">
            <i class="fas fa-chevron-left"></i> Volver a la comunidad
        </a>
        <div class="header-pregunta">
            <h1><?php echo htmlspecialchars($post['titulo']); ?></h1>
            <p style="color:#a0aec0; font-size: 13px; margin-top:10px;">
                <i class="far fa-comment"></i> <?php echo $post['respuestas_count']; ?> comentarios
            </p>
        </div>
        <div class="caja-comentario">
            <h3 style="color: var(--azul); margin-top:0; font-size: 18px;">Escribe tu respuesta o comentario:</h3>
            <form action="guardar_respuesta.php" method="POST">
                <input type="hidden" name="id_pregunta" value="<?php echo $id; ?>">
                <textarea name="respuesta" placeholder="Escribe aquí tu comentario..." required></textarea>
                <button type="submit" class="btn-enviar">Publicar mi opinión</button>
            </form>
        </div>
        <h3 style="color: var(--azul); margin-bottom: 20px;">Respuestas publicadas:</h3>
        <?php 
        if (mysqli_num_rows($res_respuestas) > 0) {
            while ($reg = mysqli_fetch_assoc($res_respuestas)) {
                ?>
                <div class="respuesta-item">
                    <div class="user-icon"><i class="fas fa-user-circle"></i></div>
                    <div>
                        <p class="texto-comentario"><?php echo nl2br(htmlspecialchars($reg['contenido'])); ?></p>
                        <small style="color: #cbd5e0; font-size: 11px;"><?php echo $reg['fecha_respuesta']; ?></small>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div style="text-align:center; color:#a0aec0; margin-top:30px;">
                    <i class="far fa-comments" style="font-size:30px;"></i>
                    <p>Aún no hay respuestas. ¡Sé el primero!</p>
                </div>';
        }
        ?>
    </div>
</body>
</html>