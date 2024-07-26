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
        