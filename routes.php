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

any('/404','/404.php');

?>