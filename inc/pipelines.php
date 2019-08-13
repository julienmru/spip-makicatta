<?php

	function makicatta_affichage_final_prive($texte) {
		$texte = str_replace("<p class='pagination'><!-- pagination -->", '', $texte);
		$texte = str_replace("<!-- /pagination --></p>", '', $texte);
		return $texte;
	}

	function makicatta_formulaire_receptionner($flux, $data = null) {
		switch($flux['args']['form']) {
			case 'editer_liens':
				if ($flux['args']['args'][0] == 'mots' && $modifier_lien = _request('modifier_lien')) {
					// Makicatta rassemble en un champ les mots clés de l'article
					// il faut donc reconstruire les requetes ajouter_lien et supprimer_lien
					// utilisées par editer_liens
					$ajouter_lien = [];
					$supprimer_lien = [];
					include_spip('action/editer_liens');
					$liens_tries = [];
					foreach($modifier_lien as $lien) {
						list($objet_source, $ids, $objet_lie, $idl) = explode('-', $lien);
						$liens_tries[$objet_lie.'-'.$idl][$objet_source][] = $ids;
					}
					foreach($liens_tries as $lie => $sources) {
						list($objet_lie, $idl) = explode('-', $lie);
						foreach($sources as $objet_source => $ids) {
							$liens_actuels = objet_trouver_liens([$objet_source => '*'], [$objet_lie => $idl]);
							$ids_a_ajouter = $ids;
							$ids_a_supprimer = [];
							foreach ($liens_actuels as $lien) {
								if (($pos = array_search($lien[$objet_source], $ids) === FALSE)) {
									$supprimer_lien[] = $objet_source.'-'.$lien[$objet_source].'-'.$lie;
								} else {
									unset($ids_a_ajouter[$pos]);
								}
							} 
							foreach($ids_a_ajouter as $id) {
								$ajouter_lien[] = $objet_source.'-'.$id.'-'.$lie;
							}
						}
					}
					set_request('ajouter_lien', $ajouter_lien);
					set_request('supprimer_lien', $supprimer_lien);
				}
				break;
		}
	}

	function makicatta_formulaire_traiter($flux, $data = null) {
 		switch($flux['args']['form']) {
			case 'editer_liens':
				if ($flux['args']['args'][0] == 'mots' && $modifier_lien = _request('modifier_lien')) {
					$flux['data']['message_ok'] = _T('ecrire:info_modification_enregistree');
				}
				break;
		}
		return $flux;
	}

	function makicatta_insert_head($flux){
		if (defined('MAKICATTA_INSERT_BOOTSTRAP') && MAKICATTA_INSERT_BOOTSTRAP && find_in_path('lib/ContentBuilder/contentbuilder/contentbuilder.min.js')) {
			$flux .= '<link rel="stylesheet" type="text/css" href="'.timestamp(direction_css(scss_select_css('css/makicatta-public.css'))).'" />';
		}
		return $flux;
	}

	function makicatta_scss_variables($variables) {
		$me = realpath(__DIR__);
		$racine = realpath(_DIR_RACINE);
		if (strpos($me, $racine) === 0) {
			$depth = substr_count(substr($me, strlen($racine)), DIRECTORY_SEPARATOR);
			$path = str_repeat('../', $depth);
		} else {
			$path = '../../../';
		}
		$variables['fa-font-path'] = $path.'lib/fontawesome/webfonts';
		return $variables;
	}