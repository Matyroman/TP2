<?php
require 'config.php';

// Variables de estado
$error = '';
$login_button = '';

// Inicio de sesión tradicional
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_email_address'] = $user['email'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}

// Inicio de sesión con Google
if (isset($_GET["code"])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
    if (!isset($token['error'])) {
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];

        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        // Guardar datos del usuario en sesión
        $_SESSION['user_first_name'] = $data['given_name'] ?? '';
        $_SESSION['user_last_name'] = $data['family_name'] ?? '';
        $_SESSION['user_email_address'] = $data['email'] ?? '';
        $_SESSION['user_gender'] = $data['gender'] ?? '';
        $_SESSION['user_image'] = $data['picture'] ?? '';

        header("Location: dashboard.php");
        exit;
    }
}

// Mostrar botón de login con Google si aún no se ha autenticado
if (!isset($_SESSION['access_token']) && !isset($_SESSION['user_email_address'])) {
    $login_button = '<a href="' . $google_client->createAuthUrl() . '" style="background: #dd4b39; border-radius: 5px; color: white; display: block; font-weight: bold; padding: 20px; text-align: center; text-decoration: none; width: 200px;">Login con Google</a>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Iniciar Sesión</h2>

        <!-- Login tradicional -->
        <form method="post" class="mb-4">
            <input type="email" name="email" required placeholder="Correo" class="form-control mb-2">
            <input type="password" name="password" required placeholder="Contraseña" class="form-control mb-2">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
        <p class="text-danger"><?= $error ?></p>
        <p><a href="register.php">Crear cuenta</a></p>
        <p><a href="forgot_password.php">¿Olvidaste tu contraseña?</a></p>

        <!-- Login con Google -->
        <div class="text-center"><?= $login_button ?></div>

        <!-- Mostrar perfil si ya está logueado -->
        <?php if (isset($_SESSION['user_email_address']) && $login_button == ''): ?>
            <div class="card mt-4 text-center">
                <div class="card-header">Bienvenido</div>
                <div class="card-body">
                    <img src="<?= $_SESSION['user_image'] ?>" class="rounded-circle mb-3" style="width: 100px;">
                    <h4><?= $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name'] ?></h4>
                    <p><?= $_SESSION['user_email_address'] ?></p>
                    <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
