-- Inserting data into Entreprise table
INSERT INTO Entreprise (username_entreprise, mdp_entreprise, courriel_entreprise)
VALUES ('techsolutions', PASSWORD('securepassword123'), 'contact@techsolutions.com');

SET @id_entreprise = LAST_INSERT_ID();

-- Inserting data into Infos_Entreprise table
INSERT INTO Infos_Entreprise (id_entreprise, nom_entreprise, prenom_fondateur, nom_fondateur, num_tel_entreprise,
                                adresse_entreprise, heures_ouvertures,
                                description_entreprise, description_service, prix_service, temps_livraison)
VALUES (@id_entreprise, 'Tech Solutions', 'John', 'Doe', '1234567890', '123 Tech Street, City, Country',
        '9am - 5pm', 'Leading provider of tech solutions',
        'Comprehensive IT and software services', 500.00, '3 days');

INSERT INTO Liens_Entreprise (id_entreprise, lien_site_web, lien_page_facebook, lien_page_instagram,
                                 lien_page_tiktok, lien_chaine_youtube)
VALUES (@id_entreprise, 'https://techsolutions.com', 'https://facebook.com/techsolutions', 'https://instagram.com/techsolutions',
        'https://tiktok.com/@techsolutions', 'https://youtube.com/techsolutions');

-- Inserting data into Prospects table
INSERT INTO Prospects (prenom_prospect, nom_prospect, num_tel_prospect, courriel_prospect, id_entreprise, statut_prospect)
VALUES ('Alice', 'Smith', '1234567891', 'alice.smith@example.com', @id_entreprise, 'Intéressé');

INSERT INTO Prospects (prenom_prospect, nom_prospect, num_tel_prospect, courriel_prospect, id_entreprise, statut_prospect)
VALUES ('Bob', 'Johnson', '1234567892', 'bob.johnson@example.com', @id_entreprise, 'En attente');

INSERT INTO Prospects (prenom_prospect, nom_prospect, num_tel_prospect, courriel_prospect, id_entreprise, statut_prospect)
VALUES ('Charlie', 'Brown', '1234567893', 'charlie.brown@example.com', @id_entreprise, 'Pas intéressé');