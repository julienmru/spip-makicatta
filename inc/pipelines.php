<?php

	function makicatta_affichage_final_prive($texte) {
		$texte = str_replace("<p class='pagination'><!-- pagination -->", '', $texte);
		$texte = str_replace("<!-- /pagination --></p>", '', $texte);
		$texte = str_replace("<table class='spip'>", "<table class='table table-striped'>", $texte);
		// certains codes appellement directement icone_horizontale et non le filtre, on doit donc repasser à la main
		// cf. https://github.com/cariagency/spip-makicatta/issues/2 causé par revisions_boite_infos()
		// dans plugins-dist/revisions/inc/revisions_pipeline.php
		if (preg_match_all("/<span class='icone .*'>.*<\/span>/", $texte, $matches)) {
			foreach($matches as $match) {
				if (($img = extraire_balise($match[0], 'img')) && ($src = extraire_attribut($img, 'src'))) {
					$texte = str_replace($img, makicatta_icone($src), $texte);
				}
			}
		}

		// bouge le titre au bon endroit (inspiré de affichage_final_prive_title_auto)
		if (($p = strpos($texte, '<!--h1-->')) !== false
			and
			(preg_match(",<h1[^>]*>(.+)</h1>,Uims", $texte, $match)
				or preg_match(",<h[23][^>]*>(.+)</h[23]>,Uims", $texte, $match))
			and $h1 = textebrut(trim($match[1]))
		) {
			$texte = str_replace($match[0], '', $texte);
			$texte = substr_replace($texte, "<h1>" . $h1 . "</h1>", $p, 9);
		}

		return $texte;
	}

	function makicatta_formulaire_receptionner($flux, $data = null) {
		switch($flux['args']['form']) {
			case 'editer_liens':
				if ($flux['args']['args'][0] == 'mots' && $modifier_lien = _request('modifier_lien')) {
					// Makicatta rassemble en un champ les mots clés de l'article
					// il faut donc reconstruire les requetes ajouter_lien et supprimer_lien
					// utilisées par editer_liens
					$ajouter_lien = array();
					$supprimer_lien = array();
					include_spip('action/editer_liens');
					$liens_tries = array();
					foreach($modifier_lien as $lien) {
						list($objet_source, $ids, $objet_lie, $idl) = explode('-', $lien);
						$liens_tries[$objet_lie.'-'.$idl][$objet_source][] = $ids;
					}
					foreach($liens_tries as $lie => $sources) {
						list($objet_lie, $idl) = explode('-', $lie);
						foreach($sources as $objet_source => $ids) {
							$liens_actuels = objet_trouver_liens([$objet_source => '*'], [$objet_lie => $idl]);
							$ids_a_ajouter = $ids;
							$ids_a_supprimer = array();
							if (is_array($liens_actuels)) {
								foreach ($liens_actuels as $lien) {
									if (($pos = array_search($lien[$objet_source], $ids) === FALSE)) {
										$supprimer_lien[] = $objet_source.'-'.$lien[$objet_source].'-'.$lie;
									} else {
										unset($ids_a_ajouter[$pos]);
									}
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
		$path = _DIR_PLUGIN_MAKICATTA;
		#$path = str_replace('../', '../../', $path);
		$path = '../../../' . $path;
		
		$variables['fa-font-path'] = $path.'lib/fontawesome/webfonts';
		return $variables;
	}

	function makicatta_styliser($flux) {
		if ($flux['args']['fond'] == 'formulaires/editer_liens' && ($t = $flux['args']['contexte']['table_source']) && is_file($flux['data'].'_'.$t.'.html')) {
			$flux['data'] = $flux['data'].'_'.$t;
		}

		return $flux;
	}