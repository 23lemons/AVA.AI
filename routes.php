<?php

require_once __DIR__.'/router.php';

get('/nouveau_login_ava.php', '/nouveau_login_ava.php');
get('/nouveau_login_ava', '/nouveau_login_ava.php');

post('/nouveau_login_ava.php', '/nouveau_login_ava.php');

get('/informationPrincipale.php', '/informationPrincipale.php');
post('/informationPrincipale.php', '/informationPrincipale.php');

get('/informationEntreprise.php', '/informationEntreprise.php');
post('/informationEntreprise.php', '/informationEntreprise.php');

get('/liensEntreprise.php', '/liensEntreprise.php');
post('/liensEntreprise.php', '/liensEntreprise.php');

any('/landing_page.php', '/landing_page.php');
any('/landing_page', '/landing_page.php');
any('/', '/landing_page.php');

get('/dashboard_page.php', '/dashboard_page.php');

//api

post('/api/repondre_prospect', '/api/repondre_prospect.php');
get('/api/repondre_prospect', '/api/repondre_prospect.php');

post('/api/contacter_prospect/$id_prospect', '/api/contacter_prospect.php');

post('/api/update_database', '/api/update_database.php');
get('/api/update_database', '/api/update_database.php');

get('/api/infos_clients', '/api/get_infos_clients.php');

delete('/api/delete_prospect/$id', '/api/delete_prospect.php');

post('/api/ajouter_prospect', '/api/ajouter_prospect.php');

?>