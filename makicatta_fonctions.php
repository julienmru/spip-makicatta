<?php


include_spip('inc/bandeau');
include_spip('inc/presentation_mini');
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
	$correspondance = [
		'menu_accueil' => 'fa-home',
		'menu_edition' => 'fa-pen-square',
		'menu_publication' => 'fa-check-square',
		'menu_activite' => 'fa-tachometer-alt',
		'menu_squelette' => 'fa-copy',
		'menu_administration' => 'fa-heartbeat',
		'menu_configuration' => 'fa-wrench',
		'agenda' => 'fa-calendar-alt',
		'annonce' => 'fa-bullhorn',
		'article' => 'fa-file',
		'articles' => 'fa-copy',
		'attachment' => 'fa-paperclip',
		'audio' => 'fa-music',
		'auteur' => 'fa-user',
		'auteurs' => 'fa-user',
		'auteur-0minirezo' => 'fa-user-astronaut',
		'auteur-1comite' => 'fa-user-edit',
		'auteur-5poubelle' => 'fa-user-slash',
		'auteur-6forum' => 'fa-user-shield',
		'base' => 'fa-database',
		'base-backup' => 'fa-download',
		'base-restore' => 'fa-upload',
		'base-maintenance' => 'fa-database',
		'breve' => 'fa-newspaper',
		'breves' => 'fa-newspaper',
		'cache' => 'fa-database',
		'cache-empty' => 'fa-battery-empty',
		'cadenas' => 'fa-lock',
		'calendrier' => 'fa-calendar-alt',
		'cfg' => 'fa-cogs',
		'cookie' => 'fa-cookie',
		'compat' => 'fa-spider',
		'config-contenu' => 'fa-cog',
		'config-interaction' => 'fa-cog',
		'depot' => 'fa-puzzle-piece',
		'deplacer' => 'fa-ellipsis-v',
		'diff' => 'fa-exchange-alt',
		'distant' => 'fa-globe-europe',
		'doc' => 'fa-file-code',
		'document' => 'fa-file-code',
		'documents' => 'fa-photo-video',
		'documents-cases' => 'fa-th-large',
		'documents-liste-courte' => 'fa-bars',
		'documents-liste' => 'fa-th-list',
		'erreur' => 'fa-bban',
		'evenements' => 'fa-calendar-alt',
		'export' => 'fa-download',
		'fermer' => 'fa-times',
		'fleche-droite' => 'fa-arrow-right',
		'fleche-gauche' => 'fa-arrow-left',
		'fiche-perso' => 'fa-id-card',
		'forum' => 'fa-comments',
		'forum_interne_suivi' => 'fa-comments',
		'groupe_mots' => 'fa-tags',
		'heure' => 'fa-clock',
		'image' => 'fa-image',
		'import' => 'fa-upload',
		'information' => 'fa-info-circle',
		'information-perso' => 'fa-id-card',
		'ma_langue' => 'fa-language',
		'mediabox' => 'fa-photo-video',
		'media-audio' => 'fa-file-audio',
		'media-image' => 'fa-file-image',
		'media-video' => 'fa-file-video',
		'mes_preferences' => 'fa-cogs',
		'message' => 'fa-envelope',
		'message-envoyer' => 'fa-inbox-out',
		'messagerie' => 'fa-envelope',
		'mot' => 'fa-tag',
		'mots' => 'fa-tag',
		'ok' => 'fa-check',
		'ouvrir' => 'fa-plus-circle',
		'petition' => 'fa-signature',
		'pensebete' => 'fa-sticky-note',
		'photo' => 'fa-camera',
		'php' => 'fa-php',
		'portfolio' => 'fa-image',
		'plan_site' => 'fa-sitemap',
		'plugin' => 'fa-puzzle-piece',
		'plugins' => 'fa-puzzle-piece',
		'plus-info' => 'fa-plus-square',
		'preview' => 'fa-search',
		'racine' => 'fa-server',
		'referer' => 'fa-compress-arrows-alt',
		'referers' => 'fa-compress-arrows-alt',
		'reseau' => 'fa-network-wired',
		'revision' => 'fa-code-branch',
		'repartition' => 'fa-chart-pie',
		'rss' => 'fa-rss-square',
		'rubriques' => 'fa-folder',
		'secteur' => 'fa-folder',
		'site' => 'fa-globe',
		'sites' => 'fa-globe',
		'statistique' => 'fa-chart-bar',
		'svp' => 'fa-truck-loading',
		'synchro' => 'fa-sync-alt',
		'tables' => 'fa-table',
		'telecharger' => 'fa-download',
		'traduction' => 'fa-flag-checkered',
		'tri-asc' => 'fa-sort-alpha-down',
		'tri-desc' => 'fa-sort-alpha-down-alt',
		'url' => 'fa-link',
		'video' => 'fa-video',
		'vu' => 'fa-eye',
		'warning' => 'fa-exclamation-triangle',
		'zoomin' => 'fa-search-plus',
		'zoomout' => 'fa-search-minus',
	];
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
	return (($icone_makicatta) ? '<i class="fas '.$icone_makicatta.' mr-1"></i>' : ((defined('MAKICATTA_DEBUG_ICONES') && MAKICATTA_DEBUG_ICONES) ? '<i class="fas fa-bug mr-1" title="'.$icone_spip.'"></i>' : '')) . supprimer_tags($titre);
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