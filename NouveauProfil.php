<?php

require_once ('./config.php');

$erreur = "";
$nbErreur = 0;

$username = $nom = $prenom = $nomEntreprise = $numTelephone = $adresse = $heures = $lienWeb = "";

if(isset($_POST["nomEntreprise"]) && isset($_POST["prenomFondateur"]) && isset($_POST["nomFondateur"])
&& isset($_POST["username"]) && isset($_POST["motdepasse"]) && isset($_POST["motdepasse2"])
&& isset($_POST["numTelephone"]) && isset($_POST["adresse"]) && isset(["LienSiteWeb"])){

    $nom = $_POST["nomFondateur"];
    $prenom = $_POST["prenomFondateur"];
    $nomEntreprise = $_POST["nomEntreprise"];
    $username = $_POST["username"];


    //VERIFICATION DE L'EXISTENCE DE USAGER
    $requete = $conn->prepare("SELECT * FROM 'usagers' WHERE 'username' = :username");
    $requete->bindParam(":username", $username);
    $requete->execute();

    if($requete->fetch()){
        $erreur = "Nom d'utilisateur existe deja";
        $nbErreur++;
    }

    //VERIFICATION DE LA LONGUEUR DU PASSWORD   
    else if((strlen($_POST["motdepasse"])) < 8){

        $erreur = "Le mot de passe doit comprendre au moins 8 caracteres";
        $nbErreur++;
    }

    //VERIFICATION DE LA SIMILARITE DU PASSWORD 
    else if($_POST["motdepasse"] != $_POST["motdepasse2"]){

        $erreur = "Les deux mots de passes ne sont pas pareils.";
        $nbErreur++;
      }

    //VERIFICATION QUE LES DEUX NE SONT PAS VIDE
    else if(empty($_POST["nomFondateur"]) || empty($_POST["prenomFondateur"]) || empty($_POST["nomEntreprise"])){

          $erreur = "Le nom et/ou le prénom est vide";
          $nbErreur++;
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

            <h2>Créer votre compte<h2>
            <h3>Informations de base sur l'entreprise : </h3>

            <?php
                if($nbErreur > 0){
                    echo "<div class='alert alert-danger'> $erreur</div>";
                }
            ?>
            
            <div>
            <form method="post" action="./NouveauProfil.php">

            <label for="nomEntreprise">Nom de l'entreprise :</label>
            <input 
            type="text"
            id="nomEntreprise"
            name="nomEntreprise"
            value=""
            class="form-control"
            required />

            <label for="prenomFondateur"> Votre prénom :</label>
            <input
            type="text"
            id="prenomFondateur"
            name="prenomFondateur"
            value=""
            class="form-control"
            required />

            <label for="nomFondateur"> Votre nom :</label>
            <input 
            type = "text"
            id="nomFondateur"
            name="nomFondateur"
            value=""
            class="form-control"
            required />

            <label for="username"> Username:</label>
            <input 
            type = "text"
            id="username"
            name="username"
            value=""
            class="form-control"
            required />

            <label for="motdepasse"> Password :</label>
            <input 
            type = "password"
            id="motdepasse"
            name="motdepasse"
            value=""
            class="form-control"
            required />

            <label for="motdepasse2"> Confirmation du password :</label>
            <input 
            type = "password"
            id="motdepasse2"
            name="motdepasse2"
            value=""
            class="form-control"
            required />



            <label for="numTelephone"> Le numéro de téléphone de l'entreprise : </label>
            <input
            type = "text"
            id="numTelephone"
            name="numTelephone"
            value=""
            class="form-control"
            required />

            <label for="adresseEntreprise"> Votre adresse : </label>
            <input
            type="text"
            id="adresseEntreprise"
            name="adresseEntreprise"
            value=""
            class="form-control"
            required />

            <label for="heureOuverture"> Vos heures d'ouverture :</label>
            <input
            type="text"
            id="heures"
            name="heures"
            value="Lundi au vendredi de 9h à 17h"
            class="form-control"
            />

            <label for="LienSiteWeb"> Le lien vers votre site Web :</label>
            <input 
            type="url"
            id="lienSiteWeb"
            name="lienSiteWeb"
            value=""
            class="form-control"
            required />

            <label for="LienTiktok"> Le lien vers votre site Web :</label>
            <input 
            type="url"
            id="lienTiktok"
            name="lienTiktok"
            value=""
            class="form-control"
            />

            <label for="LienFaceBook"> Le lien vers votre site Web :</label>
            <input 
            type="url"
            id="lienFaceBook"
            name="lienFaceBook"
            value=""
            class="form-control"
            />

            <label for="LienInstagram"> Le lien vers votre site Web :</label>
            <input 
            type="url"
            id="lienInstagram"
            name="lienInstagram"
            value=""
            class="form-control"
            />

            <label for="LienYoutube"> Le lien vers votre site Web :</label>
            <input 
            type="url"
            id="lienYoutube"
            name="lienYoutube"
            value=""
            class="form-control"
            />
            </form>

            <h3>Informations sur vos services et produits offerts :</h3>

            <form>

            </form>
            <div>
        </main>
    
</body>
</html>