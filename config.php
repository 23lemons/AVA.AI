<?php
// Démarrer la session
session_start();

if(isset($_GET["logout"])){
    unset($_SESSION['user_loggedin']);
  }

// Informations de connexion à la base de données
$hostname = "db";
$username = "user";
$password = "password";
$database = "mydatabase";


// Établir la connexion
try {
    $conn = new PDO("mysql:host=$hostname;dbname=$database",
            "$username", "$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connexion échouée: " . $e->getMessage();
}