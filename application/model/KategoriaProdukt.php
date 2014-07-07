<?php

class Model_KategoriaProdukt
{
	const VIEW_PRODUCTS           = 0;
	const VIEW_TILES              = 1;
	const MODULE_PROMOTIONS       = 2;
	const MODULE_ASORTYMENT_SIECI = 3;

	public $id = '';
	public $id_nadrzedna = '';

	public $view_type = 0;

	public $szablon_id = '';
	public $kolor_tekst = '';
	public $kolor_tlo = '';

	public $nazwa = array();
	public $tresc = array();
	public $miejsce = array();
	public $aktywna = array();
	public $nadrzedne = array();
	public $errors = array();

	public $tagIds = array();
	public $tags = array();

	//========= parametry filtrowania =============
	public $filtr_id = '';
	public $filtr_mapa_serwisu = '';
	public $filtr_id_nadrzedna = '';
	public $filtr_aktywna = '';
	public $filtr_nazwa = '';
	public $filtr_tresc = '';
	public $filtr_jezyk_id = '';
	public $filtr_sortuj_po = '';
	public $filtr_sortuj_jak = '';
	public $filtr_strona = '';
	public $filtr_ilosc_wynikow = '';
	public $filtr_maks = '';

	public $rekordy = array();
	public $ilosc_rekordow = 0;

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function __construct($id = 0)
	{
		if ((int)$id > 0) {
			$this->pobierz($id);
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierz($id)
	{
		$db = Core_DB::instancja();
		if ((int)$id > 0) {
			$sql              = 'SELECT * FROM kategorie_produkty WHERE kategoria_id = ' . (int)$id . ' LIMIT 1';
			$result_kategoria = $db->get_row($sql);

			if (count($result_kategoria) > 0) {
				$this->id           = (int)$result_kategoria['kategoria_id'];
				$this->id_nadrzedna = $result_kategoria['kategoria_id_nadrzedna'];
				$this->szablon_id   = $result_kategoria['kategoria_szablon_id'];
				$this->kolor_tekst  = $result_kategoria['kategoria_kolor_tekst'];
				$this->kolor_tlo    = $result_kategoria['kategoria_kolor_tlo'];
				$this->view_type    = $result_kategoria['kategoria_view_type'];

				$this->zwrocIdNadrzednych($this->id);

				$sql_opis              = 'SELECT * FROM kategorie_produkty_opisy WHERE kategoria_id = ' . (int)$this->id;
				$result_kategoria_opis = $db->query($sql_opis);

				foreach ($result_kategoria_opis as $opis_row) {
					$this->nazwa[$opis_row['jezyk_id']]   = stripslashes($opis_row['kategoria_nazwa']);
					$this->miejsce[$opis_row['jezyk_id']] = $opis_row['kategoria_miejsce'];
					$this->aktywna[$opis_row['jezyk_id']] = $opis_row['kategoria_aktywna'];
				}
			} else {
				$this->errors[] = 'Nie odnaleziono kategorii o nr id: ' . $id . '.';
			}

			$this->tagIds = Model_Tag_Service::getTagIdsForObject($this);
			$this->tags   = Model_Tag_Service::getTags($this->tagIds);
		}
		if (count($this->errors) > 0) {
			return false;
		} else {
			return true;
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapisz()
	{
		$db = Core_DB::instancja();

		$rekord                           = array();
		$rekord["kategoria_id_nadrzedna"] = (int)$this->id_nadrzedna;
		$rekord["kategoria_szablon_id"]   = $this->szablon_id;
		$rekord["kategoria_kolor_tekst"]  = $this->kolor_tekst;
		$rekord["kategoria_kolor_tlo"]    = $this->kolor_tlo;
		$rekord["kategoria_view_type"]    = (int)$this->view_type;

		if ((int)$this->id > 0) {
			$rekord["kategoria_id"] = (int)$this->id;
			$db->update('kategorie_produkty', $rekord, 'kategoria_id = ' . (int)$this->id);
		} else {
			$db->insert('kategorie_produkty', $rekord);
			$this->id = $db->last_insert_id('kategorie_produkty');
		}

		if ((int)$this->id > 0) {
			$a_jezyki = Model_Jezyk::pobierzWszystkie();

			foreach ($a_jezyki as $idJezyka => $skrotJezyka) {
				$rekord                      = array();
				$rekord["kategoria_id"]      = (int)$this->id;
				$rekord["jezyk_id"]          = (int)$idJezyka;
				$rekord["kategoria_nazwa"]   = $this->nazwa[$idJezyka];
				$rekord["kategoria_tresc"]   = $this->tresc[$idJezyka];
				$rekord["kategoria_miejsce"] = $this->miejsce[$idJezyka];
				$rekord["kategoria_aktywna"] = $this->aktywna[$idJezyka];

				$queryCount = $db->query('SELECT * FROM kategorie_produkty_opisy WHERE kategoria_id = ' . (int)$this->id . ' AND jezyk_id = ' . (int)$idJezyka . '');
				if ($queryCount->RecordCount() > 0) {
					$db->update('kategorie_produkty_opisy', $rekord, 'kategoria_id = ' . (int)$this->id . ' AND jezyk_id = ' . (int)$idJezyka . '');
				} else {
					$db->insert('kategorie_produkty_opisy', $rekord);
				}
			}

			Model_Tag_Service::saveTagIdsForObject($this, $this->tagIds);
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function usun($id_in)
	{
		$db = Core_DB::instancja();
		$db->query('DELETE FROM kategorie_produkty WHERE kategoria_id = ' . (int)$id_in);
		$db->query('DELETE FROM kategorie_produkty_opisy WHERE kategoria_id = ' . (int)$id_in);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function fromArray(array $r)
	{
		$this->id = (int)$r['id'];

		if (isset($r['id_nadrzedna'])) {
			$this->id_nadrzedna = (int)$r['id_nadrzedna'];
		}
		if (isset($r['szablon_id'])) {
			$this->szablon_id = $r['szablon_id'];
		}
		if (isset($r['kolor_tekst'])) {
			$this->kolor_tekst = $r['kolor_tekst'];
		}
		if (isset($r['kolor_tlo'])) {
			$this->kolor_tlo = $r['kolor_tlo'];
		}
		if (isset($r['view_type'])) {
			$this->view_type = $r['view_type'];
		}

		if (isset($r['nazwa']) && is_array($r['nazwa'])) {
			foreach ($r['nazwa'] as $jezykId => $wartosc) {
				$this->nazwa[$jezykId] = stripslashes($wartosc);
			}
		}

		if (isset($r['tresc']) && is_array($r['tresc'])) {
			foreach ($r['tresc'] as $jezykId => $wartosc) {
				$this->tresc[$jezykId] = $wartosc;
			}
		}

		if (isset($r['aktywna']) && is_array($r['aktywna'])) {
			foreach ($r['aktywna'] as $jezykId => $wartosc) {
				$this->aktywna[$jezykId] = (int)$wartosc;
			}
		}

		if (isset($r['miejsce']) && is_array($r['miejsce'])) {
			foreach ($r['miejsce'] as $jezykId => $wartosc) {
				$this->miejsce[$jezykId] = (int)$wartosc;
			}
		}

		if (isset($r['tag'])) {
			$this->tagIds = array();
			foreach ($r['tag'] as $tagId => $isConnected) {
				if ((int)$isConnected > 0) {
					$this->tagIds[] = $tagId;
				}
			}
		}
		$this->tags = Model_Tag_Service::getTags($this->tagIds);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function validate()
	{
		$errors = array();

		return $errors;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	function zwrocIdNadrzednych($id)
	{
		$db = Core_DB::instancja();

		$sql_nadrzedne    = 'SELECT kategoria_id AS id, kategoria_id_nadrzedna AS id_nad FROM kategorie_produkty WHERE kategoria_id = ' . (int)$id . ' LIMIT 1';
		$result_nadrzedne = $db->get_row($sql_nadrzedne);

		if (count($result_nadrzedne) > 0) {
			if ($result_nadrzedne['id_nad'] != 0) {
				$this->zwrocIdNadrzednych($result_nadrzedne['id_nad']);
			}
			$this->nadrzedne[] = $result_nadrzedne['id'];
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function getCategoryFullPathName($id, $jezykId = 1)
	{
		$path    = '';
		$db      = Core_DB::instancja();
		$sql     = 'SELECT
                kp.kategoria_id AS id,
                kp.kategoria_id_nadrzedna AS id_nad,
                kpo.kategoria_nazwa AS nazwa
            FROM
                kategorie_produkty kp
            LEFT JOIN
                kategorie_produkty_opisy kpo
            ON
                kp.kategoria_id = kpo.kategoria_id
            WHERE
                kp.kategoria_id = ' . (int)$id . '
                AND kpo.jezyk_id = ' . (int)$jezykId . '
            LIMIT 1';
		$catInfo = $db->get_row($sql);
		if (isset($catInfo['nazwa'])) {
			$path = $catInfo['nazwa'];
			if (count($catInfo) > 0) {
				if ((int)$catInfo['id_nad'] > 0) {
					$path = self::getCategoryFullPathName($catInfo['id_nad'], $jezykId) . '/' . $path;
				}
			}
		}
		return $path;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function filtruj()
	{
		$db = Core_DB::instancja();

		$this->rekordy = array();

		if ($this->filtr_strona < 1) {
			$this->filtr_strona = 1;
		}

		$jezykId = (int)$this->filtr_jezyk_id;
		if ($jezykId == 0) {
			$jezykId = Model_Jezyk::DEFAULT_ID;
		}

		$sql = "SELECT
		        k.kategoria_id AS id
		    FROM
		        kategorie_produkty AS k,
		        kategorie_produkty_opisy AS ko
		    WHERE
		        k.kategoria_id = ko.kategoria_id
		        AND ko.jezyk_id = '" . (int)$jezykId . "'
		    ";

		if ((int)$this->filtr_id > 0) {
			$sql .= ' AND k.kategoria_id=' . $this->filtr_id . ' ';
		}
		if ($this->filtr_nazwa != '') {
			$sql .= ' AND ko.kategoria_nazwa LIKE "%' . mysql_real_escape_string($this->filtr_nazwa) . '%" ';
		}
		if ($this->filtr_id_nadrzedna !== '') {
			$sql .= ' AND k.kategoria_id_nadrzedna = ' . (int)$this->filtr_id_nadrzedna . ' ';
		}

		if ($this->filtr_aktywna == '1') {
			$sql .= ' AND ko.kategoria_aktywna = 1 ';
		} else if ($this->filtr_aktywna == '0') {
			$sql .= ' AND ko.kategoria_aktywna = 0 ';
		}

		$sql_count = $sql;

		if ($this->filtr_sortuj_po != '') {
			$kolumna = '';
			switch ($this->filtr_sortuj_po) {
				case 'nazwa':
					$kolumna = ' ko.kategoria_nazwa ';
					break;
				case 'id':
					$kolumna = ' k.kategoria_id ';
					break;
				case 'kolejnosc':
				case 'miejsce':
					$kolumna = ' ko.kategoria_miejsce ';
					break;
				case 'aktywna':
					$kolumna = ' ko.kategoria_aktywna ';
					break;
				case 'path':
					$kolumna = ' k.path ';
					break;
				case 'pathAndKolejnosc':
					$kolumna = ' k.path, ko.kategoria_miejsce ';
					break;
				default:
					$kolumna = ' ko.kategoria_nazwa ';
					break;
			}

			$sql .= ' ORDER BY ' . $kolumna;
			if ($this->filtr_sortuj_jak != '') {
				$sql .= ' ' . $this->filtr_sortuj_jak;
			}
		}

		if ($this->filtr_maks != '') {
			$sql .= ' LIMIT ' . (int)$this->filtr_maks . '';
		} else if ($this->filtr_ilosc_wynikow != '' && $this->filtr_strona != '') {
			$sql .= ' LIMIT ' . ($this->filtr_ilosc_wynikow * $this->filtr_strona - $this->filtr_ilosc_wynikow) . ', ' . (int)$this->filtr_ilosc_wynikow . '';
		}

		$this->sql = $sql;

		$result_kategorie = $db->query($sql);
		foreach ($result_kategorie as $row) {
			$this->rekordy[] = $row['id'];
		}

		$result_count         = $db->query($sql_count);
		$this->ilosc_rekordow = $result_count->RecordCount();

		return $this->rekordy;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function pobierzUprawnieniaDlaGrup($kategoriaId)
	{
		$db        = Core_DB::instancja();
		$result    = array();
		$sql       = 'SELECT
				ag.grupa_id AS id ,
				ag.grupa_nazwa AS nazwa,
				kpgu.uprawnienie_stan AS stan
			FROM administratorzy_grupy ag
			LEFT JOIN kategorie_produkty_grupy_uprawnienia kpgu
			ON
				ag.grupa_id = kpgu.grupa_id
				AND kpgu.kategoria_id = "' . (int)$kategoriaId . '"
			';
		$resultSql = $db->query($sql);
		foreach ($resultSql as $row) {
			$result[] = $row;
		}
		return $result;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function pobierzUprawnienieDlaAdministratora($kategoriaId, $administratorId)
	{
		$result = false;
		$db     = Core_DB::instancja();
		if ((int)$administratorId > 0) {
			$o_admin = new Model_Administrator($administratorId);

			$sql                = 'SELECT
				kpgu.uprawnienie_stan AS stan
			FROM kategorie_produkty_grupy_uprawnienia kpgu
			WHERE
				kpgu.kategoria_id = "' . (int)$kategoriaId . '"
				AND kpgu.grupa_id = "' . (int)$o_admin->grupa_id . '"
			';
			$result_uprawnienia = $db->query($sql);

			$row = $result_uprawnienia->GetArray();
			if ($row[0]['stan'] == 1) {
				$result = true;
			}
		}
		return $result;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function zapiszUprawnieniaDlaGrup($kategoriaId, array $uprawnienia)
	{
		$db = Core_DB::instancja();

		$sql = 'DELETE
			FROM `kategorie_produkty_grupy_uprawnienia`
			WHERE
				`kategoria_id` = "' . (int)$kategoriaId . '"
			';
		$db->query($sql);

		foreach ($uprawnienia as $grupaId => $stan) {
			$sql = 'INSERT INTO
				`kategorie_produkty_grupy_uprawnienia`
			SET
				`grupa_id` = "' . (int)$grupaId . '",
				`kategoria_id` = "' . (int)$kategoriaId . '",
				`uprawnienie_stan` = "' . (int)$stan . '"
			';
			$db->query($sql);
		}
	}

	public static function updateCategoriesPaths()
	{
		$paths = Model_KategoriaProdukt::getCategoriesPaths();
		foreach ($paths as $catId => $path) {
			self::updateCategoryPath($catId, $path);
		}
		return $paths;
	}

	public static function getCategoriesPaths($idNadrzedna = 0, $path = '')
	{
		$filtr                     = new Model_KategoriaProdukt();
		$filtr->filtr_id_nadrzedna = $idNadrzedna;
		$categories                = $filtr->filtruj();

		$res = array();
		foreach ($categories as $catId) {
			$cat                = new Model_KategoriaProdukt($catId);
			$fullPath           = $path . '.' . sprintf('%05d', $cat->id);
			$res['' . $cat->id] = $fullPath;
			$res                = $res + self::getCategoriesPaths($cat->id, $fullPath);
		}
		return $res;
	}

	public static function updateCategoryPath($categoryId, $path)
	{
		$db  = Core_DB::instancja();
		$sql = 'UPDATE
				`kategorie_produkty`
			SET
				`path` = "' . mysql_real_escape_string($path) . '"
		    WHERE
		        `kategoria_id` = "' . (int)$categoryId . '"
			';
		$db->query($sql);
	}

	public function getTags()
	{
		return $this->tags;
	}

	public function addTag(Model_Tag_TagEntity $tag)
	{
		$this->tags[]   = $tag;
		$this->tagIds[] = $tag->id;
	}
}
