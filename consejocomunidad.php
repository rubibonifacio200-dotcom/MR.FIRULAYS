<?php 
include 'conexion.php'; 
$busqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'recientes';
$orden = "fecha_registro DESC"; 
if ($filtro == 'populares') $orden = "respuestas_count DESC";
if ($filtro == 'antiguos') $orden = "fecha_registro ASC";
if ($filtro == 'para_ti') $orden = "RAND()"; 
$sql = "SELECT * FROM consejocomunidad WHERE titulo LIKE '%$busqueda%' ORDER BY $orden";
$resultado = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comunidad - MR. Firulays</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --azul: #003366; --celeste: #0057C2; --fondo: #fcfdfe; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background: #fff; }
        .navbar { display: flex; justify-content: space-between; padding: 20px 5%; border-bottom: 1px solid #eee; align-items: center; }
        .comunidad-container { display: flex; padding: 40px 5%; gap: 30px; background: var(--fondo); min-height: 100vh; }
        .cards-grid { flex: 3; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; align-content: flex-start; }
        .card { background: white; border-radius: 25px; padding: 30px; border: 1px solid #eef2f6; box-shadow: 0 5px 15px rgba(0,0,0,0.02); display: flex; flex-direction: column; }
        .card h3 { color: var(--azul); font-family: 'Montserrat'; font-size: 19px; margin-bottom: 10px; }
        .skeleton { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
        .skeleton i { color: #cbd5e0; font-size: 20px; }
        .line { height: 8px; background: #f0f4f8; border-radius: 4px; flex: 1; }
        .btn-opinar { text-decoration: none; text-align: center; background: #f0f7ff; color: var(--celeste); border: none; padding: 12px; border-radius: 50px; font-weight: 600; cursor: pointer; margin-top: auto; transition: 0.3s; }
        .btn-opinar:hover { background: var(--celeste); color: white; }
        .sidebar { flex: 1; background: white; padding: 30px; border-radius: 30px; border: 1px solid #eef2f6; height: fit-content; position: sticky; top: 20px; }
        .search-bar { position: relative; margin-bottom: 25px; }
        .search-bar input { width: 100%; padding: 12px 12px 12px 45px; border-radius: 25px; border: 1px solid #e2e8f0; box-sizing: border-box; background: #f8fafc; }
        .search-bar i { position: absolute; left: 18px; top: 15px; color: var(--azul); }
        .filter-link { display: flex; align-items: center; gap: 15px; text-decoration: none; color: var(--azul); padding: 12px 20px; border: 1px solid #eef2f6; border-radius: 25px; margin-bottom: 10px; font-weight: 600; transition: 0.2s; }
        .filter-link:hover, .filter-link.active { background: #f0f7ff; border-color: var(--celeste); color: var(--celeste); }
        .btn-plus { width: 65px; height: 65px; background: var(--azul); color: white; border-radius: 50%; border: none; font-size: 25px; cursor: pointer; display: block; margin: 30px auto 10px; box-shadow: 0 8px 20px rgba(0,51,102,0.2); }
#modalPost {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px); 
    align-items: center; 
    justify-content: center;
}
.modal-content {
    background-color: #fff;
    padding: 40px; 
    border-radius: 30px; 
    width: 90%;
    max-width: 500px; 
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); 
    text-align: center;
    border: none;
}
.modal-content input[type="text"] {
    width: 100%;
    padding: 18px;
    margin: 20px 0;
    border-radius: 15px; 
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
    font-family: 'Poppins', sans-serif;
    box-sizing: border-box; 
    outline: none;
    font-size: 16px;
}
.btn-publicar-modal {
    background-color: #003366; 
    color: white;
    width: 100%;
    padding: 15px;
    border-radius: 50px; 
    border: none;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}
.btn-publicar-modal:hover {
    background-color: #0057C2;
}
</style>
</head>
<body>
    <nav class="navbar">
        <div style="font-family: 'Montserrat'; font-weight: 800; color: var(--azul); font-size: 22px;"><i class="fas fa-paw"></i> MR.Firulays</div>
        <div style="display: flex; gap: 25px; font-weight: 600;">
            <a href="index.php" style="text-decoration:none; color:#666;">Inicio</a>
            <a href="sobrenosotros.html" style="text-decoration:none; color:#666;">Sobre Nosotros</a>
            <a href="staff.html" style="text-decoration:none; color:#666;">Staff</a>
            <a href="servicios.html" style="text-decoration:none; color:#666;">Servicios</a>
            <a href="#" style="text-decoration:none; color:var(--azul); border-bottom: 2px solid var(--azul);">Consejos/Comunidad</a>
            <a href="dashboard.php" style="text-decoration:none; color:#666;">Dashboard</a>
        </div>
    </nav>
    <main class="comunidad-container">
        <div class="cards-grid">
            <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                <div class="card">
                    <h3><?php echo $row['titulo']; ?></h3>
                    <p style="color:#a0aec0; font-size:13px; margin-bottom:15px;"><?php echo $row['respuestas_count']; ?> respuestas</p>
                    <div class="skeleton"><i class="fas fa-user-circle"></i> <div class="line" style="width:70%"></div></div>
                    <div class="skeleton"><i class="fas fa-user-circle"></i> <div class="line" style="width:40%"></div></div>          
                    <a href="ver_consejo.php?id=<?php echo $row['id']; ?>" class="btn-opinar">
                    <i class="far fa-comment"></i> Comparte tu opinión
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
        <aside class="sidebar">
            <form action="consejocomunidad.php" method="GET" class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="buscar" placeholder="Buscar..." value="<?php echo htmlspecialchars($busqueda); ?>">
            </form>
            <p style="font-weight: 800; color: var(--azul); margin-bottom: 15px;">Filtrar por:</p>
            <a href="consejocomunidad.php?filtro=recientes" class="filter-link"><i class="far fa-clock"></i> Recientes</a>
            <a href="consejocomunidad.php?filtro=populares" class="filter-link"><i class="fas fa-fire"></i> Populares</a>
            <a href="consejocomunidad.php?filtro=para_ti" class="filter-link"><i class="far fa-heart"></i> Para ti</a>
            <a href="consejocomunidad.php?filtro=antiguos" class="filter-link"><i class="far fa-calendar-alt"></i> Mas antiguos</a>
            <button class="btn-plus" onclick="document.getElementById('modalPost').style.display='flex'"><i class="fas fa-plus"></i></button>
            <p style="text-align: center; color: var(--celeste); font-weight: 800; font-size: 13px;">Crear nueva publicación</p>
        </aside>
    </main>
<div id="modalPost">
    <div class="modal-content">
        <h2 style="color: #003366; font-family: 'Montserrat'; font-weight: 800; margin-bottom: 10px;">Nueva Publicación</h2>
        <form action="guardar_post.php" method="POST">
            <input type="text" 
                name="titulo" 
                placeholder="¿Qué quieres preguntar?" 
                required>
            <button type="submit" class="btn-publicar-modal">Publicar</button>
            <button type="button" 
                    onclick="document.getElementById('modalPost').style.display='none'" 
                    style="width:100%; border:none; background:none; margin-top:20px; color:#a0aec0; cursor:pointer; font-weight:600;">
                Cancelar
            </button>
        </form>
    </div>
</div>
</body>
</html>