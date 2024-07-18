CREATE TABLE Entreprise (
    id_entreprise INT AUTO_INCREMENT PRIMARY KEY,
    username_entreprise VARCHAR(20) NOT NULL,
    mdp_entreprise VARCHAR(20) NOT NULL,
    nom_entreprise VARCHAR(20) NOT NULL
);

CREATE TABLE Infos_Entreprise (
    id_entreprise INT PRIMARY KEY,
    prenom_fondateur VARCHAR(20) NOT NULL,
    nom_fondateur VARCHAR(20) NOT NULL,
    num_tel_entreprise VARCHAR(20) NOT NULL,
    courriel_entreprise VARCHAR(50) NOT NULL,
    adresse_entreprise TEXT NOT NULL,
    heures_ouvertures TEXT NOT NULL,
    lien_site_web TEXT NOT NULL,
    lien_page_facebook TEXT,
    lien_page_instagram TEXT,
    lien_page_tiktok TEXT,
    lien_chaine_youtube TEXT,
    description_entreprise TEXT NOT NULL,
    description_service TEXT NOT NULL,
    prix_service DECIMAL(10, 2) NOT NULL,
    temps_livraison VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id_entreprise)
);

CREATE TABLE Prospects (
    id_prospect INT AUTO_INCREMENT PRIMARY KEY,
    prenom_prospect VARCHAR(20) NOT NULL,
    nom_prospect VARCHAR(20) NOT NULL,
    num_tel_prospect VARCHAR(20) NOT NULL,
    courriel_prospect VARCHAR(50) NOT NULL,
    id_entreprise INT,
    statut_prospect VARCHAR(20),
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id_entreprise)
);

