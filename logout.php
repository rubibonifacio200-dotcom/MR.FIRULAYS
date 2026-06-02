<?php
session_start();
session_destroy(); // Esto es lo que rompe la memoria del navegador
header("Location: index.php");
exit();
?>