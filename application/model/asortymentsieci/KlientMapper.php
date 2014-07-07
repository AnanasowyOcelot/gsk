<?php

class Model_AsortymentSieci_KlientMapper extends Core_Mapper
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getTable()
	{
		return 'asortyment_sieci_klienci';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDataObjectClass()
	{
		return 'Model_AsortymentSieci_KlientEntity';
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function getDescription()
	{
		return array(
			'id'    => array('id', Core_Mapper::T_INT),
			'nazwa' => array('nazwa', Core_Mapper::T_VARCHAR)
		);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function save(Model_AsortymentSieci_KlientEntity $o)
	{
		parent::save($o);

		$wymiary_p_0 = array(
			array(
				'szerokosc' => '',
				'wysokosc' => '',
				'typ' => 'png'
			),
			array(
				'szerokosc' => '110',
				'wysokosc' => '90',
				'typ' => 'png'
			),
			array(
				'szerokosc' => '618',
				'wysokosc' => '506',
				'typ' => 'png'
			)
		);
		$postedFiles = $o->getPostedFiles();
		//Core_Narzedzia::drukuj($postedFiles);
		foreach ($postedFiles as $nazwa => $dane) {
			if ($dane['tmp_name'] != "") {
				$path      = $dane['tmp_name'];
				//$pathInfo  = pathinfo($dane['name']);
				$extension = 'png'; //$pathInfo['extension'];
				$plik_nazwa = $o->id . '.' . $extension;
				if ($nazwa == "p_0") {
					$katalog_zdj   = Core_Config::get('images_path') . $this->getDataObjectClass() . '/' . $nazwa;
					foreach ($wymiary_p_0 as $nr => $wymiar) {
						$katalogWymiaru = $katalog_zdj . '/' . $nr;

						if (!file_exists($katalogWymiaru)) {
							mkdir($katalogWymiaru, 0777, true);
						}
						$sciezka = $katalogWymiaru . '/' . $plik_nazwa;
						//Core_Narzedzia::drukuj($sciezka);
						Core_Zdjecie::tworz_miniaturke($path, $sciezka, $wymiar['szerokosc'], $wymiar['wysokosc'], $wymiar['typ']);
					}
				}
			}
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	/**
	 * @param $nazwa
	 * @return null|Model_AsortymentSieci_KlientEntity
	 */
	public function findOneByName($nazwa)
	{
		$db        = Core_DB::instancja();
		$sqlSelect = 'SELECT *
        	FROM `' . self::escape($this->getTable()) . '`
        	WHERE nazwa = "' . mysql_real_escape_string($nazwa) . '"
        	LIMIT 1';
		$result    = $db->get_row($sqlSelect);
		if (count($result) > 0) {
			$object = $this->buildObject($result);
			return $object;
		} else {
			return null;
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	/**
	 * @param int $klientId
	 * @return array
	 */
	public function getValues($klientId)
	{
		$db        = Core_DB::instancja();
		$sqlSelect = 'SELECT *
        	FROM `asortyment_sieci_produkty_klienci`
        	WHERE klient_id = "' . (int)$klientId . '"
        	';
		$rows      = $db->query($sqlSelect);
		$result    = array();
		foreach ($rows as $row) {
			$result[] = $row;
		}
		return $result;
	}
}
