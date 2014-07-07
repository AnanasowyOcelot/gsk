<?php

class Model_Uzytkownik
{
	private $db = '';

	public $id = 0;

	public $nazwa = '';
	public $email = '';
	public $opis = '';
	public $aktywny = 0;

	public function __construct($id = 0) {
		$this->db = Core_DB::instancja();

		if((int)$id > 0) {
			
			
			$rekord = $this->db->get_row('SELECT * FROM uzytkownik WHERE id = '.(int)$id);
			$this->fromArray($rekord);
		}
	}

	public function zapisz() {
		$rekord = array();

		$rekord["nazwa"] = $this->nazwa;
		$rekord["email"] = $this->email;
		$rekord["aktywny"] = (int)$this->aktywny;
		$rekord["opis"] = $this->opis;

		if((int)$this->id > 0) {
			$rekord["id"] = (int)$this->id;
			$insertSQL = $this->db->AutoExecute('uzytkownik', $rekord, 'UPDATE', 'id = '.(int)$this->id);
		} else {
			$insertSQL = $this->db->AutoExecute('uzytkownik', $rekord, 'INSERT');
			$this->id = $this->db->Insert_ID();
		}
	}

	public function fromArray(array $r) {
		$this->id = (int) $r['id'];

		if(isset($r['nazwa'])) $this->nazwa = $r['nazwa'];
		if(isset($r['email'])) $this->email = $r['email'];
		if(isset($r['aktywny'])) $this->aktywny = (int)$r['aktywny'];
		if(isset($r['opis'])) $this->opis = $r['opis'];
	}
};
