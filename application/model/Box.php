<?php

class Model_Box
{

	public $id = '';
	public $data_dodania = '';
	public $zdjecie = '';
	public $podstrona_id = array();
	public $szablon_id = '';
	public $nazwa = array();
	public $tytul = array();
	public $tytul_tresc = array();
	public $url = array();
	public $link = array();
	public $opis = array();
	public $adres = array();
	public $aktywna = array();
	public $miejsce = array();
	public $link_wiecej = array();

	public $title = array();
	public $description = array();
	public $keywords = array();
	public $errors = array();

	public $pliki = array();

	//========= parametry filtrowania =============
	public $filtr_id= '';
	public $filtr_aktywna = '';
	public $filtr_nazwa = '';
	public $filtr_opis = '';
	public $filtr_url = '';
	public $filtr_jezyk_id = '';
	public $filtr_sortuj_po =  '';
	public $filtr_sortuj_jak =  '';
	public $filtr_strona = '';
	public $filtr_ilosc_wynikow ='';
	public $filtr_maks = '';

	public $table_prefix = "box";
	public $main_table = "boxy";
	public $opis_table = "boxy_opisy";
	public $podstrony_table = "boxy_podstrony";
	public $podstrony_prefix = "pb";

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
			$katalog_zdj = Core_Config::get('images_path').'boxy/';

			$a_wymiary[0]['wysokosc'] = '';
			$a_wymiary[0]['szerokosc'] = '';
			
			$a_wymiary[1]['wysokosc'] = '115';
			$a_wymiary[1]['szerokosc'] = '105';
			
			$a_wymiary[2]['wysokosc'] = '150';
			$a_wymiary[2]['szerokosc'] = '135';
			
			$a_wymiary[3]['wysokosc'] = '90';
			$a_wymiary[3]['szerokosc'] = '105';


			foreach ($this->pliki as $nazwa => $dane)
			{
				
				if($dane['tmp_name']!="")
				{
					$path = $dane['tmp_name'];
	
					//====================================
					$plik_nazwa = time().'_'.Core_Narzedzia::usunZnakiNiedozwolonePliki($dane['name']);
					//====================================
					$sciezka = $katalog_zdj.'0/'.$plik_nazwa;
					$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $a_wymiary[0]['szerokosc'], $a_wymiary[0]['wysokosc']);
					
					$sciezka = $katalog_zdj.'1/'.$plik_nazwa;
					$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $a_wymiary[1]['szerokosc'], $a_wymiary[1]['wysokosc'] );
	
