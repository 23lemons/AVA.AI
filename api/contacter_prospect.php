<?php
include("./config.php");
require_once 'openai_api.php';

 if (!isset($_SESSION["user_loggedin"])) {
     echo json_encode(["error" => "Utilisateur non connecté"]);
     exit();
 }

$nom_utilisateur = $_SESSION["user_loggedin"];

try {

    $stmt = $conn->prepare("SELECT prenom_prospect, num_tel_prospect, statut_prospect
                            FROM Prospects WHERE id_prospect = :id");
    $stmt->bindParam(":id", $id_prospect, PDO::PARAM_INT);
    $stmt->execute();
    $infos_prospect = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are results
    if (count($infos_prospect) > 0) {
        // Process the first item in the array
        $prospect_info = $infos_prospect[0];

    } else echo "Aucun prospect";
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

if ($prospect_info["statut_prospect"] == "En attente") {

    $to = "+1" . $prospect_info["num_tel_prospect"]; //numero a contacter
$prenom_prospect = $prospect_info["prenom_prospect"];

try {

    $requete = $conn->prepare("SELECT id_entreprise FROM Entreprise WHERE username_entreprise = :nom_utilisateur");
    $requete->bindParam(":nom_utilisateur", $nom_utilisateur);
    $requete->execute();

    $entreprise = $requete->fetch(PDO::FETCH_ASSOC);
    $id_entreprise = $entreprise['id_entreprise'] ?? null;

    if ($id_entreprise) {
        $stmt = $conn->prepare("SELECT nom_entreprise, description_entreprise, description_service
                                FROM Infos_Entreprise WHERE id_entreprise = :id");
        $stmt->bindParam(":id", $id_entreprise, PDO::PARAM_INT);
        $stmt->execute();
        $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $infos_entreprise = $entreprises[0];
    }

    echo json_encode($infos_entreprise);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}


$account_sid = ''; // change to my twilio sid
$auth_token = ''; // my twilio auth token
$from = ''; //numero twilio

$company_name = $infos_entreprise["nom_entreprise"]; //nom de l'entreprise
$service = $infos_entreprise["description_service"];
$company_desc = $infos_entreprise["description_entreprise"];

$prompt =  "Créez un message sous le nom d' Ava, pour un potentiel client nommé $prenom_prospect afin de présenter notre entreprise $company_name. La description de l'entreprise est : $company_desc. Nous offrons le service suivant : $service. Le message doit être professionnel et demander auclient s'il est intéressé. Le message doit comporter moins de 250 caractères (tokens)";
$message_body = genererMessage($prompt);

if(str_contains($message_body, "Erreur de l'API OpenAI:") || str_contains($message_body, "Pas de réponse générée")){

    return json_encode("erreur de l'api");
    
} else {

$url = 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/Messages.json';

$data = [
    'To' => $to,
    'From' => $from,
    'Body' => $message_body
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
}

?>