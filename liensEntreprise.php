<?php

require_once ('./config.php');

if(isset($_POST["website_link"])) {
    // Traitement des données du formulaire
    $website_link = $_POST["website_link"];
    $instagram_link = $_POST["instagram_link"];
    $tiktok_link = $_POST["tiktok_link"];
    $facebook_link = $_POST["facebook_link"];
    $youtube_link = $_POST["youtube_link"];

    $requete = $conn->prepare("INSERT INTO Liens_Entreprise (lien_site_web, lien_page_instagram, lien_page_tiktok, lien_chaine_youtube, lien_page_facebook) 
    VALUES (:lienWEB, :lienIG, :lienTiktok, :lienYT, :lienFB)");

    $requete->bindValue(":lienWEB", $website_link);
    $requete->bindValue(":lienIG", $instagram_link);
    $requete->bindValue(":lienTiktok", $tiktok_link);
    $requete->bindValue(":lienYT", $youtube_link);
    $requete->bindValue(":lienFB", $facebook_link);

    $requete->execute();

    // Redirection vers le tableau de bord ou une autre page
    header("Location: dashboard.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte Entreprise - Page 3</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <div class="left-section">
            <div class="logo">
                <img src="images/AVALOGOBLANC.png" alt="Votre Logo" />
            </div>
            <h1 class="sauce">Plus qu'une dernière étape...</h1>
        </div>
        <div class="right-section">
            <h2>Liens de l'Entreprise</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="website_link">Lien du Site Web</label>
                    <input type="text" id="website_link" name="website_link">
                </div>
                <div class="form-group">
                    <label for="instagram_link">Lien de la Page Instagram</label>
                    <input type="text" id="instagram_link" name="instagram_link">
                </div>
                <div class="form-group">
                    <label for="tiktok_link">Lien de la Page TikTok</label>
                    <input type="text" id="tiktok_link" name="tiktok_link">
                </div>
                <div class="form-group">
                    <label for="facebook_link">Lien de la Page TikTok</label>
                    <input type="text" id="facebook_link" name="facebook_link">
                </div>
                <div class="form-group">
                    <label for="youtube_link">Lien de la Page TikTok</label>
                    <input type="text" id="youtube_link" name="youtube_link">
                </div>

                <button type="submit" class="btn">Soumettre</button>
            </form>
        </div>
    </div>
</body>
</html>
