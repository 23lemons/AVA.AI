<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function genererMessage($prompt) {
    $apiKey = '';
    $url = 'https://api.openai.com/v1/chat/completions';
    
    $data = [
        "model" => "gpt-4",
        "messages" => [
            ["role" => "system", "content" => "You are a helpful assistant."],
            ["role" => "user", "content" => $prompt]
        ],
        "max_tokens" => 250
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
        return json_encode(["message" => "Pas de réponse générée"], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    $messageContent = str_replace("\n"," ", $responseData['choices'][0]['message']['content']);

    //$array = ["message" => $messageContent]; // j'ai enlever a cause du "message" : Objet : type shiiiiii

    // Return the generated text with the correct formatting
    return json_encode($messageContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
?>
