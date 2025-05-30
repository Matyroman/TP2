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
