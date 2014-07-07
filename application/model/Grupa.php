<?php
class Model_Grupa
{
	private $db = '';
	public $id = 0;
	public $nazwa = '';
	public $aktywna = 0;
	public $uprawnienia = array();

	//================================================================================
	public function __construct($id = 0)
	{
		$this->db = Core_DB::instancja();
		if ((int)$id > 0) {
			$sql           = 'SELECT * FROM administratorzy_grupy WHERE grupa_id = ' . (int)$id;
			$rekord        = $this->db->get_row($sql);
			$this->id      = $rekord['grupa_id'];
			$this->nazwa   = $rekord['grupa_nazwa'];
			$this->aktywna = $rekord['grupa_aktywna'];
		}
	}

	//================================================================================
	public function zapisz()
	{
		$rekord                  = array();
		$rekord["grupa_nazwa"]   = $this->nazwa;
		$rekord["grupa_aktywna"] = (int)$this->aktywna;
		if ((int)$this->id > 0) {
			$rekord["id"] = (int)$this->id;
			$updateSQL    = $this->db->update('administratorzy_grupy', $rekord, 'grupa_id = ' . (int)$this->id);
		} else {
			$insertSQL = $this->db->insert('administratorzy_grupy', $rekord);
			$this->id  = $this->db->last_insert_id('administratorzy_grupy');
		}

		if (count($this->uprawnienia) > 0) {
			foreach ($this->uprawnienia as $modul => $akcje) {
				foreach ($akcje as $akcja => $stan) {
					echo "<br>=>" . $modul . " => " . $akcja . " : " . $stan;

					$rekord                     = array();
					$rekord['uprawnienia_stan'] = $stan;

//					$a_dane['uprawnienia_modul_id'] = $modul;
//					$a_dane['uprawnienia_akcja'] = $akcja;
//					$a_dane['uprawnienia_grupa_id'] = (int)$thi->id;

					$warunek   = " uprawnienia_grupa_id = " . (int)$this->id . " AND uprawnienia_modul_id='" . $modul . "' AND uprawnienia_akcja='" . $akcja . "' ";
					$insertSQL = $this->db->update('administratorzy_uprawnienia', $rekord, $warunek);
				}
			}
		}
	}

	//================================================================================
	public function fromArray(array $r)
	{
		$this->id = (int)$r['id'];
		if (isset($r['nazwa'])) {
			$this->nazwa = $r['nazwa'];
		}
		if (isset($r['aktywna'])) {
			$this->aktywna = (int)$r['aktywna'];
		}
		if (isset($r['uprawnienia'])) {
			$this->uprawnienia = $r['uprawnienia'];
		}
	}

	//================================================================================
	public function validate()
	{
		$errors = array();
		if (trim($this->nazwa) == '') {
			$errors[] = array('error', 'Proszę podać nazwę');
		}
		return $errors;
	}

	//================================================================================
	public static function pobierzUprawnienia($grupa_id)
	{
		$db            = Core_DB::instancja();
		$a_uprawnienia = array();
		if ((int)$grupa_id > 0) {
			$sql_uprawnienia    = 'SELECT
						au.uprawnienia_id AS akcja_id, 
						au.uprawnienia_modul_id AS id, 
						au.uprawnienia_akcja AS akcja, 
						au.uprawnienia_stan AS stan 
					FROM
						administratorzy_uprawnienia AS au
					JOIN
						cms_moduly AS cm
					ON
					    cm.modul_id = au.uprawnienia_modul_id
					WHERE 
						au.uprawnienia_grupa_id = ' . (int)$grupa_id . '
						AND cm.modul_aktywny = 1
					ORDER BY
						cm.modul_id ASC,
						uprawnienia_akcja ASC';
			$result_uprawnienia = $db->query($sql_uprawnienia);
			foreach ($result_uprawnienia as $row) {
				$a_uprawnienia[$row['id']][$row['akcja']] = $row['stan'];
			}
		}
		return $a_uprawnienia;
	}

	//================================================================================
	public function usun()
	{
		$this->db->Execute('DELETE FROM administratorzy_grupy WHERE grupa_id = ' . (int)$this->id);
	}

	//================================================================================
	public static function pobierzWszystkie()
	{
		$db          = Core_DB::instancja();
		$result      = array();
		$sql         = 'SELECT grupa_id FROM administratorzy_grupy';
		$resultGrupy = $db->query($sql);
		foreach ($resultGrupy as $row) {
			$result[] = new Model_Grupa($row['grupa_id']);
		}
		return $result;
	}
}
