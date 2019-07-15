<?php


include_spip('inc/bandeau');
include_spip('inc/presentation_mini');
if(_request('exec') == 'auteur') {
	include_spip('prive/objets/liste/auteurs_fonctions');
}

function makicatta_quete_icone($libelle) {
	$correspondance = [
		'menu_accueil' => 'fa-home',
		'menu_edition' => 'fa-pen-square',
		'menu_publication' => 'fa-check-square',
		'menu_activite' => 'fa-tachometer-alt',
		'menu_squelette' => 'fa-copy',
		'menu_administration' => 'fa-heartbeat',
		'menu_configuration' => 'fa-wrench',
		'information-perso' => 'fa-id-badge',
		'racine' => 'fa-server',
		'secteur' => 'fa-folder',
		'auteurs' => 'fa-user',
		'rubriques' => 'fa-folder',
		'articles' => 'fa-file',
		'documents' => 'fa-photo-video',
		'image' => 'fa-image',
		'calendrier' => 'fa-calendar-alt',
		'evenements' => 'fa-calendar-alt',
		'agenda' => 'fa-calendar-alt',
		'forum_interne_suivi' => 'fa-comments',
		'messagerie' => 'fa-envelope',
		'auteur' => 'fa-user',
		'cache' => 'fa-database',
		'auteur-new' => 'fa-user-plus',
		'fiche-perso' => 'fa-id-card',
		'rubrique-del' => 'fa-folder-minus',
		'preview' => 'fa-search',
		'cadenas' => 'fa-lock',
		'synchro' => 'fa-sync-alt',
		'document' => 'fa-file-code',
		'breves' => 'fa-newspaper',
		'breve' => 'fa-newspaper',
		'breve-new' => 'fa-newspaper',
		'sites' => 'fa-globe',
		'site' => 'fa-globe',
		'site-new' => 'fa-globe',
	];
	if (isset($correspondance[$libelle])) return $correspondance[$libelle];
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
								_T('info_articles_tous'),  // titre
								$key,
								null
							);
						$sousmenu[$key]->sousmenu['articles_miens'] = new Bouton(
								'',  // icone
								_T('info_articles_miens'),  // titre
								$key,
								'id_auteur='.$GLOBALS['auteur_session']['id_auteur']
							);
						$sousmenu[$key]->sousmenu['article_add'] = new Bouton(
								'',  // icone
								_T('bouton_ajouter'),  // titre
								'article_edit',
								'new=oui'
							);
					} elseif ($key == 'documents') {
						$sousmenu[$key]->libelle = _T('medias:documents');
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
	return (($icone_makicatta) ? '<i class="fas '.$icone_makicatta.' mr-1"></i>' : '<i class="fas fa-bug mr-1" title="'.$icone_spip.'"></i>') . supprimer_tags($titre);
}

function makicatta_titre_boite($titre) {
	$icone_spip = preg_replace('/-[0-9]+\.([a-z]+)$/', '', basename(extraire_attribut(extraire_balise($titre, 'img'), 'src')));
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