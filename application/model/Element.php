<?php
class Model_Element
{
	private $db = '';
	public $id = 0;
	public $nazwa = '';
	public $klucz = '';
	public $aktywny = '';
	
	public $table_name = 'page_elementy';
	public $table_prefix = 'pe';
	//================================================================================
	public function __construct($id = 0) 
	{
		$this->db = Core_DB::instancja();
		
		if((int)$id > 0) 
		{
			$sql = 'SELECT * FROM '.$this->table_name.' WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$rekord = $this->db->get_row($sql);
			
			$this->id = $rekord[$this->table_prefix.'_id'];
			$this->nazwa = $rekord[$this->table_prefix.'_nazwa'];
			$this->klucz = $rekord[$this->table_prefix.'_klucz'];
			$this->aktywny = $rekord[$this->table_prefix.'_aktywny'];
		}
	}
	//================================================================================
	public function zapisz() 
	{
		$rekord = array();
		$rekord[$this->table_prefix."_nazwa"] = $this->nazwa;
		$rekord[$this->table_prefix."_klucz"] = $this->klucz;
		$rekord[$this->table_prefix."_aktywny"] = (int)$this->aktywny;
		
		if((int)$this->id > 0) {
			$rekord["id"] = (int)$this->id;
			$updateSQL = $this->db->update($table_name, $rekord, $this->table_prefix.'_id = '.(int)$this->id);
		} else {
			$insertSQL = $this->db->insert($this->table_name, $rekord);
			$this->id = $this->db->last_insert_id($this->table_name);
		}		
	}
	//================================================================================
	public function fromArray(array $r) 
	{
		$this->id = (int) $r['id'];
		if(isset($r['nazwa'])) $this->nazwa = $r['nazwa'];
		if(isset($r['klucz'])) $this->nazwa = $r['klucz'];
		if(isset($r['aktywny'])) $this->aktywna = (int)$r['aktywny'];
		
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
	public function pobierzElementy()
	{
		$db = Core_DB::instancja();
		$a_elementy = array();		
		
		$sql_elementy = 'SELECT
					pe_id AS id, 
					pe_nazwa AS nazwa, 
					pe_klucz AS klucz, 
					pe_aktywny AS aktywny 
				FROM 
					'.$this->table_name.'
					
				WHERE 
					pe_aktywny = 1
				ORDER BY
					nazwa
				ASC ';
				
		$result_elementy = $db->query($sql_elementy);
		
		foreach($result_elementy as $row)
		{
			$a_elementy[$row['klucz']]['id']=$row['id'];
			$a_elementy[$row['klucz']]['nazwa']=$row['nazwa'];
		}
		
		return $a_elementy;
	}
	//================================================================================
	public function usun() {
		$this->db->Execute('DELETE FROM '.$this->table_name.' WHERE '.$this->table_prefix.'_id = '.(int)$this->id);
	}
};
