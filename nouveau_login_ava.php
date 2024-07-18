<?php
require_once './config.php'; // Inclure la configuration de la base de données

$erreur = "";
$nbErreur = 0;
$nom_utilisateur = "";

if (isset($_SESSION['user_loggedin'])) {
    header('Location: dashboard_page.php'); // Remplacer par le chemin de la page principale
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['username'];
    $mot_de_passe = $_POST['password'];

    if (!empty($nom_utilisateur) && !empty($mot_de_passe)) {
        // Préparer la requête pour rechercher l'utilisateur par nom d'utilisateur
        $requete = $conn->prepare("SELECT * FROM Entreprise WHERE username_entreprise = :nom_utilisateur AND mdp_entreprise =  :mot_de_passe");
        $requete->bindParam(":nom_utilisateur", $nom_utilisateur);
        $requete->bindParam(":mot_de_passe", $mot_de_passe);
        $requete->execute();

        // Récupérer les résultats de la requête
       // $utilisateur = $requete->fetch();

        // Vérifier si l'utilisateur existe et si le mot de passe correspond
        // if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mdp_entreprise'])) {
        //     // L'utilisateur est authentifié
        //     $_SESSION['username'] = $utilisateur['username_entreprise'];
        //     $_SESSION['user_loggedin'] = true;
        //     header('Location: dashboard_page.php');
        //     exit();
        // } else {
        //     $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
        //     $nbErreur++;
        // }

        if($requete->fetch()){
            $_SESSION["user_loggedin"] = $nom_utilisateur;
            header("Location: dashboard_page.php");
            exit();
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
            <div class="logo">
                <img src="images/AVALOGOBLANC.png" alt="Votre Logo" />
            </div>
            <h1>Bienvenue</h1>
            <p>Veuillez vous connecter pour accéder à votre compte</p>
        </div>
        <div class="right-section">
            <h2>Connexion</h2>
            <?php
            if (!empty($erreur)) {
                echo '<p style="color: red;">' . htmlspecialchars($erreur) . '</p>';
            }
            ?>
            <form method="post" action="nouveau_login_ava.php">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button class="btn" type="submit">Se connecter</button>
                <button class="btn" type="button" onclick="register()">S'inscrire</button>
            </form>
        </div>
    </div>

    <script>
        function register() {
            // Redirection vers la page d'inscription
            window.location.href = 'informationPrincipale.php';
        }
    </script>
</body>
</html>
