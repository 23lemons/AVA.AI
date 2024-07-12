
INSERT INTO Entreprise (
    username_entreprise, mdp_entreprise, nom_entreprise, prenom_fondateur, nom_fondateur, num_tel_entreprise, courriel_entreprise, 
    adresse_entreprise, heures_ouvertures, lien_site_web, lien_page_facebook, lien_page_instagram, 
    lien_page_tiktok, lien_chaine_youtube, description_entreprise, description_service, 
    prix_service, temps_livraison
) VALUES (
    'webcreations','12345678','Web Creations', 'John', 'Doe', '1234567890', 'contact@webcreations.com', 
    '123 Web Street, City, Country', 'Mon-Fri 9:00-18:00', 'http://www.webcreations.com', 
    'http://facebook.com/webcreations', 'http://instagram.com/webcreations', 
    'http://tiktok.com/@webcreations', 'http://youtube.com/webcreations', 
    'Web Creations specializes in creating custom websites for businesses.', 
    'We offer website design, development, and maintenance services.', 
    1500.00, '2 weeks'
);

SET @entreprise_id = LAST_INSERT_ID();

INSERT INTO Prospects (prenom_prospect, nom_prospect, num_tel_prospect, courriel_prospect, statut_prospect, id_entreprise) VALUES
('Alice', 'Smith', '1234567891', 'alice.smith@example.com', 'en attente', @entreprise_id),
('Bob', 'Johnson', '1234567892', 'bob.johnson@example.com', 'pas intéressé', @entreprise_id),
('Charlie', 'Brown', '1234567893', 'charlie.brown@example.com', 'intéressé', @entreprise_id);