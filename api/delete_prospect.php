<?php
include("./config.php");

header('Content-Type: application/json');

function logError($message) {
    error_log($message, 3, __DIR__.'/error_log.txt'); // Enregistrer les erreurs dans le fichier de log
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    logError("Requête invalide");
    echo json_encode(["error" => "Requête invalide"]);
    exit();
}

// Extraire l'ID du prospect à partir de l'URL
parse_str(file_get_contents("php://input"), $delete_vars);
$id_prospect = $delete_vars['id'] ?? null;

if (!$id_prospect) {
    logError("ID du prospect manquant");
    echo json_encode(["error" => "ID du prospect manquant"]);
    exit();
}

try {
    $stmt = $conn->prepare("DELETE FROM Prospects WHERE id_prospect = :id_prospect");
    $stmt->bindParam(":id_prospect", $id_prospect, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        logError("Suppression réussie pour l'ID: $id_prospect");
        echo json_encode(["success" => true]);
    } else {
        logError("Aucun prospect trouvé avec l'ID: $id_prospect");
        echo json_encode(["error" => "Aucun prospect trouvé avec cet ID"]);
    }
} catch (PDOException $e) {
    logError("Erreur PDO: " . $e->getMessage());
    echo json_encode(["error" => $e->getMessage()]);
}
?>
