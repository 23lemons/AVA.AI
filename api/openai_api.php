<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function genererMessage($prompt) {
    $apiKey = '';
    $url = '';
    
    $data = [
        "model" => "gpt-4",
        "messages" => [
            ["role" => "system", "content" => "You are a helpful assistant."],
            ["role" => "user", "content" => $prompt]
        ],
        "max_tokens" => 150
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return json_encode(["error" => "Erreur lors de la requête CURL: " . $error_msg]);
    }

    curl_close($ch);

    echo "<pre>";
    print_r($response);
    echo "</pre>";
    
    $responseData = json_decode($response, true);

    // Debugging: Print the full response from OpenAI
    if ($responseData === null) {
        return json_encode(["error" => "Impossible de décoder la réponse JSON"]);
    }

    // Check if OpenAI returned an error
    if (isset($responseData['error'])) {
        return json_encode(["error" => "Erreur de l'API OpenAI: " . $responseData['error']['message']]);
    }

    // Check if the message was generated
    if (!isset($responseData['choices'][0]['message']['content'])) {
        return json_encode(["message" => "Pas de réponse générée"]);
    }

    // Return the generated text
    return json_encode(["message" => $responseData['choices'][0]['message']['content']]);
}
?>
