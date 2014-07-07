<?php

class kategoria_View
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function wyswietlListe($id, $jezyk, $wciecie, $zaznaczona, $podstrona_id = '')
	{
		$podstrony                     = new Model_KategoriaProdukt();
		$podstrony->filtr_sortuj_po    = 'ko.kategoria_miejsce ASC, ko.kategoria_nazwa';
		$podstrony->filtr_sortuj_jak   = 'ASC';
		$podstrony->filtr_id_nadrzedna = '' . $id . '';
		$podstrony->filtr_jezyk_id     = $jezyk;
		$podstrony->filtruj();

		$html = '';

		foreach ($podstrony->rekordy as $p_id) {
			if ($podstrona_id != $p_id) {
				$p = new Model_KategoriaProdukt($p_id);

				$html .= '<option value="' . $p->id . '"';
				if (is_array($zaznaczona)) {
					if (array_search($p->id, $zaznaczona) !== false) {
						$html .= ' selected="selected"';
					}
				} else {
					if ($p->id == $zaznaczona) {
						$html .= ' selected="selected"';
					}
				}
				$html .= '>';
				for ($i = 0; $i < $wciecie; $i++) {
					$html .= '&nbsp;&nbsp;&nbsp;';
				}
				if ($wciecie != 0) {
					$html .= '-&nbsp;';
				}
				$html .= '' . $p->nazwa[$jezyk] . '</option>';
				$wciecie++;
				$html .= self::wyswietlListe($p->id, $jezyk, $wciecie, $zaznaczona, $podstrona_id);
				$wciecie--;
			}
		}
		return $html;
	}

}
