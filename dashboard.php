<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Panel</title></head>
<link rel="stylesheet" href="css.css">
<body>
<h2>Bienvenido, <?= $_SESSION['user'] ?></h2>
<p>Has iniciado sesión correctamente.</p>
<a href="logout.php">Cerrar sesión</a>
</body>
</html>
