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
	$nouveau_menu = [];
	foreach($menu as $cle => $bouton) {
		if ($cle == 'menu_edition') {
			$sousmenu = [];
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

function makicatta_trouve_squelette($chemin) {
	if (is_file(__DIR__.'/'.$chemin.'.html')) return $chemin;
	else return dirname($chemin).'/dist';
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

/**
 * Afficher le sélecteur de rubrique
 *
 * Il permet de placer un objet dans la hiérarchie des rubriques de SPIP
 *
 * @uses inc_chercher_rubrique_dist()
 *
 * @param string $titre
 * @param int $id_objet
 * @param int $id_parent
 * @param string $objet
 * @param int $id_secteur
 * @param bool $restreint
 * @param bool $actionable
 *   true : fournit le selecteur dans un form directement postable
 * @param bool $retour_sans_cadre
 * @return string
 */
function makicatta_chercher_rubrique(
	$titre,
	$id_objet,
	$id_parent,
	$objet,
	$id_secteur,
	$restreint,
	$actionable = false,
	$retour_sans_cadre = false
) {

	include_spip('inc/autoriser');
	if (intval($id_objet) && !autoriser('modifier', $objet, $id_objet)) {
		return "";
	}
	if (!sql_countsel('spip_rubriques')) {
		return "";
	}
	$chercher_rubrique = charger_fonction('chercher_rubrique', 'inc');
	$form = '<div class="modal-body">';
	$form .= inserer_attribut($chercher_rubrique($id_parent, $objet, $restreint, ($objet == 'rubrique') ? $id_objet : 0), 'data-width', '100%');

	if ($id_parent == 0) {
		$logo = "racine-24.png";
	} elseif ($id_secteur == $id_parent) {
		$logo = "secteur-24.png";
	} else {
		$logo = "rubrique-24.png";
	}

	$confirm = "";
	if ($objet == 'rubrique') {
		// si c'est une rubrique-secteur contenant des breves, demander la
		// confirmation du deplacement
		$contient_breves = sql_countsel('spip_breves', "id_rubrique=" . intval($id_objet));

		if ($contient_breves > 0) {
			$scb = ($contient_breves > 1 ? 's' : '');
			$scb = _T('avis_deplacement_rubrique',
				array(
					'contient_breves' => $contient_breves,
					'scb' => $scb
				));
			$confirm .= "\n<div class='confirmer_deplacement verdana2'>"
				. "<div class='choix'><input type='checkbox' name='confirme_deplace' value='oui' id='confirme-deplace' /><label for='confirme-deplace'>"
				. $scb .
				"</label></div></div>\n";
		} else {
			$confirm .= "<input type='hidden' name='confirme_deplace' value='oui' />\n";
		}
	}
	$form .= $confirm;
	$form .= '</div>';
	if ($actionable) {
		if (strpos($form, '<select') !== false) {
			$form .= '<div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">'._T('bouton_fermer').'</button>
              <button type="submit" class="btn btn-primary">'._T('bouton_enregistrer').'</button>
            </div>';
		}
		$form = "<input type='hidden' name='editer_$objet' value='oui' />\n" . $form;
		if ($action = charger_fonction("editer_$objet", "action", true)) {
			$form = generer_action_auteur("editer_$objet", $id_objet, self(), $form,
				" method='post' class='submit_plongeur'");
		} else {
			$form = generer_action_auteur("editer_objet", "$objet/$id_objet", self(), $form,
				" method='post' class='submit_plongeur'");
		}
	}

	if ($retour_sans_cadre) {
		return $form;
	}

	include_spip('inc/presentation');

	return debut_cadre_couleur($logo, true, "", $titre) . $form . fin_cadre_couleur(true);

}