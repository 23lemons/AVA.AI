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
    <div class="wrapper-section">
        <div class="div-1">
            <h1 class="heading-3"><?php echo $nom_entreprise; ?></h1>
            <form class="div-block-televerser" id="upload-form">
                <input type="file" id="csv-file" accept=".csv" />
                <button type="button" onclick="handleFileUpload()">Téléverser</button>
            </form>
        </div>
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
<footer>
    <p>&copy; 2024 AVA. Tous droits réservés.</p>
</footer>

<script>
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
                                 <a data-w-id="ae27a065-dfeb-a9cb-56ca-8b63a99081bc" onclick="editProspect(${prospect.id_prospect})" href="#" class="link-block-12 w-inline-block">
                      <img src="images/crayon_modifier.svg" alt="" class="table-action-icon">
                    </a>
                    <a data-w-id="ae27a065-dfeb-a9cb-56ca-8b63a99081be" onclick="deleteProspect(${prospect.id_prospect})" href="#" class="link-block-10 w-inline-block">
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

function editProspect(id) {
    // Implémentez la logique de modification
    console.log("Edit prospect with ID:", id);
}

function deleteProspect(id) {
    // Implémentez la logique de suppression
    if (confirm("Voulez-vous vraiment supprimer ce prospect ?")) {
     
        const params = new URLSearchParams(window.location.search);
        const id = params.get('id');
        console.log("Deleting prospect with URL:", id); // Ajoutez ce log pour vérifier l'URL
        fetch('/api/delete_prospect/' + id) 
        
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Prospect ${id} supprimé avec succès`);
                location.reload(); // Recharger la page pour mettre à jour la liste des prospects
            } else { 
                alert(`Erreur lors de la suppression du prospect: ${data.error}`);
            }
        })
    }
}


</script>
<script src="script_csvUpload.js"></script> 

</body>
</html>
