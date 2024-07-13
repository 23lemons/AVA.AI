<?php

require_once ('./config.php');

$erreur = "";
$nbErreur = 0;

$username = $nom = $prenom = $nomEntreprise = $numTelephone = $adresse = $heures = $lienWeb = "";

if(isset($_POST["nomEntreprise"]) && isset($_POST["prenomFondateur"]) && isset($_POST["nomFondateur"])
&& isset($_POST["username"]) &&ISSET($_POST["courriel"]) && isset($_POST["motdepasse"]) && isset($_POST["motdepasse2"])
&& isset($_POST["numTelephone"]) && isset($_POST["adresseEntreprise"]) && isset($_POST["lienSiteWeb"])){
    
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
    else if($_POST["motdepasse"] != $_POST["motdepasse2"]){
        $erreur = "Les deux mots de passe ne sont pas pareils.";
        $nbErreur++;
    }

    if($nbErreur == 0){
        $requete = $conn->prepare("INSERT INTO Entreprise (username_entreprise, mdp_entreprise, nom_entreprise, 
        prenom_fondateur, nom_fondateur, num_tel_entreprise, courriel_entreprise,
        adresse_entreprise, heures_ouvertures, lien_site_web, lien_page_facebook,
        lien_page_instagram, lien_page_tiktok, lien_chaine_youtube, description_entreprise,
        description_service, prix_service, temps_livraison) 
        VALUES (:username, :motdepasse, :nomEntreprise, :prenomFondateur, :nomFondateur,
        :numTelephone, :courriel, :adresseEntreprise, :heures, :lienSiteWeb, :lienFB, :lienIG, 
        :lienTiktok, :lienYT, :descriptionEntreprise, :descriptionService, :prixService, :tempsLivraison)");

        $requete->bindValue(":username", $username);
        $requete->bindValue(":motdepasse", password_hash($_POST["motdepasse"], PASSWORD_DEFAULT));
        $requete->bindValue(":nomEntreprise", $_POST["nomEntreprise"]);
        $requete->bindValue(":prenomFondateur", $_POST["prenomFondateur"]);
        $requete->bindValue(":nomFondateur", $_POST["nomFondateur"]);
        $requete->bindValue(":numTelephone", $_POST["numTelephone"]);
        $requete->bindValue(":courriel", $_POST["courriel"]);
        $requete->bindValue(":adresseEntreprise", $_POST["adresseEntreprise"]);
        $requete->bindValue(":heures", $_POST["heures"]);
        $requete->bindValue(":lienSiteWeb", $_POST["lienSiteWeb"]);
        $requete->bindValue(":lienFB", $_POST["lienFaceBook"]);
        $requete->bindValue(":lienIG", $_POST["lienInstagram"]);
        $requete->bindValue(":lienTiktok", $_POST["lienTiktok"]);
        $requete->bindValue(":lienYT", $_POST["lienYoutube"]);
        $requete->bindValue(":descriptionEntreprise", $_POST["descriptionEntreprise"]);
        $requete->bindValue(":descriptionService", $_POST["descriptionService"]);
        $requete->bindValue(":prixService", $_POST["prixService"]);
        $requete->bindValue(":tempsLivraison", $_POST["tempsLivraison"]);

        $requete->execute();

        header('Location: login.php');//AMINE
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<main class= "NouveauProfil">

    <h2>Créer votre compte</h2>
    <h3>Informations de base sur l'entreprise :</h3>

    <?php
        if($nbErreur > 0){
            echo "<div class='alert alert-danger'>$erreur</div>";
        }
    ?>

    <div>
        <form method="post" action="./NouveauProfil.php">

            <label for="nomEntreprise">Nom de l'entreprise :</label>
            <input type="text" id="nomEntreprise" name="nomEntreprise" value="" class="form-control" required />

            <label for="prenomFondateur">Votre prénom :</label>
            <input type="text" id="prenomFondateur" name="prenomFondateur" value="" class="form-control" required />

            <label for="nomFondateur">Votre nom :</label>
            <input type="text" id="nomFondateur" name="nomFondateur" value="" class="form-control" required />

            <label for="courriel">Courriel :</label>
            <input type="text" id="courriel" name="courriel" value="" class="form-control" required />

            <label for="username">Username :</label>
            <input type="text" id="username" name="username" value="" class="form-control" required />

            <label for="motdepasse">Password :</label>
            <input type="password" id="motdepasse" name="motdepasse" value="" class="form-control" required />

            <label for="motdepasse2">Confirmation du password :</label>
            <input type="password" id="motdepasse2" name="motdepasse2" value="" class="form-control" required />

            <label for="numTelephone">Le numéro de téléphone de l'entreprise :</label>
            <input type="text" id="numTelephone" name="numTelephone" value="" class="form-control" required />

            <label for="adresseEntreprise">Votre adresse :</label>
            <input type="text" id="adresseEntreprise" name="adresseEntreprise" value="" class="form-control" required />

            <label for="heures">Vos heures d'ouverture :</label>
            <input type="text" id="heures" name="heures" value="Lundi au vendredi de 9h à 17h" class="form-control" />

            <label for="lienSiteWeb">Le lien vers votre site Web :</label>
            <input type="url" id="lienSiteWeb" name="lienSiteWeb" value="" class="form-control" required />

            <label for="lienTiktok">Le lien vers votre page TikTok :</label>
            <input type="url" id="lienTiktok" name="lienTiktok" value="" class="form-control" />

            <label for="lienFaceBook">Le lien vers votre page Facebook :</label>
            <input type="url" id="lienFaceBook" name="lienFaceBook" value="" class="form-control" />

            <label for="lienInstagram">Le lien vers votre page Instagram :</label>
            <input type="url" id="lienInstagram" name="lienInstagram" value="" class="form-control" />

            <label for="lienYoutube">Le lien vers votre chaîne YouTube :</label>
            <input type="url" id="lienYoutube" name="lienYoutube" value="" class="form-control" />

            <h3>Informations sur vos services et produits offerts :</h3>

            <label for="descriptionEntreprise">Décrivez votre entreprise :</label>
            <input type="text" id="descriptionEntreprise" name="descriptionEntreprise" value="" class="form-control" required />

            <label for="descriptionService">Décrivez le ou les services que vous offrez :</label>
            <input type="text" id="descriptionService" name="descriptionService" value="" class="form-control" required />
            
            <label for="prixService"> Détaillez vos prix :</label>
            <input type="text" id="prixService" name="prixService" value="" class="form-control" required />

            <label for="tempsLivraison"> Indiquez votre temps de livraison :</label>
            <input type="text" id="tempsLivraison" name="tempsLivraison" value="" class="form-control" required />

            <input type="submit" value="Créer le compte" class="form-control" />
        </form>
    </div>
</main>

</body>
</html>
