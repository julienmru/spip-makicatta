<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function action_makicatta_maj_lib_dist($arg = '') {

	// droits
	include_spip('inc/autoriser');
	if (!autoriser('configurer', '_plugins')) {
		include_spip('inc/minipres');
		echo minipres();
		exit;
	}

	include_spip('inc/svp_actionner');
	include_spip('inc/headers');
	include_spip('makicatta_fonctions');

	$actionneur = new Actionneur();

	$middle = array();
	foreach(makicatta_lib_depreciees() as $lib) {
		$middle[] = array(
			'todo' => 'getlib',
			'n' => $lib['nom'],
			'p' => $lib['nom'],
			'v' => $lib['lien'],
			's' => $lib['lien'],
		);
	}

	$actionneur->end = $middle;
	$actionneur->sauver_actions();

	include_spip('inc/headers');
	redirige_par_entete('spip.php?action=actionner');
}