					$sciezka = $katalog_zdj.'2/'.$plik_nazwa;
					$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $a_wymiary[2]['szerokosc'], $a_wymiary[2]['wysokosc'] );
					
					$sciezka = $katalog_zdj.'3/'.$plik_nazwa;
					$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $a_wymiary[3]['szerokosc'], $a_wymiary[3]['wysokosc'] );
				}
			}
		}


		$rekord = array();

		$rekord[$this->table_prefix."_data_dodania"] = $this->data_dodania;
		$rekord[$this->table_prefix."_szablon_id"] = $this->szablon_id;
		
		if($plik_nazwa!="")
		{
			$rekord[$this->table_prefix."_zdjecie"] = $plik_nazwa;
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
				$rekord[$this->table_prefix."_tytul"] = $this->tytul[$idJezyka];
				$rekord[$this->table_prefix."_tytul_tresc"] = $this->tytul_tresc[$idJezyka];
				$rekord[$this->table_prefix."_url"] = $this->url[$idJezyka];
				$rekord[$this->table_prefix."_tresc"] = $this->opis[$idJezyka];
				$rekord[$this->table_prefix."_miejsce"] = $this->miejsce[$idJezyka];
				$rekord[$this->table_prefix."_aktywna"] = $this->aktywna[$idJezyka];
				$rekord[$this->table_prefix."_link"] = $this->link[$idJezyka];
				$rekord[$this->table_prefix."_link_wiecej"] = $this->link_wiecej[$idJezyka];


				$queryCount = $db->query('SELECT * FROM '.$this->opis_table.'  WHERE '.$this->table_prefix.'_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				if( $queryCount->RecordCount() > 0 ) {
					$resultSQL = $db->update($this->opis_table, $rekord, $this->table_prefix.'_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				} else {
					$resultSQL = $db->insert($this->opis_table, $rekord);
				}
			}
			
			
			$sql_clera_podstrony = "DELETE FROM ".$this->podstrony_table." WHERE ".$this->podstrony_prefix."_box_id=".(int)$this->id;
			$queryClear = $db->query($sql_clera_podstrony);
			
			foreach ($this->podstrona_id as $index => $tmp_podstrona_id)
			{
				$a_podstrony = array();
				$a_podstrony[$this->podstrony_prefix.'_box_id'] = (int)$this->id;
				$a_podstrony[$this->podstrony_prefix.'_podstrona_id'] = (int)$tmp_podstrona_id;

				$resultSQL = $db->insert($this->podstrony_table, $a_podstrony);
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
			
			$sql_del = 'DELETE FROM '.$this->podstrony_table.' WHERE '.$this->podstrony_prefix.'_box_id = '.(int)$id;
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
				$this->data_dodania = $result[$this->table_prefix."_data_dodania"];
				$this->zdjecie = $result[$this->table_prefix."_zdjecie"];
				$this->szablon_id = $result[$this->table_prefix."_szablon_id"];

				$sql_opis = 'SELECT * FROM '.$this->opis_table.' WHERE '.$this->table_prefix.'_id = '.(int)$this->id;
				$result_opis = $db->query($sql_opis);
				foreach($result_opis as $opis)
				{
					$this->nazwa[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_nazwa']);
					$this->tytul[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_tytul']);
					$this->tytul_tresc[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_tytul_tresc']);
					$this->url[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_url']);
					$this->opis[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_tresc']);
					$this->miejsce[$opis['jezyk_id']] = $opis[$this->table_prefix.'_miejsce'];
					$this->aktywna[$opis['jezyk_id']] = $opis[$this->table_prefix.'_aktywna'];
					$this->link[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_link']);
					$this->link_wiecej[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_link_wiecej']);


					$j = new Model_Jezyk($opis['jezyk_id']);
					
					//$link_tpl = Core_Config::get('www_url').$j->skrot.'/box/'.$this->url[$opis['jezyk_id']];
					$link_tpl = Core_Config::get('www_url');
					if($this->link[$opis['jezyk_id']] !="")
					{
						$link_tpl = $this->link[$opis['jezyk_id']];
					}

					$this->adres[$opis['jezyk_id']] = $link_tpl;
				}
				
				
				$sql_podstrony = 'SELECT '.$this->podstrony_prefix.'_podstrona_id AS id FROM '.$this->podstrony_table.' WHERE '.$this->podstrony_prefix.'_box_id = '.(int)$this->id;
				$result_podstrony = $db->query($sql_podstrony);
				
			
				foreach($result_podstrony as $index => $podstrona)
				{					
					$this->podstrona_id[] = $podstrona['id'];
				}
				//$this->podstrona_id = $db->get_one($sql_podstrony);
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
		$this->data_dodania = date("Y-m-d");
		$this->szablon_id = $r['szablon_id'];
		
		$this->podstrona_id = array();
		$this->podstrona_id[] = $r['podstrona_id'];

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
		
		if(isset($r['tytul_tresc']) && is_array($r['tytul_tresc'])) {
			foreach($r['tytul_tresc'] as $jezykId => $wartosc) {
				$this->tytul_tresc[$jezykId] = stripslashes($wartosc);
			}
		}

		if(isset($r['opis']) && is_array($r['opis'])) {
			foreach($r['opis'] as $jezykId => $wartosc) {
				$this->opis[$jezykId] = stripslashes($wartosc);
			}
		}

		if(isset($r['url']) && is_array($r['url'])) {
			foreach($r['url'] as $jezykId => $wartosc) {

				if(trim($wartosc) != '') {
					$this->url[$jezykId]  = Core_Narzedzia::usunZnakiNiedozwolone(trim(stripslashes($wartosc)));
				}
				else {
					$this->url[$jezykId] = Core_Narzedzia::usunZnakiNiedozwolone(trim(stripslashes($this->nazwa[$jezykId])));
				}
			}
		}

		if(isset($r['aktywna']) && is_array($r['aktywna'])) {
			foreach($r['aktywna'] as $jezykId => $wartosc) {
				$this->aktywna[$jezykId] = (int)$wartosc;
			}
		}
		
		if(isset($r['link_wiecej']) && is_array($r['link_wiecej'])) {
			foreach($r['link_wiecej'] as $jezykId => $wartosc) {
				$this->link_wiecej[$jezykId] = $wartosc;
			}
		}

		if(isset($r['miejsce']) && is_array($r['miejsce'])) {
			foreach($r['miejsce'] as $jezykId => $wartosc) {
				$this->miejsce[$jezykId] = (int)$wartosc;
			}
		}
		
		if(isset($r['link']) && is_array($r['link'])) {
			foreach($r['link'] as $jezykId => $wartosc) {
				$this->link[$jezykId] = $wartosc;
			}
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzPrzezUrl($jezyk_id,$url)
	{
		$db = Core_DB::instancja();

		$sql = 'SELECT '.$this->table_prefix.'_id AS id FROM '.$this->opis_table.' WHERE '.$this->table_prefix.'_url = "'.trim($url).'" AND jezyk_id = '.(int)$jezyk_id;
		$rekord = $db->get_row($sql);

		if(count($rekord) == 0)
		{
			$this->errors[] = 'Nie ma takiego rekordu';
		}
		else if(count($rekord) == 1)
		{
			$this->pobierz($rekord['id']);
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
				'.$this->opis_table.' AS too ';
		
		if(count($this->podstrona_id)>0)
		{
			//$sql .= ','.$this->podstrony_table.' AS tp ';
		}
		
		$sql .= '	WHERE
				t.'.$this->table_prefix.'_id = too.'.$this->table_prefix.'_id ';

		if($this->filtr_strona < 1) $this->filtr_strona = 1;
		if($this->filtr_id != '') $sql .= ' AND too.'.$this->table_prefix.'_id='.(int)$this->filtr_id;
		if($this->filtr_jezyk_id != '') $sql .= ' AND too.jezyk_id='.(int)$this->filtr_jezyk_id;
		if($this->filtr_nazwa != '') $sql .= ' AND too.'.$this->table_prefix.'_nazwa LIKE "%'.$this->filtr_nazwa.'%" ';
		if($this->filtr_opis != '') $sql .= ' AND too.'.$this->table_prefix.'_opis LIKE "%'.$this->filtr_opis.'%" ';
		if($this->filtr_url != '') $sql .= ' AND too.'.$this->table_prefix.'_url LIKE "%'.$this->filtr_url.'%" ';
		
		if(count($this->podstrona_id)>0)
		{
			$sql .= ' AND 0 < (SELECT count(*) FROM '.$this->podstrony_table.' AS tp2 WHERE '.$this->podstrony_prefix.'_podstrona_id IN ('.implode(",",$this->podstrona_id).') AND tp2.'.$this->podstrony_prefix.'_box_id=t.'.$this->table_prefix.'_id)';
		}

		

		if($this->filtr_aktywna == '1'){
			$sql .= ' AND too.'.$this->table_prefix.'_aktywna = 1 ';
		}
		else if($this->filtr_aktywna == '0') {
			$sql .= ' AND too.'.$this->table_prefix.'_aktywna = 0 ';
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


}
