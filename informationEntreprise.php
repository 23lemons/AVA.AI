<?php

require_once ('./config.php');


if(isset($_POST["company_name"]) && isset($_POST["owner_firstname"]) && isset($_POST["phone_number"]) && isset($_POST["address"]) && isset($_POST["company_description"])) {
    // Votre code ici pour traiter les données du formulaire
    $company_name = $_POST["company_name"];
    $owner_firstname = $_POST["owner_firstname"];
    $phone_number = $_POST["phone_number"];
    $address = $_POST["address"];
    $company_description = $_POST["company_description"];

    // Vous pouvez ajouter ici votre code pour enregistrer les données dans la base de données ou autre traitement
    $requete = $conn->prepare("INSERT INTO Entreprise (nom_entreprise) VALUES (:company_name)");

    $requete->bindValue(":company_name", $company_name);
    $requete->execute();
    
    $requete = $conn->prepare("INSERT INTO Infos_Entreprise (prenom_fondateur, nom_fondateur, num_tel_entreprise, adresse_entreprise, description_entreprise) 
    VALUES (:owner_firstname, :phone_number, :adress, :company_description)");

    $requete->bindValue(":owner_firstname", $owner_firstname);
    $requete->bindValue(":phone_number", $phone_number);
    $requete->bindValue(":adress", $address);
    $requete->bindValue(":company_description", $company_description);
    $requete->execute();

    // Redirection vers la page suivante
    header("Location: liensEntreprise.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte Entreprise - Page 2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <div class="left-section">
            <div class="logo">
                <img src="images/AVALOGOBLANC.png" alt="Votre Logo" />
            </div>
            <h1 class="sauce">Chez AVA, votre paix d'esprit est notre mission</h1>
        </div>
        <div class="right-section">
            <h2>Informations de l'Entreprise</h2>
            <form action="liensEntreprise.php" method="POST">
                <div class="form-group">
                    <label for="company_name">Nom de l'Entreprise</label>
                    <input type="text" id="company_name" name="company_name" required>
                </div>
                <div class="form-group">
                    <label for="owner_firstname">Prénom du Détenteur</label>
                    <input type="text" id="owner_firstname" name="owner_firstname" required>
                </div>
                <div class="form-group">
                    <label for="phone_number">Numéro de Téléphone</label>
                    <input type="text" id="phone_number" name="phone_number" required>
                </div>
                <div class="form-group">
                    <label for="address">Adresse de l'Entreprise</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="company_description">Description de l'Entreprise</label>
                    <textarea id="company_description" name="company_description" rows="5" placeholder="Veuillez brièvement décrire ce que l'entreprise vend, le temps de livraison de votre produit et le prix du service vendu en 150 mots."></textarea>
                </div>
                <button type="submit" class="btn">Suivant</button>
            </form>
        </div>
    </div>
</body>
</html>
