<?php

class produkt_View
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function wyswietlListeKategorii($id, $jezyk, $wciecie, $zaznaczona, $kategoria_id = '')
	{
		$kategorie                     = new Model_KategoriaProdukt();
		$kategorie->filtr_sortuj_po    = 'ko.kategoria_miejsce ASC, ko.kategoria_nazwa';
		$kategorie->filtr_sortuj_jak   = 'ASC';
		$kategorie->filtr_id_nadrzedna = '' . $id . '';
		$kategorie->filtr_jezyk_id     = $jezyk;
		$kategorie->filtruj();

		$html = '';

		foreach ($kategorie->rekordy as $k_id) {
			if ($kategoria_id != $k_id) {
				$p = new Model_KategoriaProdukt($k_id);

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
				/*if ($p->id_nadrzedna == 0) {
					$html .= ' disabled="disabled"';
				}*/

				$html .= '>';
				for ($i = 0; $i < $wciecie; $i++) {
					$html .= '&nbsp;&nbsp;&nbsp;';
				}
				if ($wciecie != 0) {
					$html .= '-&nbsp;';
				}
				$html .= '' . $p->nazwa[$jezyk] . '</option>';
				$wciecie++;
				$html .= self::wyswietlListeKategorii($p->id, $jezyk, $wciecie, $zaznaczona, $kategoria_id);
				$wciecie--;
			}
		}
		return $html;
	}

}
