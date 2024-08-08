<?php
include("./config.php");

$nom_utilisateur = $_SESSION["user_loggedin"] ?? null;

if (!$nom_utilisateur) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

try {

    $requete = $conn->prepare("SELECT id_entreprise FROM Entreprise WHERE username_entreprise = :nom_utilisateur");
    $requete->bindParam(":nom_utilisateur", $nom_utilisateur);
    $requete->execute();

    $entreprise = $requete->fetch(PDO::FETCH_ASSOC);
    $id_entreprise = $entreprise['id_entreprise'] ?? null;

    if (!$id_entreprise) {
        echo json_encode(["error" => "id entreprise pas trouve"]);
        exit;
    }

    $body = file_get_contents("php://input");

    $jsonData = json_decode($body);

    if (!$jsonData) {
        echo json_encode(["error" => "json invalide"]);
        exit;
    }

    
    $insert = $conn->prepare("INSERT INTO `Prospects` (`prenom_prospect`, `nom_prospect`, `num_tel_prospect`, `courriel_prospect`, `id_entreprise`)
                              VALUES (:prenom_prospect, :nom_prospect, :num_tel_prospect, :courriel_prospect, :id_entreprise)");

    $insert->bindParam(":prenom_prospect", $jsonData->prenom_prospect);
    $insert->bindParam(":nom_prospect", $jsonData->nom_prospect);
    $insert->bindParam(":num_tel_prospect", $jsonData->num_tel_prospect);
    $insert->bindParam(":courriel_prospect", $jsonData->courriel_prospect);
    $insert->bindParam(":id_entreprise", $id_entreprise);

    $insert->execute();

    echo json_encode(["success" => "prospect inserted successfully"]);

} catch (PDOException $e) {
    
    echo json_encode(["error" => $e->getMessage()]);
}
?>