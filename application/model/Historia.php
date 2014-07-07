<?php

class Model_Historia
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function zapiszRekord($rekord, $rekord_id, $rekord_typ, $operacja, $admin_id)
	{
		$db     = Core_DB::instancja();
		$a_dane = array();

		$a_dane['historia_rekord']        = serialize($rekord);
		$a_dane['historia_typ']           = $rekord_typ;
		$a_dane['historia_uzytkownik_id'] = $admin_id;
		$a_dane['historia_czas']          = date("Y-m-d H:i:s");
		$a_dane['historia_rekord_id']     = $rekord_id;
		$a_dane['historia_klucz']         = md5(time());
		$a_dane['historia_operacja']      = $operacja;

		$resultSQL = $db->insert('historia_zmian', $a_dane, 0);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function pobierzRekord($klucz)
	{
		$db      = Core_DB::instancja();
		$a_dane  = array();
		$sql_get = "SELECT * FROM historia_zmian WHERE historia_klucz='" . $klucz . "' ";
		$a_dane  = $db->get_row($sql_get);

		//echo $a_dane['historia_rekord'];
		return unserialize($a_dane['historia_rekord']);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzHistorie($obiekt_id, $obiekt_typ)
	{
		$db     = Core_DB::instancja();
		$a_dane = array();

		if ((int)$obiekt_id > 0 && $obiekt_typ != '') {
			$sql_get = "SELECT * FROM historia_zmian WHERE historia_typ='" . $obiekt_typ . "' AND historia_rekord_id=" . $obiekt_id . " ORDER BY historia_czas DESC ";
			$r_dane  = $db->query($sql_get);

			foreach ($r_dane as $index => $dane) {
				$m_admin = new Model_Administrator($dane['historia_uzytkownik_id']);

				$dane['admin']  = $m_admin->imie . ' ' . $m_admin->nazwisko;
				$a_dane[$index] = $dane;
			}
		}

		return $a_dane;
	}
}
