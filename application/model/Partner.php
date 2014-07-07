<?php

class Model_Partner
{

	public $id = '';	
	public $logo = '';
	
	public $nazwa = array();
	public $link = array();	
	public $aktywny = array();
	public $miejsce = array();
	
	public $title = array();
	public $description = array();
	public $keywords = array();
	public $errors = array();
	public $pliki = array();
	//========= parametry filtrowania =============
	public $filtr_id= '';		
	public $filtr_aktywny = '';
	public $filtr_nazwa = '';		
	public $filtr_link = '';	
	public $filtr_jezyk_id = '';
	public $filtr_sortuj_po =  '';
	public $filtr_sortuj_jak =  '';
	public $filtr_strona = '';
	public $filtr_ilosc_wynikow ='';
	public $filtr_maks = '';
	
	public $table_prefix = "partner";
	public $main_table = "partnerzy";
	public $opis_table = "partnerzy_opisy";

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function __construct($id = 0)
	{
		if((int)$id > 0)
		{
			$this->pobierz($id);
		}		
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function setFiles($pliki_in)
	{
		$this->pliki = $pliki_in;
	}


	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapisz()
	{
		$db = Core_DB::instancja();

		$plik_nazwa = '';
		if(count($this->pliki)>0)
		{
			$katalog_zdj = Core_Config::get('images_path').'partnerzy/';

			foreach ($this->pliki as $nazwa => $dane)
			{
				
				if($dane['tmp_name']!="")
				{
					$path = $dane['tmp_name'];
	
					//====================================
					$plik_nazwa = time().'_'.Core_Narzedzia::usunZnakiNiedozwolonePliki($dane['name']);
					//====================================
					
					$szerokosc_oryg = 0;
					$wysokosc_oryg = 0;
					$sciezka = $katalog_zdj.'0/'.$plik_nazwa;
					$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $szerokosc_oryg, $wysokosc_oryg);
				}
			}
		}

		$rekord = array();
		$rekord[$this->table_prefix."_data_dodania"] = date("Y-m-d");
		if($plik_nazwa!='')
		{			
			$rekord[$this->table_prefix."_logo"] = $plik_nazwa;
		}
			
		if((int)$this->id > 0) {			
			$resultSQL = $db->update($this->main_table, $rekord, $this->table_prefix.'_id = '.(int)$this->id);
		} else {
			$resultSQL = $db->insert($this->main_table, $rekord);
			$this->id = $db->last_insert_id($this->main_table);//Insert_ID();
		}

		
		if((int)$this->id > 0) {
			$a_jezyki = Model_Jezyk::pobierzWszystkie();

			foreach($a_jezyki as $idJezyka => $skrotJezyka) {
				$rekord = array();
				$rekord[$this->table_prefix."_id"] = (int)$this->id;
				$rekord["jezyk_id"] = (int)$idJezyka;
				$rekord[$this->table_prefix."_nazwa"] = $this->nazwa[$idJezyka];
				$rekord[$this->table_prefix."_link"] = $this->link[$idJezyka];								
				$rekord[$this->table_prefix."_miejsce"] = $this->miejsce[$idJezyka];
				$rekord[$this->table_prefix."_aktywny"] = $this->aktywny[$idJezyka];
				
				
				$queryCount = $db->query('SELECT * FROM '.$this->opis_table.'  WHERE '.$this->table_prefix.'_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				if( $queryCount->RecordCount() > 0 ) {
					$resultSQL = $db->update($this->opis_table, $rekord, $this->table_prefix.'_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				} else {
					$resultSQL = $db->insert($this->opis_table, $rekord);
				}
			}			
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function usun($id)
	{
		$db = Core_DB::instancja();
		$komunikaty = array();
		
		if($id > 0) {
			
			$sql_del = 'DELETE FROM '.$this->main_table.' WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$db->Execute($sql_del);			
			
			$sql_del = 'DELETE FROM '.$this->opis_table.'  WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$db->Execute($sql_del);					
			
			$komunikaty[] = array('ok', 'Rekord o id = ' . (int)$id . ' został usunięty.');
		}
		
		return $komunikaty;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierz($id)
	{
		$db = Core_DB::instancja();

		if((int)$id > 0)
		{
			$sql = 'SELECT * FROM '.$this->main_table.' WHERE '.$this->table_prefix.'_id = '.(int)$id.' LIMIT 1';
			$result = $db->get_row($sql);

			if(count($result) > 0)
			{
				$this->id = (int)$result[$this->table_prefix.'_id'];
				$this->logo = $result[$this->table_prefix."_logo"];
								
				$sql_opis = 'SELECT * FROM '.$this->opis_table.' WHERE '.$this->table_prefix.'_id = '.(int)$this->id;
				$result_opis = $db->query($sql_opis);
				foreach($result_opis as $opis)
				{	
					$this->nazwa[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_nazwa']);
					$this->link[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_link']);					
					$this->miejsce[$opis['jezyk_id']] = $opis[$this->table_prefix.'_miejsce'];
					$this->aktywny[$opis['jezyk_id']] = $opis[$this->table_prefix.'_aktywny'];
				}
			}
			else
			{
				$this->errors[] = 'Nie znaleziono rekordu o '.$id.'.';
			}
		}

		if(count($this->errors) > 0){
			return false;
		}
		else return true;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function validate() {
		$errors = array();

		return $errors;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function fromArray(array $r)
	{
		$this->id = (int) $r['id'];
		
		if(isset($r['nazwa']) && is_array($r['nazwa'])) {
			foreach($r['nazwa'] as $jezykId => $wartosc) {
				$this->nazwa[$jezykId] = stripslashes($wartosc);
			}
		}
						
		if(isset($r['link']) && is_array($r['link'])) {
			foreach($r['link'] as $jezykId => $wartosc) {
				$this->link[$jezykId] = stripslashes($wartosc);
			}
		}		
		
		
		if(isset($r['aktywny']) && is_array($r['aktywny'])) {
			foreach($r['aktywny'] as $jezykId => $wartosc) {
				$this->aktywny[$jezykId] = (int)$wartosc;
			}
		}		
		
		if(isset($r['miejsce']) && is_array($r['miejsce'])) {
			foreach($r['miejsce'] as $jezykId => $wartosc) {
				$this->miejsce[$jezykId] = (int)$wartosc;
			}
		}	
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function filtrujRekordy()
	{

		$db = Core_DB::instancja();

		$sql = 'SELECT
				t.'.$this->table_prefix.'_id AS id
			FROM
				'.$this->main_table.' AS t, 
				'.$this->opis_table.' AS too
			WHERE
				t.'.$this->table_prefix.'_id = too.'.$this->table_prefix.'_id ';

		if($this->filtr_strona < 1) $this->filtr_strona = 1;		
		if($this->filtr_id != '') $sql .= ' AND too.'.$this->table_prefix.'_id='.(int)$this->filtr_id;
		if($this->filtr_jezyk_id != '') $sql .= ' AND too.jezyk_id='.(int)$this->filtr_jezyk_id;
		if($this->filtr_nazwa != '') $sql .= ' AND too.'.$this->table_prefix.'_nazwa LIKE "%'.$this->filtr_nazwa.'%" ';		
	
		if($this->filtr_link != '') $sql .= ' AND too.'.$this->table_prefix.'_link LIKE "%'.$this->filtr_link.'%" ';
		
		if($this->filtr_aktywny == '1'){
			$sql .= ' AND too.'.$this->table_prefix.'_aktywny = 1 ';
		}
		else if($this->filtr_aktywny == '0') {
			$sql .= ' AND too.'.$this->table_prefix.'_aktywny = 0 ';
		}


		$sql_count = $sql;

		if($this->filtr_sortuj_po != '')
		{
			$kolumna = '';
			switch ($this->filtr_sortuj_po)
			{				
				case 'id':
					$kolumna = ' t.'.$this->table_prefix.'_id ';
					break;
				case 'nazwa':
					$kolumna = ' too.'.$this->table_prefix.'_nazwa ';
					break;
				case 'url':
					$kolumna = ' too.'.$this->table_prefix.'_url ';
					break;
				case 'kolejnosc':
					$kolumna = ' too.'.$this->table_prefix.'_miejsce ';
					break;				
				case 'aktywna':
					$kolumna = ' too.'.$this->table_prefix.'_aktywna ';
					break;
				default:
					$kolumna = ' t.'.$this->table_prefix.'_id ';
					break;
			}
						
			$sql .= ' ORDER BY '.$kolumna;
			if($this->filtr_sortuj_jak != '') $sql .= ' '.$this->filtr_sortuj_jak;
		}
		else 
		{
			$sql .= ' ORDER BY t.'.$this->table_prefix.'_id DESC';
		}

		if($this->filtr_maks != '')
		{
			$sql .= ' LIMIT '.(int)$this->filtr_maks.'';
		}
		else if($this->filtr_ilosc_wynikow != '' && $this->filtr_strona != '')
		{
			$sql .= ' LIMIT '.($this->filtr_ilosc_wynikow * $this->filtr_strona - $this->filtr_ilosc_wynikow).', '.(int)$this->filtr_ilosc_wynikow.'';
		}
			
		$result_aktualnosci = $db->query($sql);
		foreach($result_aktualnosci as $row)
		{
			$this->rekordy[] = $row['id'];
		}
	

		$result_count = $db->query($sql_count);
		$this->ilosc_rekordow =$result_count->RecordCount();
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	
}
