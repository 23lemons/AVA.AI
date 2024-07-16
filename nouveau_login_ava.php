<?php

require_once ('./config.php');


$erreur ="";
  $nbErreur = 0;
  $nom_utilisateur = "";

  if (isset($_SESSION['user_loggedin'])) {
    header('Location: PAGEPRINCIPAL A CHANGER ICI METTRE LE CHEMIN'); //METTRE LE CHEMIN DE LA PAGE PRINCIPALE ICI
    exit();
}

  if(isset($_POST["username"]) && isset($_POST["password"])){

    $nom_utilisateur = $_POST['username'];
    $mot_de_passe = $_POST['password'];

    if (!empty($nom_utilisateur) && !empty($mot_de_passe)) {
        // Préparer la requête pour rechercher l'utilisateur par nom d'utilisateur
        $requete = $conn->prepare("SELECT * FROM `usagers` WHERE `username` = :username");
        $requete->bindParam(":username", $nom_utilisateur);
        $requete->execute();

        // Récupérer les résultats de la requête
        $utilisateur = $requete->fetch();

        // Vérifier si l'utilisateur existe et si le mot de passe correspond
        if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            // L'utilisateur est authentifié
            $_SESSION['username'] = $utilisateur['username'];
            header('Location: inser ici'); //INSERT HERE PATH TO PRINCIPAL
            exit();
        } else {
            $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
            $nbErreur++;
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
        $nbErreur++;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">Votre Logo</div>
            <h1>Bienvenue</h1>
            <p>Veuillez vous connecter pour accéder à votre compte</p>
        </div>
        <div class="right-section">
            <h2>Connexion</h2>
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password">
            </div>
            <button class="btn" onclick="login()">Se connecter</button>
            <button class="btn" onclick="register()">S'inscrire</button>
            
        </div>
    </div>

    <script>
        function login() {
            
            alert('Connexion réussie!');
        }

        function register() {
            // Redirection vers la page d'inscription
            window.location.href = 'informationPrincipale.php';
        }
    </script>
</body>
</html>
