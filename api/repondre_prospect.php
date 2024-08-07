<?php

include ('./config.php');
require_once ('openai_api.php');



$from = $_POST['From']; // Sender's phone number
$body = $_POST['Body']; // Message content

$phoneNumberWithoutCountryCode = ltrim($from, '+1');



$requete = $conn->prepare("SELECT id_entreprise, id_prospect FROM Prospects WHERE num_tel_prospect = :numTel");
$requete->bindParam(":numTel", $phoneNumberWithoutCountryCode);
$requete->execute();

$id_entreprise = $requete->fetchAll(PDO::FETCH_ASSOC);

// Check if there are results
if (count($id_entreprise) > 0) {
    // Process the first item in the array
    $company_id = $id_entreprise[0];
    $id = $company_id["id_entreprise"];
    $id_prospect = $company_id["id_prospect"];
}

$requete2 = $conn->prepare("SELECT nom_entreprise, description_entreprise, description_service, prix_service
FROM Infos_Entreprise WHERE id_entreprise = :id");
$requete2->bindParam(":id", $id, PDO::PARAM_INT);
$requete2->execute();
$entreprises = $requete2->fetchAll(PDO::FETCH_ASSOC);

if (count($entreprises) > 0) {
    // Process the first item in the array
    $entreprise = $entreprises[0];

}

$company_name = $entreprise["nom_entreprise"]; //nom de l'entreprise
$price_to_sell = $entreprise["prix_service"];
$service = $entreprise["description_service"];
$company_desc = $entreprise["description_entreprise"];

$prompt = "voici un message d'un prospect, interprete s'il est interesse ou non. S'il est interesse repond SEULEMENT avec 'OUI',
 s'il n'est pas interesse repond SEULEMENT avec 'NON',
  s'il nest pas certain repond a son message en utilisant les donnes de la compagnie et demande lui s'il est intéressé:
   nom de la compagnie : $company_name, prix du service ou produit : $price_to_sell, la desciption du service ou produit vendu : $service,
la description de l'entreprise qui vent le produit : $company_desc
   Voici le message du prospect: $body ";

$reponseGPT = genererMessage($prompt);

$reponseGPT = json_decode($reponseGPT);

if($reponseGPT == "OUI"){

    $statut = "Intéressé";

    repondre_prospect("Merci, un representant de la compagnie vous contactera dans les plus brefs délais.", $phoneNumberWithoutCountryCode);

} else if ($reponseGPT == "NON"){

    $statut = "Pas intéressé";

    repondre_prospect("Merci pour votre réponse.", $phoneNumberWithoutCountryCode);

} else {

    $statut = "En attente";
    repondre_prospect($reponseGPT, $phoneNumberWithoutCountryCode);
}

    // Update the database with the determined status
    $stmt = $conn->prepare("UPDATE Prospects SET statut_prospect = :statut WHERE num_tel_prospect = :phone_number");
    $stmt->bindParam(':statut', $statut); // Bind the status parameter
    $stmt->bindParam(':phone_number', $phoneNumberWithoutCountryCode); // Bind the phone number parameter
    $stmt->execute();
    
    // // Output the response
    // header('Content-Type: text/xml');
    // echo $response;



function repondre_prospect($message_ou_prompt, $phoneNumberWithoutCountryCode){

    $to = "+1" . $phoneNumberWithoutCountryCode; //numero a contacter



    $account_sid = ''; // change to my twilio sid
    $auth_token = ''; // my twilio auth token
    $from = ''; //numero twilio
    
    $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/Messages.json';
    
    $data = [
        'To' => $to,
        'From' => $from,
        'Body' => $message_ou_prompt
    ];
    
    $post = http_build_query($data);
    $x = curl_init($url);
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_USERPWD, "$account_sid:$auth_token");
    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($x, CURLOPT_POSTFIELDS, $post);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($x);
    curl_close($x);
    
    echo $response;
    }
?>