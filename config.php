<?php

//start session on web page
session_start();

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID | Copiar "Aqui tu ID DE CLIENTE"
$google_client->setClientId('113200675580-2kav2aluqirvm4r1vf4cqvprds0u52am.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key Aqui tu CLAVE
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI | URL AUTORIZADO
$google_client->setRedirectUri('http://localhost/TP2/google-login/index.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');

?>
