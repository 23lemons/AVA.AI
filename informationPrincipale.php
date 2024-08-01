<?php

require_once ('./config.php');

$erreur = "";
$nbErreur = 0;


$username = $email = $password = $confirm_password = '';

if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) 
&& isset($_POST["confirm_password"])){

    $username = $_POST["username"];

    // VERIFICATION DE L'EXISTENCE DE USAGER
    $requete = $conn->prepare("SELECT * FROM Entreprise WHERE username_entreprise = :username");
    $requete->bindParam(":username", $username);
    $requete->execute();

    if($requete->fetch()){
        $erreur = $erreur . "Nom d'utilisateur existe déjà\n";
        $nbErreur++;
    }

    // VERIFICATION DE LA LONGUEUR DU PASSWORD   
    else if(strlen($_POST["password"]) < 8){
        $erreur = $erreur . "Le mot de passe doit comprendre au moins 8 caractères\n";
        $nbErreur++;
    }

    // VERIFICATION DE LA SIMILARITÉ DU PASSWORD 
    else if($_POST["password"] != $_POST["confirm_password"]){
        $erreur = $erreur . "Les deux mots de passe ne sont pas pareils\n";
        $nbErreur++;
 
   }

    if($nbErreur == 0){

        $requete = $conn->prepare("INSERT INTO Entreprise (username_entreprise, courriel_entreprise, mdp_entreprise)
        VALUES (:username, :email, PASSWORD(:motdepasse) )");

        $requete->bindValue(":username", $username);
        $requete->bindValue(":email", $_POST["email"]);
        $requete->bindValue(":motdepasse", $_POST["password"]);

        $requete->execute();


        $_SESSION["id_entreprise"] = $conn->lastInsertId();
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
            <form action="informationPrincipale.php" method="POST">
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
                <p class="erreur"> <?php if($nbErreur){ echo $erreur;} ?> </p>
            </form>
        </div>
    </div>
</body>
</html>