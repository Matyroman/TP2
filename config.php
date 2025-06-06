<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos (MySQL con PDO)
$host = 'localhost';
$db = 'tp2';           // Reemplaza si tu base de datos tiene otro nombre
$user = 'root';        // Cambia si tu usuario de MySQL es distinto
$pass = '';            // Cambia si tu contraseña no está vacía

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Google Client
require_once 'vendor/autoload.php';

$google_client = new Google_Client();
$google_client->setClientId('113200675580-2kav2aluqirvm4r1vf4cqvprds0u52am.apps.googleusercontent.com');
$google_client->setClientSecret('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');
$google_client->setRedirectUri('http://localhost/TP2/index.php');
$google_client->addScope('email');
$google_client->addScope('profile');
?>

