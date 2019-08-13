<?php

// nos filtres pour les icones

function filtre_icone($lien, $texte, $fond, $align = "", $fonction = "", $class = "", $javascript = "") {
	return makicatta_icone_base($lien, $texte, $fond, $fonction, "verticale $align $class", $javascript);
}

function filtre_icone_horizontale($lien, $texte, $fond, $fonction = "", $class = "", $javascript = "") {
	return makicatta_icone_base($lien, $texte, $fond, $fonction, "horizontale $class", $javascript);
}

// remplacement de prepare_icone_base (source : inc/filtres)

function makicatta_prepare_icone_base($type, $lien, $texte, $fond, $fonction = "", $class = "", $javascript = "") {
	if (in_array($fonction, array("del", "supprimer.gif"))) {
		$class .= ' danger';
	} elseif ($fonction == "rien.gif") {
		$fonction = "";
	} elseif ($fonction == "delsafe") {
		$fonction = "del";
	}

	// remappage des icone : article-24.png+new => article-new-24.png
	if ($icone_renommer = charger_fonction('icone_renommer', 'inc', true)) {
		list($fond, $fonction) = $icone_renommer($fond, $fonction);
	}

	// ajouter le type d'objet dans la class de l'icone
	$class .= " " . substr(basename($fond), 0, -4);

	$alt = attribut_html($texte);
	$title = " title=\"$alt\""; // est-ce pertinent de doubler le alt par un title ?

	$ajax = "";
	if (strpos($class, "ajax") !== false) {
		$ajax = "ajax";
		if (strpos($class, "preload") !== false) {
			$ajax .= " preload";
		}
		if (strpos($class, "nocache") !== false) {
			$ajax .= " nocache";
		}
		$ajax = " class='$ajax'";
	}

	$size = 24;
	if (preg_match("/-([0-9]{1,3})[.](gif|png)$/i", $fond, $match)) {
		$size = $match[1];
	}

	$icone_spip = preg_replace('/-[0-9]+\.([a-z]+)$/', '', basename($fond));

	if ($fonction) {
		// 2 images pour composer l'icone : le fond (article) en background,
		// la fonction (new) en image
		$icone = http_img_pack($fonction, $alt, "width='$size' height='$size'\n" .
			http_style_background($fond));
	} else {
		$icone = http_img_pack($fond, $alt, "width='$size' height='$size'");
	}

	if ($type == 'lien') {
		return "<span class='icone s$size $class'>"
		. "<a href='$lien'$ajax$javascript>"
		. makicatta_icone($icone_spip)
		. "$texte"
		. "</a></span>\n";
	} else {
		return bouton_action("$icone<b>$texte</b>", $lien, "icone s$size $class", $javascript, $alt);
	}
}

// remplacement de icone_base (source : inc/filtres)

function makicatta_icone_base($lien, $texte, $fond, $fonction = "", $class = "", $javascript = "") {
	return makicatta_prepare_icone_base('lien', $lien, $texte, $fond, $fonction, $class, $javascript);
}