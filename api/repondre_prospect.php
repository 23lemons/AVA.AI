<?php

include ('./config.php');
require_once ('openai_api.php');



$from = $_POST['From']; // Sender's phone number
$body = $_POST['Body']; // Message content

$phoneNumberWithoutCountryCode = ltrim($from, '+1');

$requete = $conn->prepare("SELECT id_entreprise FROM Prospects WHERE num_tel_prospect = :numTel");
$requete->bindParam(":numTel", $phoneNumberWithoutCountryCode);
$requete->execute();

$id_entreprise = $requete->fetchAll(PDO::FETCH_ASSOC);

// Check if there are results
if (count($id_entreprise) > 0) {
    // Process the first item in the array
    $company_id = $id_entreprise[0];
}

$requete2 = $conn->prepare("SELECT nom_entreprise, description_entreprise, description_service, prix_service
FROM Infos_Entreprise WHERE id_entreprise = :id");
$requete2->bindParam(":id", $id_entreprise, PDO::PARAM_INT);
$requete2->execute();
$entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

$company_name = $entreprises["nom_entreprise"]; //nom de l'entreprise
$price_to_sell = $entreprises["prix_service"];
$service = $entreprises["description_service"];
$company_desc = $entreprises["description_entreprise"];

$prompt = "voici un message d'un prospect, interprete s'il est interesse ou non. S'il est interesse repond SEULEMENT avec 'OUI',
 s'il n'est pas interesse repond SEULEMENT avec 'NON',
  s'il nest pas certain repond a son messge avec les donnes de la compagnie:
   nom de la compagnie : $company_name, prix du service ou produit : $price_to_sell, la desciption du service ou produit vendu : $service,
la description de l'entreprise qui vent le produit : $company_desc
   Voici le message du prospect: $body ";

$reponseGPT = genererMessage($prompt);

$reponseGPT = json_decode($reponseGPT);

if($reponseGPT == "OUI"){

    $statut = "Intéressé";

} else if ($reponseGPT == "NON"){

    $statut = "Pas intéressé";

} else {

    $statut = "En attente";
    repondre_prospect($reponseGPT);
}



// Process the message and update your database
// Example: Update database based on the reply


try {

    // Update the database with the determined status
    $stmt = $conn->prepare("UPDATE Prospects SET statut_prospect = :statut WHERE num_tel_prospect = :phone_number");
    $stmt->bindParam(':statut', $statut); // Bind the status parameter
    $stmt->bindParam(':phone_number', $phoneNumberWithoutCountryCode); // Bind the phone number parameter
    $stmt->execute();

} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Handle other errors
    http_response_code(500);
    echo json_encode(['error' => 'Unexpected error: ' . $e->getMessage()]);a
}

function repondre_prospect($message_ou_prompt){







}


?>