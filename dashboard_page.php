<?php
include ("./config.php");

$requete = $conn->prepare("SELECT id_entreprise FROM Entreprise WHERE username_entreprise = :nom_utilisateur");
$requete->bindParam(":nom_utilisateur", $_SESSION["user_loggedin"]);
$requete->execute();

// Fetch the result
$entreprise = $requete->fetch(PDO::FETCH_ASSOC);

$id_entreprise = $entreprise['id_entreprise'];
$prospects = [];

echo $_SESSION["user_loggedin"];

try {
    $sql = "SELECT prenom_prospect, nom_prospect, num_tel_prospect, courriel_prospect, statut_prospect 
            FROM Prospects WHERE id_entreprise = :id";

    // Préparer la déclaration
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":id", $id_entreprise, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        $prospects = $result;
        }
     else {
        echo "0 clients";
    }
} catch (PDOException $e){

	echo "Erreur:" . $e->getMessage();
    	$conn = null;}
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
<header>
        <nav>
            <div class="logo">
                <img src="images\Capture d’écran 2024-07-16 101521.png" alt="Logo AVA" style="width: 100px; height: auto;">
            </div>
            <div class="nav-items">
                <ul>
                    <li><a href="landing_page.php">Acceuil</a></li>         
                </ul>
            </div>
        </nav>
    </header>
  <div class="wrapper-section">
    <div class="div-1">
      <h1 class="heading-3">Tableau de bord - AVA</h1>
      <form class="div-block-televerser" id="upload-form">
        <input type="file" id="csv-file" accept=".csv" />
        <button type="button" onclick="handleFileUpload()"> Téleverser </button>
  </form>
  <script src="script_csvUpload.js"></script>
    </div>
    <div class="table-wrapper">
      <div class="table-row head">
        <div class="div-block-406"></div>
        <div class="table-box">
          <div class="table-heading">Nom</div>
        </div>
        <div class="table-box">
          <div class="table-heading">Num. Téléphone</div>
        </div>
        <div class="table-box action">
          <div class="table-heading">Statut</div>
        </div>
        <div class="table-box action">
         <div class="table-heading">Action</div>
        </div>
      </div>
      <div class="table-container">
        <div class="table-data-wrapper">
          <div class="scroll-container">
            <div class="scroll-table-content">
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
                  <div>Page <span class="text-span">1</span> de 1</div>
                </div>
                <div class="div-block-410"><img src="images/fleche_boutton.svg" alt="" class="table-arrows"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <footer>
        <p>&copy; 2024 AVA. Tous droits réservés.</p>
    </footer>
  </body>
</html>
