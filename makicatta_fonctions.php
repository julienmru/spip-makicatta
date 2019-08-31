<?php


include_spip('inc/bandeau');
include_spip('inc/presentation_mini');
include_spip('inc/filtres_makicatta');
if(_request('exec') == 'auteur') {
	include_spip('prive/objets/liste/auteurs_fonctions');
}

function makicatta_quete_icone($libelle) {
	$libelle = preg_replace('/-[0-9]+\.([a-z]+)$/', '', basename($libelle));
	if (preg_match('/(.*)-(add|new|edit|del)$/', $libelle, $matches)) {
		$libelle = $matches[1];
		$action = $matches[2];
	} else {
		$action = '';
	}
	include __DIR__.'/makicatta_icones.php';
	$correspondance = pipeline('makicatta_icones', $correspondance);
	if (isset($correspondance[$libelle])) return $correspondance[$libelle].(($action) ? ' ico-'.$action : '');
	else return FALSE;
}

function makicatta_reorganise_menu($menu) {
	$nouveau_menu = array();
	foreach($menu as $cle => $bouton) {
		if ($cle == 'menu_edition') {
			$sousmenu = array();
			foreach(['articles', 'documents', 'rubriques'] as $key) {
				if (array_key_exists($key, $bouton->sousmenu)) {
					$sousmenu[$key] = $bouton->sousmenu[$key];
					if ($key == 'articles') {
						$sousmenu[$key]->sousmenu['articles_tous'] = new Bouton(
								'',  // icone
								'info_articles_tous',  // titre
								$key,
								null
							);
						$sousmenu[$key]->sousmenu['articles_miens'] = new Bouton(
								'',  // icone
								'info_articles_miens',  // titre
								$key,
								'id_auteur='.$GLOBALS['auteur_session']['id_auteur']
							);
						$sousmenu[$key]->sousmenu['article_add'] = new Bouton(
								'',  // icone
								'bouton_ajouter',  // titre
								'article_edit',
								'new=oui'
							);
					} elseif ($key == 'documents') {
						$sousmenu[$key]->libelle = 'medias:documents';
					} elseif ($key == 'rubriques') {
						$sousmenu[$key]->sousmenu['rubriques_tous'] = new Bouton(
								'',  // icone
								'makicatta:info_rubriques_tous',  // titre
								$key,
								null
							);
						foreach(sql_allfetsel('id_rubrique, titre', 'spip_rubriques', 'id_parent = 0', '0+titre, titre') as $rubrique) {
							$sousmenu[$key]->sousmenu['rubrique_'.$rubrique['id_rubrique']] = new Bouton(
									'',  // icone
									$rubrique['titre'].'*',  // titre arec une étoile à la fin pour pas que ça soit traduit
									'rubrique',
									'id_rubrique='.$rubrique['id_rubrique']
								);
						}
					}
					unset($bouton->sousmenu[$key]);
				}
			}
			$sousmenu = array_merge($sousmenu, $bouton->sousmenu);
			foreach($sousmenu as $sousmenu_cle => $sousmenu_bouton) {
				$nouveau_menu[$sousmenu_cle] = $sousmenu_bouton;
			}
		} else {
			$nouveau_menu[$cle] = $bouton;
		}
	}
	return $nouveau_menu;
}

function makicatta_icone($icone_spip) {
	if (!$icone_spip) return '';
	$icone_makicatta = makicatta_quete_icone($icone_spip);
	if (defined('MAKICATTA_DEBUG_ICONES')) {
		return (($icone_makicatta) ? '<i class="'.$icone_makicatta.' mr-1" title="'.$icone_spip.'"></i>' : '<i class="fas fa-bug mr-1" title="'.$icone_spip.'"></i>');
	} else {
		return (($icone_makicatta) ? '<i class="'.$icone_makicatta.' mr-1"></i>' : '');
	}
}

function makicatta_titre_boite($titre) {
	$icone_spip = extraire_attribut(extraire_balise($titre, 'img'), 'src');
	$titre_clean = strip_tags($titre);
	return makicatta_icone($icone_spip).$titre_clean;
}

function makicatta_coupera($texte, $suffixe) {
	if (couper($texte, $suffixe) != $texte) return $texte;
	else return FALSE;
}

function filtre_afficher_plus_info($lien, $titre = "+", $titre_lien = "") {
	return '';

	$titre = attribut_html($titre);
	$icone = "\n<a href='$lien' title='$titre' class='plus_info'>" .
		http_img_pack("information-16.png", $titre) . "</a>";

	if (!$titre_lien) {
		return $icone;
	} else {
		return $icone . "\n<a href='$lien'>$titre_lien</a>";
	}
}

function makicatta_lib_depreciees() {
	$get_infos = charger_fonction('get_infos', 'plugins');
	$dir = implode('/', explode('/', _DIR_PLUGIN_MAKICATTA, -2)). '/';
	$plug = end(explode('/', _DIR_PLUGIN_MAKICATTA, -1));
	
	$plugin = $get_infos($plug, false, $dir);
	$obsolete = array();
	foreach($plugin['lib'] as $lib) {
		lire_fichier(_DIR_LIB.$lib['nom'].'/install.log', $contenu);
		if (strpos($contenu, basename($lib['lien'])) == FALSE) {
			$obsolete[] = $lib;
		}
	}
	return $obsolete;
}