<?php
/*
* Configuration de SPIP pour makicatta
* Attention, fichier en UTF-8 sans BOM
*/

if (!defined('_ECRIRE_INC_VERSION')) return;

include_spip('inc/meta');
/*
 * Fonction d'installation, mise a jour de la base
 *
 * @param unknown_type $nom_meta_base_version
 * @param unknown_type $version_cible
 */
function makicatta_upgrade($nom_meta_base_version,$version_cible){
	$maj = array();
	
	$maj['3.1.67'] = array( 
		array('makicatta_1_0_0'),
		array('makicatta_finalisationinstall') /* À rajouter à la fin systématiquement */		
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}

function makicatta_1_0_0() {
	ecrire_meta('barre_typo_generalisee', 'a:6:{s:38:\"rubriques_texte_barre_typo_generalisee\";s:2:\"on\";s:40:\"groupesmots_texte_barre_typo_generalisee\";s:2:\"on\";s:33:\"mots_texte_barre_typo_generalisee\";s:2:\"on\";s:40:\"sites_description_barre_typo_generalisee\";s:2:\"on\";s:48:\"configuration_description_barre_typo_generalisee\";s:2:\"on\";s:42:\"auteurs_quietesvous_barre_typo_generalisee\";s:2:\"on\";}','non');
	ecrire_meta('ppp', 'a:5:{s:14:"descriptif_ppp";s:0:"";s:9:"chapo_ppp";s:2:"on";s:6:"ps_ppp";s:2:"on";s:29:"configuration_description_ppp";s:2:"on";s:23:"auteurs_quietesvous_ppp";s:2:"on";}', 'non');
	ecrire_meta('orthotypo',"a:7:{s:10:\"guillemets\";s:1:\"1\";s:9:\"exposants\";s:1:\"1\";s:4:\"mois\";s:1:\"1\";s:4:\"caps\";s:1:\"0\";s:5:\"fines\";s:1:\"0\";s:11:\"corrections\";s:1:\"1\";s:18:\"corrections_regles\";s:319:\"oeuf = œuf\ncceuil = ccueil\n(a priori) = {a priori}\n(([hH])uits) = $1uit\n/([cC]h?)oeur/ = $1œur\n/oeuvre/ = œuvre\n(O[Ee]uvre([rs]?)) = Œuvre$1\n/\b([cC]|[mM].c|[rR]ec)on+ais+a((?:n(?:ce|te?)|ble)s?)\b/ = $1onnaissa$2\nCO2 = <abbr title=\"CO2, Dioxyde de carbone, O=C=O\">CO<sub>2</sub></abbr>\noeil = œil\n(O[Ee]il) = Œil\";}", 'non');
	
	ecrire_config('crayons/barretypo','on');
	ecrire_config('crayons/reduire_logo',400);
	ecrire_config('crayons/espaceprive','on');
	ecrire_config('crayons/exec_autorise','*');

	ecrire_meta('auto_compress_css', 'oui', 'non');
	ecrire_meta('auto_compress_js', 'oui', 'non');
	
	ecrire_meta('inserer_modeles', 'a:1:{s:6:"objets";a:2:{i:0;s:13:"spip_articles";i:1;s:0:"";}}', 'non');

	ecrire_config('bigup/max_file_size','64');

	ecrire_config('orthotypo/caps','0');
	ecrire_config('orthotypo/fines','0');
	ecrire_config('orthotypo/corrections','1');
}


function makicatta_finalisationinstall() {
	// On termine en invalidant les caches
	include_spip('inc/invalideur');
	suivre_invalideur("makicatta");
	
}

/*
 * Fonction de desinstallation
 *
 * @param unknown_type $nom_meta_base_version
 */
function makicatta_vider_tables($nom_meta_base_version) {
	effacer_meta($nom_meta_base_version);
}
