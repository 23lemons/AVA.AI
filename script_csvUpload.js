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