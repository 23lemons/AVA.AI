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
                    <div class="table-cell">${entryData.ID}</div>
                </div>
                <div class="table-box">
                    <div class="table-cell">${entryData.Nom}</div>
                </div>
                <div class="table-box">
                    <div class="table-cell">${entryData['Num. Téléphone']}</div>
                </div>
                <div class="table-box action">
                    <div class="table-cell">
                        <span class="status-circle status-pas-interesse"></span>
                        ${entryData.Statut}
                    </div>
                </div>
                <div class="table-box action">
                    <div class="table-cell">
                        <a href="#" class="link-block-12 w-inline-block">
                            <img src="images/crayon_modifier.svg" alt="" class="table-action-icon">
                        </a>
                        <a href="#" class="link-block-10 w-inline-block">
                            <img src="images/fermer_X_rouge.svg" alt="" class="table-action-icon-2 x">
                        </a>
                    </div>
                </div>
            `;
            container.appendChild(row);
        });
    }
    
        