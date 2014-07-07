<?php

class Model_Video
{

	public $id = '';
	
	public $id_podstrona = 0;	
	public $data_dodania = '';
	public $obrazek = '';
	public $rekordy = array();
	
	public $plik_fly = '';
	public $film_vimeo = '';
	public $film_youtube = '';
	public $typ = '';
	
	public $nazwa = array();
	public $tytul = array();
	public $tresc = array();	
	public $url = array();
	
	public $aktywna = array();
	public $miejsce = array();
	
	public $title = array();
	public $description = array();
	public $keywords = array();
	public $errors = array();
	
	private $rozmiary = array();
	
	
	//========= parametry filtrowania =============
	public $filtr_id = '';
	public $filtr_id_podstrona = '';
	public $filtr_aktywna = '';
	public $filtr_nazwa = '';
	public $filtr_tytul = '';
	public $filtr_url = '';
	public $filtr_jezyk_id = '';
	public $filtr_sortuj_po =  '';
	public $filtr_sortuj_jak =  '';
	public $filtr_strona = '';
	public $filtr_ilosc_wynikow ='';
	public $filtr_maks = '';
	public $filtr_data_usuniecia_od = '';
	public $filtr_data_usuniecia_do = '';
	
	public $table_prefix = "video";
	public $main_table = "video";
	public $opis_table = "video_opisy";


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
	public function podajSkroconaTytul($jezyk_id, $dlugosc=100)
	{

		$text_tmp = $this->tytul[$jezyk_id];

		$opis_skrocony = '';
		if(strlen($text_tmp)>$dlugosc)
		{
			$skrocony = substr($text_tmp,0,$dlugosc);
			$pozycja_spacja = strrpos($skrocony,' ');
			$opis_skrocony = substr($text_tmp,0,$pozycja_spacja)."...";
		}
		else
		{
			$opis_skrocony = $text_tmp;
		}

		return $opis_skrocony;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function podajSkroconaNazwe($jezyk_id, $dlugosc=100)
	{

		$text_tmp = $this->nazwa[$jezyk_id];

		$opis_skrocony = '';
		if(strlen($text_tmp)>$dlugosc)
		{
			$skrocony = substr($text_tmp,0,$dlugosc);
			$pozycja_spacja = strrpos($skrocony,' ');
			$opis_skrocony = substr($text_tmp,0,$pozycja_spacja)."...";
		}
		else
		{
			$opis_skrocony = $text_tmp;
		}

		return $opis_skrocony;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapisz()
	{
		$db = Core_DB::instancja();
		
		$obrazek_nazwa = '';
		$flash_nazwa = '';
			
		if(count($this->pliki)>0)
		{
			$a_wymiary[1]['wysokosc'] = '100';
			$a_wymiary[1]['szerokosc'] = '165';
			
			$a_wymiary[2]['wysokosc'] = '300';
			$a_wymiary[2]['szerokosc'] = '425';

		
			foreach ($this->pliki as $nazwa => $dane)
			{
				$path = $dane['tmp_name'];
				
				if($dane['tmp_name']!="")
				{
					if($nazwa=='obrazek')
					{
						$katalog_zdj = Core_Config::get('images_path').'video/';

						$obrazek_nazwa = time().'_'.$dane['name'];
						//====================================
						$sciezka = $katalog_zdj.'1/'.$obrazek_nazwa;
						$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke_kadrujac_zdjecie($path, $sciezka, $a_wymiary[1]['szerokosc'], $a_wymiary[1]['wysokosc'] );
						
						$sciezka = $katalog_zdj.'2/'.$obrazek_nazwa;
						$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, $a_wymiary[2]['szerokosc'], $a_wymiary[2]['wysokosc'] );

						$sciezka = $katalog_zdj.'0/'.$obrazek_nazwa;
						$tmp_zdjecie = Core_Zdjecie::tworz_miniaturke($path, $sciezka, '', '');

					}

					if($nazwa=="plik_fly")
					{

						$flash_nazwa = time().'_'.$dane['name'];
						$katalog_flash = Core_Config::get('server_path').'filmy/';
						$sciezka = $katalog_flash.$flash_nazwa;

						$tmp_zdjecie = Core_Zdjecie::kopiuj_zdjecie($path,$sciezka);

					}
				}
			}
			
			
		}

		
		$rekord = array();
		$rekord[$this->table_prefix."_data_dodania"] = $this->data_dodania;
		$rekord[$this->table_prefix."_typ"] = $this->typ;
		
		$rekord[$this->table_prefix."_youtube"] = $this->film_youtube;
		$rekord[$this->table_prefix."_vimeo"] = $this->film_vimeo;
				
		if($obrazek_nazwa!='')
		{
			$rekord[$this->table_prefix."_obrazek"] = $obrazek_nazwa;
		}
		
		if($flash_nazwa!='')
		{
			$rekord[$this->table_prefix."_fly"] = $flash_nazwa;
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
				$rekord["video_id"] = (int)$this->id;
				$rekord["jezyk_id"] = (int)$idJezyka;
				$rekord["video_nazwa"] = $this->nazwa[$idJezyka];
				$rekord["video_tytul"] = $this->tytul[$idJezyka];
				$rekord["video_url"] = $this->url[$idJezyka];
				$rekord["video_nazwa"] = $this->nazwa[$idJezyka];
				$rekord["video_miejsce"] = $this->miejsce[$idJezyka];
				$rekord["video_aktywna"] = $this->aktywna[$idJezyka];
				$rekord["video_tresc"] = $this->tresc[$idJezyka];
				
				$queryCount = $db->query('SELECT * FROM video_opisy WHERE video_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				if( $queryCount->RecordCount() > 0 ) {
					$resultSQL = $db->update('video_opisy', $rekord, 'video_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				} else {
					$resultSQL = $db->insert('video_opisy', $rekord);
				}
			}
			
			$sql_clera_podstrony = "DELETE FROM video_podstrony WHERE video_id=".(int)$this->id;
			$queryClear = $db->query($sql_clera_podstrony);
			
			$a_podstrony = array();
			$a_podstrony['video_id'] = (int)$this->id;
			$a_podstrony['podstrona_id'] = $this->id_podstrona;
			
			$resultSQL = $db->insert('video_podstrony', $a_podstrony);	
					
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function usun($id)
	{
		$db = Core_DB::instancja();
		$komunikaty = array();

		if($id > 0) {
			$sql_del = 'UPDATE '.$this->main_table.'  SET '.$this->table_prefix.'_usuniety  = 1 WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$db->Execute($sql_del);
			$komunikaty[] = array('ok', 'Rekord ' . (int)$id . ' został usunięty.');
		}

		return $komunikaty;
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function przywrocusuniety($id)
	{
		$db = Core_DB::instancja();
		$komunikaty = array();

		if($id > 0) {
			$sql_del = 'UPDATE '.$this->main_table.'  SET '.$this->table_prefix.'_usuniety  = 0 WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$db->Execute($sql_del);
			$komunikaty[] = array('ok', 'Rekord ' . (int)$id . ' został przywrócony.');
		}

		return $komunikaty;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function usunKosz($id)
	{
		$db = Core_DB::instancja();
		$komunikaty = array();
		
		if($id > 0) {
			
			$sql_del = 'DELETE FROM video WHERE video_id = '.(int)$id;
			$db->Execute($sql_del);			
			
			$sql_del = 'DELETE FROM video_opisy WHERE video_id = '.(int)$id;
			$db->Execute($sql_del);		

			$sql_del = 'DELETE FROM video_podstrony WHERE video_id = '.(int)$id;
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
			$sql = 'SELECT * FROM video WHERE video_id = '.(int)$id.' LIMIT 1';
			$result = $db->get_row($sql);

			if(count($result) > 0)
			{
				$this->id = (int)$result['video_id'];
				$this->data_dodania = $result['video_data_dodania'];
				$this->typ = $result[$this->table_prefix."_typ"];
				$this->obrazek = $result['video_obrazek'];
				$this->plik_fly = $result[$this->table_prefix."_fly"];
				$this->film_youtube = $result[$this->table_prefix."_youtube"];
				$this->film_vimeo = $result[$this->table_prefix."_vimeo"];
				

				$sql_opis = 'SELECT * FROM video_opisy WHERE video_id = '.(int)$this->id;
				$result_opis = $db->query($sql_opis);
				foreach($result_opis as $opis)
				{
					$this->nazwa[$opis['jezyk_id']] = stripslashes($opis['video_nazwa']);
					$this->tytul[$opis['jezyk_id']] = stripslashes($opis['video_tytul']);
					$this->url[$opis['jezyk_id']] = stripslashes($opis['video_url']);
					$this->tresc[$opis['jezyk_id']] = stripslashes($opis['video_tresc']);
					$this->nazwa[$opis['jezyk_id']] = stripslashes($opis['video_nazwa']);
					//$this->title[$opis['jezyk_id']] = $opis['video_title'];
					//$this->description[$opis['jezyk_id']] = $opis['video_description'];
					//$this->keywords[$opis['jezyk_id']] = $opis['video_keywords'];
					//$this->podpis[$opis['jezyk_id']] = $opis['video_podpis'];
					$this->ilosc_zdjec[$opis['jezyk_id']] = $opis['video_ilosc_zdjec'];
					$this->miejsce[$opis['jezyk_id']] = $opis['video_miejsce'];
					$this->aktywna[$opis['jezyk_id']] = $opis['video_aktywna'];
					//$this->glowna[$opis['jezyk_id']] = $opis['video_glowna'];


					$j = new Model_Jezyk($opis['jezyk_id']);

					$this->adres[$opis['jezyk_id']] = Core_Config::get('www_url').$j->skrot.'/video/'.$this->url[$opis['jezyk_id']];
				}

				$sql_podstrony = 'SELECT podstrona_id AS id FROM video_podstrony WHERE video_id = '.(int)$this->id;
				$this->id_podstrona = $db->get_one($sql_podstrony);				
			}
			else
			{
				$this->errors[] = 'Nie znaleziono galerii o '.$id.'.';
			}
		}

		if(count($this->errors) > 0){
			return false;
		}
		else return true;
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function fromArray(array $r)
	{
		$this->id = (int) $r['id'];
		
		print_r($r);

		if(isset($r['id_podstrona'])) $this->id_podstrona = (int)$r['id_podstrona'];		
		if(isset($r['autor'])) $this->autor = stripslashes($r['autor']);
		if(isset($r['data_dodania'])) $this->data_dodania = $r['data_dodania'];
						
		if(isset($r['film_vimeo'])) {$this->film_vimeo = $r['film_vimeo'];}
		if(isset($r['film_youtube'])) {$this->film_youtube = trim($r['film_youtube']);}
		if(isset($r['typ'])) {$this->typ = $r['typ'];}
		
		if(isset($r['nazwa']) && is_array($r['nazwa'])) {
			foreach($r['nazwa'] as $jezykId => $wartosc) {
				$this->nazwa[$jezykId] = stripslashes($wartosc);
			}
		}
		
		if(isset($r['tresc']) && is_array($r['tresc'])) {
			foreach($r['tresc'] as $jezykId => $wartosc) {
				$this->tresc[$jezykId] = stripslashes($wartosc);
			}
		}
		
		if(isset($r['tytul']) && is_array($r['tytul'])) {
			foreach($r['tytul'] as $jezykId => $wartosc) {
				$this->tytul[$jezykId] = stripslashes($wartosc);
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
		
		if(isset($r['miejsce']) && is_array($r['miejsce'])) {
			foreach($r['miejsce'] as $jezykId => $wartosc) {
				$this->miejsce[$jezykId] = (int)$wartosc;
			}
		}	
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzPrzezUrl($jezyk_id,$url)
	{
		$db = Core_DB::instancja();

		$sql = 'SELECT video_id AS id FROM video_opisy WHERE video_url = "'.trim($url).'" AND jezyk_id = '.(int)$jezyk_id;
		$rekord = $db->get_row($sql);

		if(count($rekord) == 0)
		{
			$this->errors[] = 'Nie ma takiej galerii';
		}
		else if(count($rekord) == 1)
		{
			$this->pobierz($rekord['id']);
		}

	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzKosz()
	{
		$db = Core_DB::instancja();


		$sql = 'SELECT
				r.'.$this->table_prefix.'_id AS id
			FROM
				'.$this->main_table.' AS r, 
				'.$this->opis_table.' AS ro';

		$sql .= ' WHERE
				r.'.$this->table_prefix.'_id = ro.'.$this->table_prefix.'_id ';

		
		$sql .= ' AND r.'.$this->table_prefix.'_usuniety=1';
		$sql .= ' AND ro.jezyk_id=1';

		if($this->filtr_data_usuniecia_od != '') $sql .= ' AND ro.'.$this->table_prefix.'_data_usuniecia_od >= "'.$this->filtr_data_usuniecia_od.'" ';
		if($this->filtr_data_usuniecia_do != '') $sql .= ' AND ro.'.$this->table_prefix.'_data_usuniecia_do <= "'.$this->filtr_data_usuniecia_do.'" ';

		$sql_count = $sql;
		
		
		
		$result_aktualnosci = $db->query($sql);
		foreach($result_aktualnosci as $row)
		{
			$this->rekordy[] = $row['id'];
		}

		$result_count = $db->query($sql_count);
		$this->ilosc_rekordow =$result_count->RecordCount();
	}


	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function filtrujRekordy($kosz=0)
	{
		$db = Core_DB::instancja();

		$sql = 'SELECT
				t.'.$this->table_prefix.'_id AS id
			FROM
				'.$this->main_table.' AS t, 
				'.$this->opis_table.' AS too';
		
			if((int)$this->filtr_id_podstrona > 0)
			{
				$sql .=	' ,'.$this->table_prefix.'_podstrony AS tp ';
			}
		$sql .= '	WHERE
				t.'.$this->table_prefix.'_id = too.'.$this->table_prefix.'_id ';
		
		$sql .= ' AND t.'.$this->table_prefix.'_usuniety='.$kosz;

		
		if($this->filtr_strona < 1) $this->filtr_strona = 1;		
		if($this->filtr_id != '') $sql .= ' AND too.'.$this->table_prefix.'_id='.(int)$this->filtr_id;
		if($this->filtr_jezyk_id != '') $sql .= ' AND too.jezyk_id='.(int)$this->filtr_jezyk_id;
		if($this->filtr_nazwa != '') $sql .= ' AND too.'.$this->table_prefix.'_nazwa LIKE "%'.$this->filtr_nazwa.'%" ';		
		//if($this->filtr_opis != '') $sql .= ' AND too.'.$this->table_prefix.'_opis LIKE "%'.$this->filtr_opis.'%" ';		
		if($this->filtr_tytul!= '') $sql .= ' AND too.'.$this->table_prefix.'_tytul LIKE "%'.$this->filtr_tytul.'%" ';		
		if($this->filtr_url != '') $sql .= ' AND too.'.$this->table_prefix.'_url LIKE "%'.$this->filtr_url.'%" ';
		
		if($this->filtr_id_podstrona != '') $sql .= ' AND tp.'.$this->table_prefix.'_id ='.(int)$this->filtr_id_podstrona;

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
				case 'tytul':
					$kolumna = ' too.'.$this->table_prefix.'_tytul ';
					break;
				case 'data_dodania':
					$kolumna = ' o.'.$this->table_prefix.'_data_dodania ';
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
			
			$sql .= ', t.'.$this->table_prefix.'_id  DESC';
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
