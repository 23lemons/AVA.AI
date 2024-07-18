<?php

require_once ('./config.php');

$erreur = "";
$nbErreur = 0;


$username = $email = $password = $confirm_password = '';

if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) 
&& isset($_POST["confirm_password"])){

    $username = $_POST["username"];

    // VERIFICATION DE L'EXISTENCE DE USAGER
    $requete = $conn->prepare("SELECT * FROM usagers WHERE username = :username");
    $requete->bindParam(":username", $username);
    $requete->execute();

    if($requete->fetch()){
        $erreur = "Nom d'utilisateur existe déjà";
        $nbErreur++;
    }

    // VERIFICATION DE LA LONGUEUR DU PASSWORD   
    else if(strlen($_POST["motdepasse"]) < 8){
        $erreur = "Le mot de passe doit comprendre au moins 8 caractères";
        $nbErreur++;
    }

    // VERIFICATION DE LA SIMILARITÉ DU PASSWORD 
    else if($_POST["motdepasse"] != $_POST["confirm_password"]){
        $erreur = "Les deux mots de passe ne sont pas pareils.";
        $nbErreur++;
    }

    if($nbErreur == 0){

        $requete = $conn->prepare("INSERT INTO Entreprise (username, email, motdepasse)
        VALUES (:username, :email, :motdepasse)");

        $requete->bindValue(":username", $username);
        $requete->bindValue(":email", $_POST["email"]);
        $requete->bindValue(":motdepasse", $_POST["motdepasse"]);

        $requete->execute();

        header("Location: informationEntreprise.php");
        exit();
    }


}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte Entreprise - Page 1</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <div class="left-section">
            <div class="logo">
                <img src="images/AVALOGOBLANC.png" alt="Votre Logo" />
            </div>
            <h1 class="sauce">Bienvenue chez AVA.AI</h1>
            <p class="sauce">Inscrivez vous pour accédez au futur</p>
        </div>
        <div class="right-section">
            <h2>Inscription</h2>
            <form action="informationEntreprise.php" method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Courriel de l'Entreprise</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Suivant</button>
            </form>
        </div>
    </div>
</body>
</html>
