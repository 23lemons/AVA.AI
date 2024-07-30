<?php
include("./config.php");

header('Content-Type: application/json');

if (!isset($_SESSION["user_loggedin"])) {
    echo json_encode(["error" => "Utilisateur non connecté"]);
    exit();
}

$nom_utilisateur = $_SESSION["user_loggedin"];
$prospects = [];

try {
    $requete = $conn->prepare("SELECT id_entreprise FROM Entreprise WHERE username_entreprise = :nom_utilisateur");
    $requete->bindParam(":nom_utilisateur", $nom_utilisateur);
    $requete->execute();

    $entreprise = $requete->fetch(PDO::FETCH_ASSOC);
    $id_entreprise = $entreprise['id_entreprise'] ?? null;

    if ($id_entreprise) {
        $stmt = $conn->prepare("SELECT id_prospect, prenom_prospect, nom_prospect, num_tel_prospect, courriel_prospect, statut_prospect 
                                FROM Prospects WHERE id_entreprise = :id");
        $stmt->bindParam(":id", $id_entreprise, PDO::PARAM_INT);
        $stmt->execute();
        $prospects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($prospects);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
