<?php

	function makicatta_affichage_final_prive($texte) {
		$texte = str_replace("<p class='pagination'><!-- pagination -->", '', $texte);
		$texte = str_replace("<!-- /pagination --></p>", '', $texte);
		$texte = preg_replace("/<p class='notice'>(.*)<\/p>/ms", '<div class="alert alert-warning">
                  <h5><i class="icon fas fa-exclamation-triangle"></i> '._T('makicatta:attention').'</h5>
                  \\1
                </div>', $texte);
		$texte = preg_replace("/<div class='notice'>(.*)<\/div>/ms", '<div class="alert alert-warning">
                  <h5><i class="icon fas fa-exclamation-triangle"></i> '._T('makicatta:attention').'</h5>
                  \\1
                </div>', $texte);
		return $texte;
	}