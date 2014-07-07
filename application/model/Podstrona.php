<?php

class Model_Podstrona
{
	public $id = '';
	public $id_nadrzedna = '';
	
	public $szablon_id = '';
	public $szablon_glowny_id = '';
	
	public $url = array();
	public $link = array();
	public $nazwa = array();
	public $tytul = array();
	public $modul = array();
	public $elementy_podstrona = array();
	public $tresc = array();
	public $menu_gora = array();
	public $menu_dol = array();
	public $menu_lewa = array();
	public $miejsce = array();
	public $aktywna = array();
	public $nadrzedne = array();
	//public $galeria_podstrony = array();
	public $errors = array();

	//========= parametry filtrowania =============
	public $filtr_id = '';
	public $filtr_mapa_serwisu = '';
	public $filtr_menu_gora = '';
	public $filtr_menu_dol = '';
	public $filtr_menu_lewa = '';
	public $filtr_id_nadrzedna = '';
	public $filtr_aktywna = '';
	public $filtr_nazwa = '';
	public $filtr_modul = '';
	public $filtr_url = '';
	public $filtr_link = '';
	public $filtr_tresc = '';
	public $filtr_jezyk_id = '';
	public $filtr_sortuj_po =  '';
	public $filtr_sortuj_jak =  '';
	public $filtr_strona = '';
	public $filtr_ilosc_wynikow ='';
	public $filtr_maks = '';


