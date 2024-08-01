function handleFileUpload() {

    const fileInput = document.getElementById('csv-file'); //Recupere l'elt input de type file definit par son id
    const file = fileInput.files[0]; //Recupere premier fichier selectionne par le user

    if(file) { //Si un fichier a ete selectionne

        const read = new FileReader(); //Cree objet FileReader pour lire le fichier csv choisi
        read.onload = function(event) { //Fonction est appele lorsque le fichier est lu

            const csvData = event.target.result; //Recupere contenu du fichier csv
            processCSV(csvData); //Appelle la fonction pocessCSV pour traiter
            ;}
            read.readAsText(file); //Lit le fichier et renvoie sous une chaine de char
        }

    else { 

        alert("Veuillez sélectionner un fichier"); //Message si user n'as pas choisi de fichier 
        }
    }

    function processCSV(data) {
        const rows = data.split('\n').map(row => row.split(','));
        const headers = rows[0];
        const entries = rows.slice(1);
        const container = document.getElementById('prospects-container');
        container.innerHTML = ''; // Efface le contenu actuel du conteneur
    
        entries.forEach((entry, index) => {
            if (entry.length < 2) return; // Ignore les lignes incorrectes ou vides
    
            const entryData = {
                "ID": index + 1,
                "Nom": entry[0].trim(),
                "Num. Téléphone": entry[1].trim(),
                "Statut": "Inactif"
            };
    
            // Création d'une nouvelle ligne pour chaque entrée
            const row = document.createElement('div');
            row.className = 'table-row';
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
    
    function closeModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }
    
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
    
    
        