<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function action_spip2html_dist($arg = '') {

	if ($GLOBALS['auteur_session']) {
		include_spip('inc/texte');
		header('Content-type: text/html');
		echo liens_absolus(propre(typo($_POST['data'])));
	} else {
		header('Content-type: text/html');
		echo $_POST['data'];
	}

}
