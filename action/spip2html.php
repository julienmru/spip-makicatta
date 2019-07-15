<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function action_spip2html_dist($arg = '') {

	include_spip('inc/texte');

	$result = [];

	if ($GLOBALS['auteur_session']) {
		foreach($_POST as $k => $v) {
			$result[$k] = propre(typo($v));
		}
	}

	header('Content-type: application/json');
	echo json_encode($result);

}
