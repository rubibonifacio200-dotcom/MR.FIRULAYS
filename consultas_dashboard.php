<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$resIngresos = mysqli_query($conexion, "SELECT SUM(monto) as total FROM pagos");
$totalIngresos = mysqli_fetch_assoc($resIngresos)['total'] ?? 0;

$resUsuarios = mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuarios");
$totalClientes = mysqli_fetch_assoc($resUsuarios)['total'] ?? 0;

$meses = [];
$montos = [];
$sqlGrafico = "SELECT MONTHNAME(fecha_pago) as mes, SUM(monto) as total 
            FROM pagos 
            GROUP BY MONTH(fecha_pago) 
            ORDER BY MONTH(fecha_pago) ASC LIMIT 6";
$resGrafico = mysqli_query($conexion, $sqlGrafico);

while($row = mysqli_fetch_assoc($resGrafico)) {
    $meses[] = $row['mes'];
    $montos[] = (float)$row['total'];
}
$totalMascotas = 0; 
$totalCitas = 0;
?>