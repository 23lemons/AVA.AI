<?php

require_once ('./config.php');

$id_entreprise = 1;

$sql = "SELECT prenom_prospect, nom_prospect, num_tel_prospect, courriel_prospect, statut_prospect 
        FROM Prospects 
        INNER JOIN Entreprises ON Prospects.id_entreprise = Entreprises.id_entreprise
        WHERE Entreprises.id_entreprise = ? "; // Remplacez ? par l'ID de l'entreprise appropriée

// Préparer la déclaration
$stmt = $conn->prepare($sql);
$stmt->bindParam("i", $id_entreprise); // Remplacez $id_entreprise par l'ID de l'entreprise appropriée
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $prospects = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $erreur = "0 clients";
    echo $erreur;
    $prospects = [];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="wrapper-section">
    <div class="div-1">
      <h1 class="heading-3">Tableau de bord - AVA</h1>
      <div class="div-block-414">
        <a href="https://webflow.com/website/table-template-81d32b" target="_blank" class="button w-button">Téléverser (.csv)</a>
      </div>
    </div>
    <div class="table-wrapper">
      <div class="table-row head">
        <div class="div-block-406"></div>
        <div class="table-box">
          <div class="table-heading">Name</div>
        </div>
        <div class="table-box">
          <div class="table-heading">Phone</div>
        </div>
        <div class="table-box action">
          <div class="table-heading">Status</div>
        </div>
        <div class="table-box action">
          <div class="table-heading">Action</div>
        </div>
      </div>
      <div class="table-container">
        <div class="table-data-wrapper">
          <div class="scroll-container">
            <div class="scroll-table-content">
              <div class="table-row head hide"><!-- div n"est pas affiche -->
                <div class="div-block-406"></div>
                <div class="table-box">
                  <div class="table-heading">Name</div>
                </div>
                <div class="table-box">
                  <div class="table-heading">Email</div>
                </div>
                <div class="table-box">
                  <div class="table-heading">Phone</div>
                </div>
                <div class="table-box">
                  <div class="table-heading">Country</div>
                </div>
                <div class="table-box _11">
                  <div class="table-heading">Last Update</div>
                </div>
                <div class="table-box action">
                  <div class="table-heading">Status</div>
                </div>
                <div class="table-box action">
                  <div class="table-heading">Action</div>
                </div>
              </div>

              <?php
              $i = 1;
              foreach ($prospects as $prospect) {
                echo '
                <div class="table-row">
                  <div class="div-block-406 _2">
                    <div class="table-row-nr">'.$i.'</div>
                  </div>
                  <div class="table-box _2">
                    <div class="table-data name">'.$prospect["prenom_prospect"] . ' ' . $prospect["nom_prospect"].'</div>
                  </div>
                  <div class="table-box _2">
                    <div class="table-data">'.$prospect["num_tel_prospect"].'</div>
                  </div>
                  <div class="table-box _2 action">
                    <a href="#" data-ix="toggle" data-w-id="ae27a065-dfeb-a9cb-56ca-8b63a99081b9" class="togglebutton inactive w-inline-block"></a>
                  </div>
                  <div class="table-box _2 action">
                    <a data-w-id="ae27a065-dfeb-a9cb-56ca-8b63a99081bc" href="#" class="link-block-12 w-inline-block">
                      <img src="images/crayon_modifier.svg" alt="" class="table-action-icon">
                    </a>
                    <a data-w-id="ae27a065-dfeb-a9cb-56ca-8b63a99081be" href="#" class="link-block-10 w-inline-block">
                      <img src="images/fermer_X_rouge.svg" alt="" class="table-action-icon-2 x">
                    </a>
                  </div>
                </div>';
                $i++;
              }
              ?>

            <div class="table-details-box">
              <div class="div-block-412">
                <div class="div-block-410"><img src="images/fleche_boutton.svg" alt="" class="table-arrows _2"></div>
                <div class="div-block-411">
                  <div>Page <span class="text-span">1</span> of 1</div>
                </div>
                <div class="div-block-410"><img src="images/fleche_boutton.svg" alt="" class="table-arrows"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
