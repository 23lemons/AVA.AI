<?php
/*if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $fileTmpPath = $_FILES['csvFile']['tmp_name'];
    $fileName = $_FILES['csvFile']['name'];
    $fileSize = $_FILES['csvFile']['size'];
    $fileType = $_FILES['csvFile']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Vérifier l'extension du fichier
    if ($fileExtension === 'csv') {
        // Lire le fichier CSV
        if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
            $prospects = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Supposons que les colonnes soient Nom, Num. Téléphone
                $nom = $data[0];
                $telephone = $data[1];

                // Ajouter à la base de données
                // Remplacez ceci par votre propre logique d'insertion
                $conn = new mysqli('localhost', 'username', 'password', 'database');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $stmt = $conn->prepare("INSERT INTO prospects (nom, num_tel, statut) VALUES (?, ?, 'Inactif')");
                $stmt->bind_param("ss", $nom, $telephone);
                $stmt->execute();
                $stmt->close();
                $conn->close();
            }
            fclose($handle);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Impossible de lire le fichier CSV']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez télécharger un fichier CSV']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier téléversé']);
}*/
?>
