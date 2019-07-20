<?php

	if (!defined('_SPIP_SELECT_RUBRIQUES')) define('_SPIP_SELECT_RUBRIQUES', 10000); 
	if (!defined('_SCSS_SOURCE_MAP')) define('_SCSS_SOURCE_MAP', true); 

	define('PORTE_PLUME_PUBLIC', FALSE);

	
	if (!isset($GLOBALS['z_blocs_ecrire'])) {
		$GLOBALS['z_blocs_ecrire'] = ['contenu', 'navigation', 'extra', 'head', 'hierarchie', 'top'];
	}
	$GLOBALS['z_blocs_ecrire'][] = 'header';
	$GLOBALS['z_blocs_ecrire'][] = 'liens';

	function makicatta_edition_directe($objet) {
		return (in_array($objet, ['article', 'rubrique']));
	}