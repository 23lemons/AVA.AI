<?php
include("./config.php");

header('Content-Type: application/json');

function logError($message) {
    error_log($message, 3, __DIR__.'/error_log.txt'); // Enregistrer les erreurs dans le fichier de log
}

// Extraire l'ID du prospect à partir de l'URL
$id_prospect = $_GET["id"] ?? null;

if (!$id_prospect) {
    logError("ID du prospect manquant");
    exit();
}

try {
    $stmt = $conn->prepare("DELETE FROM Prospects WHERE id_prospect = :id_prospect");
    $stmt->bindParam(":id_prospect", $id_prospect);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        logError("Suppression réussie pour l'ID: $id_prospect");
        return echo json_encode(["reussie"]);
    } else {
        logError("Aucun prospect trouvé avec l'ID: $id_prospect");
    }
} catch (PDOException $e) {
    logError("Erreur PDO: " . $e->getMessage());
}
?>
