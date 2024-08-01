<?php 
include("./config.php");

$username_entreprise = $_SESSION["user_loggedin"];

$requete = $conn->prepare("SELECT id_entreprise FROM Entreprise WHERE username_entreprise = :user");
$requete->bindParam(":user", $username_entreprise);
$requete->execute();

$id = $requete->fetch(PDO::FETCH_ASSOC);
$id = $id["id_entreprise"];


$requete2 = $conn->prepare("SELECT nom_entreprise FROM Infos_Entreprise WHERE id_entreprise = :id");
$requete2->bindParam(":id", $id);
$requete2->execute();

$nom_entreprise = $requete2->fetch(PDO::FETCH_ASSOC); // Récupère le résultat sous forme de tableau associatif
$nom_entreprise = $nom_entreprise['nom_entreprise']; // Accède à la valeur de 'nom_entreprise'
?>


<!DOCTYPE html>
<html lang="fr">
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
            <img src="images/Capture d’écran 2024-07-16 101521.png" alt="Logo AVA" style="width: 100px; height: auto;">
        </div>
        <div class="nav-items">
            <ul>
                <li><a href="landing_page.php">Accueil</a></li>         
            </ul>
        </div>
    </nav>
</header>
<main>
        <div class="div-1">
            <h1 class="heading-3">Tableau de bord</h1>
            <form class="div-block-televerser" id="upload-form">
                <input type="file" id="csv-file" accept=".csv" />
                <button class="btn_televerser" type="button" onclick="handleFileUpload()">Téléverser</button>
            </form>
        </div>
        <div class="wrapper-section">
    <button id="download-template" type="button">Télécharger le modèle Excel</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
document.getElementById('download-template').addEventListener('click', function() {
    // Définir les données du modèle Excel
    const worksheetData = [
        ['ID', 'Nom', 'Num. Téléphone']
    ];
    
    // Créer un nouveau classeur
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
    
    // Ajouter la feuille de calcul au classeur
    XLSX.utils.book_append_sheet(workbook, worksheet, "Modèle");
    
    // Générer et télécharger le fichier Excel
    XLSX.writeFile(workbook, 'modele_prospects.xlsx');
});
</script>
        <div class="table-wrapper">
            <div class="table-row head">
                <div class="div-block-406"></div>
                <div class="table-box">
                    <div class="table-heading">ID</div>
                </div>
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
            <div id="prospects-container" class="table-container"></div>
        </div>
    </div>
</main>
<!-- Modal de modification -->
<div id="edit-modal" style="display: none;">
    <div class="modal-content">
        <span id="close-modal" onclick="closeModal()">×</span>
        <h2>Modifier Prospect</h2>
        <form id="edit-form">
            <input type="hidden" id="edit-row-id">
            <label for="edit-name">Nom:</label>
            <input type="text" id="edit-name" required>
            <label for="edit-phone">Num. Téléphone:</label>
            <input type="text" id="edit-phone" required>
            <button type="submit">Sauvegarder</button>
        </form>
    </div>
</div>
<footer>
    <p>&copy; 2024 AVA. Tous droits réservés.</p>
</footer>
<script src = 'script_csvUpload.js'></script>
<script>
// Chargement initial des prospects
document.addEventListener("DOMContentLoaded", () => {
    fetch('/api/infos_clients/1')  // Assurez-vous de remplacer '1' par l'ID approprié ou configurez pour récupérer tous les prospects
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('prospects-container');
            if (data.error) {
                container.innerHTML = `<p>Erreur: ${data.error}</p>`;
            } else if (data.length === 0) {
                container.innerHTML = `<p>0 clients</p>`;
            } else {
                data.forEach(prospect => {
                    const row = document.createElement('div');
                    row.className = 'table-row';
                    let statusClass = '';

                    switch (prospect.statut_prospect.toLowerCase()) {
                        case 'intéressé':
                            statusClass = 'status-interesse';
                            break;
                        case 'en attente':
                            statusClass = 'status-attente';
                            break;
                        case 'pas intéressé':
                            statusClass = 'status-pas-interesse';
                            break;
                    }

                    row.innerHTML = `
                        <div class="div-block-406 _2"></div>
                        <div class="table-box">
                            <div class="table-cell">${prospect.id_prospect}</div>
                        </div>
                        <div class="table-box">
                            <div class="table-cell">${prospect.prenom_prospect} ${prospect.nom_prospect}</div>
                        </div>
                        <div class="table-box">
                            <div class="table-cell">${prospect.num_tel_prospect}</div>
                        </div>
                        <div class="table-box action">
                            <div class="table-cell">
                                <span class="status-circle ${statusClass}"></span>
                                ${prospect.statut_prospect}
                            </div>
                        </div>
                        <div class="table-box action">
                            <div class="table-cell">
                                 <a onclick="editProspect(this)" href="#" class="link-block-12 w-inline-block">
                      <img src="images/crayon_modifier.svg" alt="" class="table-action-icon">
                    </a>
                    <a onclick="deleteProspect(this)" href="#" class="link-block-10 w-inline-block">
                      <img src="images/fermer_X_rouge.svg" alt="" class="table-action-icon-2 x">
                    </a>
                            </div>
                        </div>
                    `;
                    container.appendChild(row);
                });
            }
        })
        .catch(error => {
            document.getElementById('prospects-container').innerHTML = `<p>Erreur: ${error.message}</p>`;
        });
});

</script>

</body>
</html>

