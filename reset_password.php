<?php
require 'config.php';

$token = $_GET['token'] ?? '';
$msg = '';
$error = '';

if (!$token) {
    die("Token no proporcionado.");
}

// Validar el token y su vencimiento
$stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Token inválido o expirado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hash, $user['id']]);
        $msg = "Contraseña actualizada correctamente. <a href='index.php'>Iniciar sesión</a>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Restablecer Contraseña</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<h2>Restablecer Contraseña</h2>

<?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
<?php if ($msg): ?>
    <p><?= $msg ?></p>
<?php else: ?>
<form method="post">
    <input type="password" name="password" required placeholder="Nueva contraseña"><br>
    <input type="password" name="confirm" required placeholder="Confirmar contraseña"><br>
    <button type="submit">Cambiar contraseña</button>
</form>
<?php endif; ?>

</body>
</html>
