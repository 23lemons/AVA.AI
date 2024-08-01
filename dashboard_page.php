<?php 
include("./config.php");


// Vérifie si la variable de session 'user_loggedin' est définie avant de l'utiliser
if (isset($_SESSION["user_loggedin"])) {
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
} 
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
            <a href="landing_page.php">
                <img src="images/Capture d’écran 2024-07-16 101521.png" alt="Logo AVA" style="width: 100px; height: auto;">
            </a>
        </div>
        <div class="nav-items">
            <ul>
                <li><a href="landing_page.php?logout=true">Deconnexion</a></li>         
            </ul>
        </div>
    </nav>
</header>
<main>
    <div class="wrapper-section">
        <div class="div-1">
            <h1 class="heading-3">Bienvenue : <?php echo htmlspecialchars($nom_entreprise); ?></h1>
            <form class="div-block-televerser" id="upload-form">
                <input type="file" id="csv-file" accept=".csv" />
                <button type="button" onclick="handleFileUpload()">Téléverser</button>
            </form>
        </div>
        <button id="download-template" type="button" onclick="window.open('https://docs.google.com/spreadsheets/d/1fFiQk8ZrnNeujFsoHJz8AcJVmxRr5GWtL6czDDrGppE/edit?usp=sharing', '_blank');">Voir le modèle Excel</button>
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

<script>
// Chargement initial des prospects
document.addEventListener("DOMContentLoaded", () => {
    fetch('/api/infos_clients') 
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

// Fonction de suppression des prospects
function deleteProspect(element) {
    if (confirm("Voulez-vous vraiment supprimer ce prospect ?")) {
        // Trouver la ligne (table-row) qui contient l'élément cliqué et la supprimer
        const row = element.closest('.table-row');
        row.remove();
    }
}

// Fonction d'édition des prospects
function editProspect(element) {
    // Trouver la ligne correspondante
    const row = element.closest('.table-row');
    
    // Extraire les données de la ligne
    const id = row.querySelector('.table-cell').textContent.trim();
    const name = row.querySelectorAll('.table-cell')[1].textContent.trim();
    const phone = row.querySelectorAll('.table-cell')[2].textContent.trim();
    
    // Remplir les champs du formulaire de modification
    document.getElementById('edit-row-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-phone').value = phone;
    
    // Afficher le modal de modification
    document.getElementById('edit-modal').style.display = 'block';
}

// Fermer le modal de modification
function closeModal() {
    document.getElementById('edit-modal').style.display = 'none';
}

// Gestion de la soumission du formulaire d'édition
document.getElementById('edit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Récupérer les valeurs modifiées
    const id = document.getElementById('edit-row-id').value;
    const name = document.getElementById('edit-name').value;
    const phone = document.getElementById('edit-phone').value;
    
    // Trouver la ligne correspondante
    const row = [...document.querySelectorAll('.table-row')]
        .find(row => row.querySelector('.table-cell').textContent.trim() === id);
    
    if (row) {
        // Mettre à jour les cellules de la ligne
        row.querySelectorAll('.table-cell')[1].textContent = name;
        row.querySelectorAll('.table-cell')[2].textContent = phone;
    }
    
    // Fermer le modal de modification
    closeModal();
});

// Fonction de téléversement des fichiers CSV
function handleFileUpload() {
    const fileInput = document.getElementById('csv-file'); // Recupere l'elt input de type file definit par son id
    const file = fileInput.files[0]; // Recupere premier fichier selectionne par le user

    if(file) { // Si un fichier a ete selectionne
        const reader = new FileReader(); // Cree objet FileReader pour lire le fichier csv choisi
        reader.onload = function(event) { // Fonction est appelee lorsque le fichier est lu
            const csvData = event.target.result; // Recupere contenu du fichier csv
            processCSV(csvData); // Appelle la fonction pocessCSV pour traiter
        };
        reader.readAsText(file); // Lit le fichier et renvoie sous une chaine de char
    } else { 
        alert("Veuillez sélectionner un fichier CSV."); // Affiche message d'alerte
    }
}

// Fonction de traitement du fichier CSV
function processCSV(csvData) {
    const lines = csvData.split('\n'); // Separe les donnees de csv en lignes
    const header = lines[0].split(','); // Separe les donnees de chaque ligne en colonnes
    const container = document.getElementById('prospects-container'); // Recupere l'elt contenant les prospects
    container.innerHTML = ''; // Vider le contenu actuel

    lines.slice(1).forEach(line => { // Itère sur chaque ligne de donnees (sauf la premiere ligne)
        const values = line.split(','); // Separe les colonnes de la ligne en valeurs

        if (values.length === header.length) { // Vérifie si le nombre de valeurs correspond au nombre de colonnes
            const row = document.createElement('div'); // Cree une div pour une nouvelle ligne dans le tableau
            row.className = 'table-row'; // Definit la classe CSS pour la ligne

            row.innerHTML = `
                <div class="div-block-406 _2"></div>
                <div class="table-box">
                    <div class="table-cell">${values[0]}</div>
                </div>
                <div class="table-box">
                    <div class="table-cell">${values[1]}</div>
                </div>
                <div class="table-box">
                    <div class="table-cell">${values[2]}</div>
                </div>
                <div class="table-box action">
                    <div class="table-cell">
                        <span class="status-circle"></span>Inactif
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

            container.appendChild(row); // Ajoute la ligne au tableau
        }
    });
}
</script>

</body>
</html>
