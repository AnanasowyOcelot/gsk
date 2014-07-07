<?php
class Model_Nawigacja
{
	private $db = '';
	public $id = 0;
	public $parent_id = 0;
	public $nazwa = '';
	public $url = '';
	public $modul = '';
	public $akcja = '';
	public $miejsce = '';
	public $html = '';
	public $aktywny = 0;
	//================================================================================		
	public function __construct($id = 0) {
		$this->db = Core_DB::instancja();
		if((int)$id > 0) {
			$rekord = $this->db->GetRow('SELECT * FROM nawigacja WHERE id = '.(int)$id);
			$this->fromArray($rekord);
		}
	}
	//================================================================================	
	public function zapisz() {
		
		$rekord = array();
		$rekord["parent_id"] = (int)$this->parent_id;
		$rekord["nazwa"] = $this->nazwa;		
		$rekord["modul"] = $this->modul;
		$rekord["akcja"] = $this->akcja;		
		$rekord["miejsce"] = $this->miejsce;	
		$rekord["aktywny"] = (int)$this->aktywny;
		
		$modul_id = $this->modul;
		if((int)$this->id > 0) {			
			$sql_modul = "SELECT modul AS modul_nazwa FROM nawigacja WHERE id=".$this->id;
			$modul_id = $this->db->get_one($sql_modul);
			
			$rekord["id"] = (int)$this->id;
			$insertSQL = $this->db->AutoExecute('nawigacja', $rekord, 'UPDATE', 'id = '.(int)$this->id);
		} else {
			$insertSQL = $this->db->AutoExecute('nawigacja', $rekord, 'INSERT');
			$this->id = $this->db->last_insert_id('nawigacja');
		}
		
		$sql_del = " DELETE FROM  administratorzy_uprawnienia WHERE uprawnienia_modul_id='".$modul_id."'  ";	
		$row = $this->db->query($sql_del);
				
			
		$sql_akcje = "SELECT * FROM cms_akcje";
		$akcje = $this->db->query($sql_akcje);
		
		$sql_grupy = "SELECT grupa_id FROM administratorzy_grupy";
		$grupy = $this->db->query($sql_grupy);
		
		foreach ($grupy as $index => $dane_grupa)
		{					
			foreach ($akcje as $index_akcja => $dane_akcja)
			{
				$a_dane = array();
				$a_dane['uprawnienia_grupa_id'] = $dane_grupa['grupa_id'];	
				$a_dane['uprawnienia_modul_id'] = $this->modul;	
				$a_dane['uprawnienia_akcja'] = $dane_akcja['akcja_id'];
				$a_dane['uprawnienia_stan'] = 1;
				
				$this->db->insert("administratorzy_uprawnienia",$a_dane);
			}
		}			
	}
	//================================================================================	
	public function fromArray(array $r) {
		$this->id = (int) $r['id'];
		if(isset($r['parent_id'])) $this->parent_id = (int)$r['parent_id'];
		if(isset($r['nazwa'])) $this->nazwa = $r['nazwa'];		
		if(isset($r['modul'])) $this->modul = $r['modul'];
		if(isset($r['akcja'])) $this->akcja = $r['akcja'];		
		if(isset($r['aktywny'])) $this->aktywny = (int)$r['aktywny'];
		if(isset($r['miejsce'])) $this->miejsce = (int)$r['miejsce'];
	}
	//================================================================================
	public function validate() {
		$errors = array();
		if(trim($this->nazwa) == '') {
			$errors[] =  array('error', 'Proszę podać nazwę');
		}
		return $errors;
	}
	//================================================================================
	public function usun() {
		$this->db->Execute('DELETE FROM nawigacja WHERE id = '.(int)$this->id);
	}
};
