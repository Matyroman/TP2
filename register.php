<?php
require 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido.";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        // Verificar si ya existe el email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "El correo ya está registrado.";
        } else {
            // Insertar usuario con password hasheada
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            if ($stmt->execute([$email, $password_hash])) {
                $success = "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar usuario.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Usuario</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="text-center">Crear Cuenta</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
        <p><a href="index.php">Ir a iniciar sesión</a></p>
    <?php else: ?>
        <form method="post">
            <input type="email" name="email" required placeholder="Correo" class="form-control mb-2">
            <input type="password" name="password" required placeholder="Contraseña" class="form-control mb-2">
            <input type="password" name="confirm_password" required placeholder="Confirmar contraseña" class="form-control mb-2">
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </form>
        <p><a href="index.php">¿Ya tienes cuenta? Inicia sesión</a></p>
    <?php endif; ?>
</div>
</body>
</html>
