<?php

	function makicatta_affichage_final_prive($texte) {
		$texte = str_replace("<p class='pagination'><!-- pagination -->", '', $texte);
		$texte = str_replace("<!-- /pagination --></p>", '', $texte);
		return $texte;
	}