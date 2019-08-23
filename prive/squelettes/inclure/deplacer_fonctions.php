<?php
	include_spip('prive/echafaudage/hierarchie/objet_fonctions');

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
				$confirm .= '<div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" id="confirme_deplace" name="confirme_deplace" value="oui">
                    <label class="form-check-label" for="confirme_deplace">' . $scb . '</label>
                  </div>';
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