	public $rekordy = array();
	public $ilosc_rekordow = 0;

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function __construct($id = 0)
	{
		if((int)$id > 0)
		{
			$this->pobierz($id);
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzElementyPodstrony()
	{
		$db = Core_DB::instancja();
		
		if((int)$this->id>0)
		{
			$sql_elementy = "SELECT
						pep.element_id AS el_id,
				                        	pe.pe_klucz AS klucz,
				                        	pe.pe_nazwa AS el_nazwa,
				                        	pep.element_parametr AS parametr,
				                        	pep.element_tpl_nazwa AS tpl_nazwa
				                    FROM 
				                        	page_elementy_podstrony AS pep, 
				                        	page_elementy AS pe 
				                    WHERE 
				                        	pe.pe_id = pep.element_id 
				                        	AND pep.podstrona_id=".$this->id."
				                     ORDER BY
				                     	pep.element_kolejnosc ASC ";
			
			//echo $sql_elementy;
				
			$result_podstrona_elementy = $db->query($sql_elementy);
	
			$a_elementy = array();
			if($result_podstrona_elementy->_numOfRows>0) 
			{
				foreach($result_podstrona_elementy as $element) 
				{
					$this->elementy_podstrona[$element['tpl_nazwa']]['element_id'] = $element['el_id'];
					$this->elementy_podstrona[$element['tpl_nazwa']]['element_nazwa'] = $element['el_nazwa'];
					$this->elementy_podstrona[$element['tpl_nazwa']]['parametr'] = $element['parametr'];
				}
			}
		}
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierz($id)
	{
		$db = Core_DB::instancja();
		if((int)$id > 0)
		{
			$sql = 'SELECT * FROM podstrony WHERE podstrona_id = '.(int)$id.' LIMIT 1';
			$result_podstrona = $db->get_row($sql);

			if(count($result_podstrona) > 0 )
			{
				$this->id = (int)$result_podstrona['podstrona_id'];
				$this->id_nadrzedna = $result_podstrona['podstrona_id_nadrzedna'];
				$this->szablon_id = $result_podstrona['podstrona_szablon_id'];
				$this->szablon_glowny_id = $result_podstrona['glowny_szablon_id'];

				$this->zwrocIdNadrzednych($this->id);
				$this->pobierzElementyPodstrony();

				$sql_opis = 'SELECT * FROM podstrony_opisy WHERE podstrona_id = '.(int)$this->id;
				$result_podstrona_opis = $db->query($sql_opis);

				foreach($result_podstrona_opis as $opis_row)
				{
					$this->url[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_url']);
					$this->link[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_link']);
					$this->nazwa[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_nazwa']);
					$this->tytul[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_tytul']);
					$this->modul[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_modul']);
					//$this->nazwa_title[$opis_row['jezyk_id']] = str_replace('"','',stripslashes($opis_row['podstrona_nazwa']));
					//$this->nazwa_mp[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_nazwa_mp']);
					//$this->skrot[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_skrot']);
					$this->tresc[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_tresc']);
					//$this->title[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_title']);
					//$this->description[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_description']);
					//$this->keywords[$opis_row['jezyk_id']] = stripslashes($opis_row['podstrona_keywords']);
					//$this->przekierowanie[$opis_row['jezyk_id']] = $opis_row['podstrona_przekierowanie'];
					//$this->mapa_serwisu[$opis_row['jezyk_id']] = $opis_row['podstrona_mapa_serwisu'];
					$this->menu_gora[$opis_row['jezyk_id']] = $opis_row['podstrona_menu_gora'];
					$this->menu_dol[$opis_row['jezyk_id']] = $opis_row['podstrona_menu_dol'];
					$this->menu_lewa[$opis_row['jezyk_id']] = $opis_row['podstrona_menu_lewa'];
					$this->miejsce[$opis_row['jezyk_id']] = $opis_row['podstrona_miejsce'];
					$this->aktywna[$opis_row['jezyk_id']] = $opis_row['podstrona_aktywna'];

					$j = new Model_Jezyk($opis_row['jezyk_id']);

					$link_tpl = Core_Config::get('www_url').$j->skrot.'/'.($this->url[$opis_row['jezyk_id']]?$this->url[$opis_row['jezyk_id']].'':'');
					if($this->link[$opis_row['jezyk_id']]!="")
					{
						$link_tpl = $this->link[$opis_row['jezyk_id']];
					}
					$this->adres[$opis_row['jezyk_id']] = $link_tpl;
				}
			}
			else
			{
				$this->errors[] = 'Nie odnaleziono podstrony o nr id: '.$id.'.';
			}
		}
		if(count($this->errors) > 0) return false;
		else return true;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapisz()
	{
		$db = Core_DB::instancja();

		$rekord = array();
		$rekord["podstrona_id_nadrzedna"] = (int)$this->id_nadrzedna;
		$rekord["podstrona_szablon_id"] = $this->szablon_id;

		if((int)$this->id > 0) {
			$rekord["podstrona_id"] = (int)$this->id;
			$resultSQL = $db->update('podstrony', $rekord, 'podstrona_id = '.(int)$this->id);
		} else {
			$resultSQL = $db->insert('podstrony', $rekord);
			$this->id = $db->last_insert_id('podstrony');//Insert_ID();
		}

		if((int)$this->id > 0) {
			$a_jezyki = Model_Jezyk::pobierzWszystkie();

			foreach($a_jezyki as $idJezyka => $skrotJezyka) {
				$rekord = array();
				$rekord["podstrona_id"] = (int)$this->id;
				$rekord["jezyk_id"] = (int)$idJezyka;
				$rekord["podstrona_url"] = $this->url[$idJezyka];
				$rekord["podstrona_link"] = $this->link[$idJezyka];
				$rekord["podstrona_nazwa"] = $this->nazwa[$idJezyka];
				$rekord["podstrona_tytul"] = $this->tytul[$idJezyka];
				$rekord["podstrona_modul"] = $this->modul[$idJezyka];
				$rekord["podstrona_tresc"] = $this->tresc[$idJezyka];
				$rekord["podstrona_menu_gora"] = $this->menu_gora[$idJezyka];
				$rekord["podstrona_menu_dol"] = $this->menu_dol[$idJezyka];
				$rekord["podstrona_menu_lewa"] = $this->menu_lewa[$idJezyka];
				$rekord["podstrona_miejsce"] = $this->miejsce[$idJezyka];
				$rekord["podstrona_aktywna"] = $this->aktywna[$idJezyka];

				$queryCount = $db->query('SELECT * FROM podstrony_opisy WHERE podstrona_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				if( $queryCount->RecordCount() > 0 ) {
					$resultSQL = $db->update('podstrony_opisy', $rekord, 'podstrona_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				} else {
					$resultSQL = $db->insert('podstrony_opisy', $rekord);
				}
			}
		}
		
		if(count($this->elementy_podstrona)>0)
		{
			$sql_clear = "DELETE FROM page_elementy_podstrony WHERE podstrona_id=".$this->id;
			$db->query($sql_clear);
			
			foreach ($this->elementy_podstrona as $index => $dane_elementu)
			{
				$dane_elementu['podstrona_id'] = $this->id;
				$dane_elementu['podstrona_kolejnosc'] = 0;
				$db->insert("page_elementy_podstrony",$dane_elementu);
			}
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public static function usun($id_in)
	{
		$db = Core_DB::instancja();
		$db->query('DELETE FROM podstrony WHERE podstrona_id = '.(int)$id_in);
		$db->query('DELETE FROM podstrony_opisy WHERE podstrona_id = '.(int)$id_in);
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function fromArray(array $r)
	{
		$this->id = (int) $r['id'];

		if(isset($r['id_nadrzedna'])) $this->id_nadrzedna = (int)$r['id_nadrzedna'];
		if(isset($r['szablon_id'])) $this->szablon_id = $r['szablon_id'];
		if(isset($r['elementy_podstrona'])) $this->elementy_podstrona = $r['elementy_podstrona'];

		if(isset($r['nazwa']) && is_array($r['nazwa'])) {
			foreach($r['nazwa'] as $jezykId => $wartosc) {
				$this->nazwa[$jezykId] = stripslashes($wartosc);
			}
		}
		
		if(isset($r['tytul']) && is_array($r['tytul'])) {
			foreach($r['tytul'] as $jezykId => $wartosc) {
				$this->tytul[$jezykId] = stripslashes($wartosc);
			}
		}

		if(isset($r['url']) && is_array($r['url'])) {
			foreach($r['url'] as $jezykId => $wartosc) {
				//$this->url[$jezykId] = $wartosc;
				
				if(trim($wartosc) != '') {
					$this->url[$jezykId]  = Core_Narzedzia::usunZnakiNiedozwolone(trim(stripslashes($wartosc)));
				}
				else {
					$this->url[$jezykId] = Core_Narzedzia::usunZnakiNiedozwolone(trim(stripslashes($this->nazwa[$jezykId])));
				}		
			}
		}

		if(isset($r['modul']) && is_array($r['modul'])) {
			foreach($r['modul'] as $jezykId => $wartosc) {
				$this->modul[$jezykId] = $wartosc;
			}
		}
		
		if(isset($r['link']) && is_array($r['link'])) {
			foreach($r['link'] as $jezykId => $wartosc) {
				$this->link[$jezykId] = $wartosc;
			}
		}

		if(isset($r['tresc']) && is_array($r['tresc'])) {
			foreach($r['tresc'] as $jezykId => $wartosc) {
				$this->tresc[$jezykId] = $wartosc;
			}
		}

		if(isset($r['menu_gora']) && is_array($r['menu_gora'])) {
			foreach($r['menu_gora'] as $jezykId => $wartosc) {
				$this->menu_gora[$jezykId] = (int)$wartosc;
			}
		}

		if(isset($r['menu_dol']) && is_array($r['menu_dol'])) {
			foreach($r['menu_dol'] as $jezykId => $wartosc) {
				$this->menu_dol[$jezykId] = (int)$wartosc;
			}
		}

		if(isset($r['menu_lewa']) && is_array($r['menu_lewa'])) {
			foreach($r['menu_lewa'] as $jezykId => $wartosc) {
				$this->menu_lewa[$jezykId] = (int)$wartosc;
			}
		}

		if(isset($r['aktywna']) && is_array($r['aktywna'])) {
			foreach($r['aktywna'] as $jezykId => $wartosc) {
				$this->aktywna[$jezykId] = (int)$wartosc;
			}
		}
		
		if(isset($r['miejsce']) && is_array($r['miejsce'])) {
			foreach($r['miejsce'] as $jezykId => $wartosc) {
				$this->miejsce[$jezykId] = (int)$wartosc;
			}
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzModul($jezyk_id)
	{
		return  $this->modul[$jezyk_id];
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function validate() {
		$errors = array();

		return $errors;
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzPrzezUrl($jezyk_id,$url)
	{
		$db = Core_DB::instancja();

		$sql = 'SELECT podstrona_id AS id FROM podstrony_opisy WHERE podstrona_url = "'.trim($url).'" AND jezyk_id = '.(int)$jezyk_id.'';
		$rekord = $db->get_row($sql);

		if(count($rekord) == 0)
		{
			$this->errors[] = 'Nie ma takiej podstrony';
		}
		else if(count($rekord) == 1)
		{
			$this->pobierz($rekord['id']);
		}

	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	function zwrocIdNadrzednych($id)
	{
		$db = Core_DB::instancja();

		$sql_nadrzedne = 'SELECT podstrona_id AS id, podstrona_id_nadrzedna AS id_nad FROM podstrony WHERE podstrona_id = '.(int)$id.' LIMIT 1';
		$result_nadrzedne = $db->get_row($sql_nadrzedne);

		if(count($result_nadrzedne) > 0)
		{
			if($result_nadrzedne['id_nad'] != 0)
			{
				$this->zwrocIdNadrzednych($result_nadrzedne['id_nad']);
			}
			$this->nadrzedne[] = $result_nadrzedne['id'];
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function filtrujPodstrony()
	{
		$db = Core_DB::instancja();
        
        $this->rekordy = array();

		if($this->filtr_strona < 1) $this->filtr_strona = 1;

		$sql = "SELECT p.podstrona_id AS id FROM podstrony AS p, podstrony_opisy AS po WHERE p.podstrona_id = po.podstrona_id AND po.jezyk_id = '".$this->filtr_jezyk_id."' ";

		if((int)$this->filtr_id >0) $sql .= ' AND p.podstrona_id='.$this->filtr_id.' ';
		if($this->filtr_nazwa != '') $sql .= ' AND po.podstrona_nazwa LIKE "%'.mysql_real_escape_string($this->filtr_nazwa).'%" ';
		if($this->filtr_modul != '') $sql .= ' AND po.podstrona_modul LIKE "%'.mysql_real_escape_string($this->filtr_modul).'%" ';
		if($this->filtr_url != '') $sql .= ' AND po.podstrona_url LIKE "%'.mysql_real_escape_string($this->filtr_url).'%" ';
		if($this->filtr_tresc != '') $sql .= ' AND po.podstrona_tresc LIKE "%'.mysql_real_escape_string($this->filtr_tresc).'%" ';
		if($this->filtr_mapa_serwisu == 1) $sql .= ' AND po.podstrona_mapa_serwisu = 1 ';
		if($this->filtr_menu_gora == 1) $sql .= ' AND po.podstrona_menu_gora = 1 ';
		if($this->filtr_menu_dol == 1) $sql .= ' AND po.podstrona_menu_dol = 1 ';
		if($this->filtr_menu_lewa == 1) $sql .= ' AND po.podstrona_menu_lewa = 1 ';
		if($this->filtr_id_nadrzedna !== '') $sql .= ' AND p.podstrona_id_nadrzedna = '.(int)$this->filtr_id_nadrzedna.' ';
		
		if($this->filtr_aktywna == '1'){
			$sql .= ' AND po.podstrona_aktywna = 1 ';
		}
		else if($this->filtr_aktywna == '0') {
			$sql .= ' AND po.podstrona_aktywna = 0 ';
		}

		$sql_count = $sql;

		if($this->filtr_sortuj_po != '')
		{
			$kolumna = '';
			switch ($this->filtr_sortuj_po)
			{
				case 'nazwa':
					$kolumna = ' po.podstrona_nazwa ';
					break;
				case 'id':
					$kolumna = ' p.podstrona_id ';
					break;
				case 'url':
					$kolumna = ' po.podstrona_url ';
					break;
				case 'kolejnosc':
					$kolumna = ' po.podstrona_miejsce ';
					break;
				case 'modul':
					$kolumna = ' po.podstrona_modul ';
					break;
				case 'aktywna':
					$kolumna = ' po.podstrona_aktywna ';
					break;
				default:
					$kolumna = ' po.podstrona_nazwa ';
					break;
			}
						
			$sql .= ' ORDER BY '.$kolumna;
			if($this->filtr_sortuj_jak != '') $sql .= ' '.$this->filtr_sortuj_jak;
		}

		if($this->filtr_maks != '')
		{
			$sql .= ' LIMIT '.(int)$this->filtr_maks.'';
		}
		else if($this->filtr_ilosc_wynikow != '' && $this->filtr_strona != '')
		{
			$sql .= ' LIMIT '.($this->filtr_ilosc_wynikow * $this->filtr_strona - $this->filtr_ilosc_wynikow).', '.(int)$this->filtr_ilosc_wynikow.'';
		}
		
		$result_podstrony = $db->query($sql);
		foreach($result_podstrony as $row)
		{
			$this->rekordy[] = $row['id'];
		}

		$result_count = $db->query($sql_count);
		$this->ilosc_rekordow =$result_count->RecordCount();
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

}
