<?php
require 'config.php';

$token = $_GET['token'] ?? '';
$msg = '';

// Validar si el token fue proporcionado
if (!$token) {
    die("Token no proporcionado.");
}

// Buscar el usuario con ese token y que no esté expirado
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Token inválido o expirado.");
}

// Si el formulario fue enviado, actualizar la contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];

    if (strlen($new_password) < 6) {
        $msg = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);

        $msg = "Contraseña actualizada correctamente. <a href='index.php'>Iniciar sesión</a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h2>Restablecer Contraseña</h2>
    <?php if ($msg): ?>
        <p><?= $msg ?></p>
    <?php else: ?>
        <form method="post">
            <label>Nueva contraseña:</label><br>
            <input type="password" name="password" required placeholder="Nueva contraseña"><br><br>
            <button type="submit">Actualizar contraseña</button>
        </form>
    <?php endif; ?>
</body>
</html>
