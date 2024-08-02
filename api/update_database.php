<?php

include("./config.php");

//use Twilio\TwiML\MessagingResponse;

//$account_sid = 'YOUR_TWILIO_ACCOUNT_SID'; // Your Twilio Account SID
//$auth_token = 'YOUR_TWILIO_AUTH_TOKEN';   // Your Twilio Auth Token

// Initialize Twilio client
//$client = new Twilio\Rest\Client($account_sid, $auth_token);

// Get the incoming message details

$from = $_POST['From']; // Sender's phone number
$body = $_POST['Body']; // Message content



$phoneNumberWithoutCountryCode = ltrim($from, '+1');

// Process the message and update your database
// Example: Update database based on the reply


try {
    /// Determine interest based on the reply
    if (strtoupper(trim($body)) == 'OUI') {
        $statut = "Intéressé";
    } elseif (strtoupper(trim($body)) == 'NON') {
        $statut = "Pas intéressé";
    } else {
        $statut = "En attente";
    }

    // Update the database with the determined status
    $stmt = $conn->prepare("UPDATE Prospects SET statut_prospect = :statut WHERE num_tel_prospect = :phone_number");
    $stmt->bindParam(':statut', $statut); // Bind the status parameter
    $stmt->bindParam(':phone_number', $phoneNumberWithoutCountryCode); // Bind the phone number parameter
    $stmt->execute();

    // Respond to the message
    // $response = new MessagingResponse();

    // if ($statut == "Intéressé") {

    //     $response->message('Merci pour votre réponse, nous vous contacterons dans un faible délai.');
    // } else if ($statut == "Pas intéressé") {

    //     $response->message('Pas de problème! Merci pour votre réponse.');
    // } else {

    //     $response->message('Merci pour votre réponse.');
    // }
    

    // // Output the response
    // header('Content-Type: text/xml');
    // echo $response;
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    // Handle other errors
    http_response_code(500);
    echo json_encode(['error' => 'Unexpected error: ' . $e->getMessage()]);
}