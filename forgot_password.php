<?php
require 'config.php';

$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600); // válido 1 hora

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        $link = "http://localhost/TP2/reset_password.php?token=$token";
        $msg = "Enlace de recuperación: <a href='$link'>$link</a>";
    } else {
        $error = "El correo no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Contraseña</title>
    <meta charset="utf-8">
       <link rel="stylesheet" href="css.css">
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<h2>Recuperar Contraseña</h2>
<form method="post">
    <input type="email" name="email" required placeholder="Correo"><br>
    <button type="submit">Enviar enlace</button>
</form>

<?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
<?php if ($msg): ?><p><?= $msg ?></p><?php endif; ?>

<a href="index.php">Volver al login</a>
</body>
</html>
