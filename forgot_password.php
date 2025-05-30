<?php
require 'config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Verificar que el correo exista en la base
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hora

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        $link = "http://localhost/reset_password.php";
        $msg = "Enlace de recuperación: <a href='$link'>$link</a>";
    } else {
        $msg = "El correo no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Recuperar Contraseña</title></head>
<body>
    <h2>Recuperar Contraseña</h2>
    <form method="post">
        <input type="email" name="email" required placeholder="Correo"><br>
        <a href="reset_password.php"><button type="submit">Enviar enlace</button></a> 
    </form>
    <p><?= $msg ?></p>
    <a href="index.php">Volver al login</a>
</body>
</html>
