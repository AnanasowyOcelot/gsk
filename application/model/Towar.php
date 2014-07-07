<?php

class Model_Towar
{

	public $id = '';

	public $id_podstrona = 0;
	public $data_dodania = '';
	public $data_wydarzenia = '';
	public $godzina_wydarzenia = '';	
	public $formToken = '';

	public $rekordy = array();
	public $tytul = array();
	public $url = array();
	public $skrot = array();
	public $tresc = array();
	public $adres = array();

	public $glowna = array();
	public $aktywna = array();
	public $miejsce = array();

	public $title = array();
	public $description = array();
	public $keywords = array();
	public $errors = array();	
	
	public $zdjecia = array();
	public $zdjecie_glowne = '';
	public $zdjecie_glowne_id = '';
	
	public $zdjecia_kolejnosc = array();
	public $zdjecia_do_usuniecia = array();
	//========= parametry filtrowania =============
	public $filtr_id= '';
	public $filtr_id_podstrona = '';
	public $filtr_aktywna = '';
	public $filtr_tytul = '';
	public $filtr_glowna = '';
	public $filtr_tresc = '';
	public $filtr_skrot = '';
	public $filtr_url = '';
	public $filtr_data_dodania = '';
	public $filtr_data_wydarzenia_od = '';
	public $filtr_data_wydarzenia_do = '';
	public $filtr_jezyk_id = '';
	public $filtr_sortuj_po =  '';
	public $filtr_sortuj_jak =  '';
	public $filtr_strona = '';
	public $filtr_ilosc_wynikow ='';
	public $filtr_maks = '';
	public $filtr_data_usuniecia_od = '';
	public $filtr_data_usuniecia_do = '';

	
	public $foto_dir = 'towary';
	public $modul_adres = 'towary';
	public $table_prefix = 'towar';
	public $main_table = "towary";
	public $opis_table = "towary_opisy";
	public $podstrony_table = "towary_kategorie";
	public $podstrony_table_prefix = "kategoria";
	public $foto_table = "towary_foto";
	public $foto_table_prefix = "foto";
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
	public function podajGodzine()
	{
		return date("H:i",strtotime($this->godzina_wydarzenia));
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function podajSkroconaDate()
	{
		return date("d.m",strtotime($this->data_wydarzenia));
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
	public function zapisz()
	{
		$db = Core_DB::instancja();
		
		
		$plik_nazwa = '';
		
		$rekord = array();
		$rekord[$this->table_prefix."_data_dodania"] = $this->data_dodania;
		
		
		if((int)$this->id > 0) {
			$resultSQL = $db->update($this->main_table, $rekord, $this->table_prefix.'_id = '.(int)$this->id);
		} else {
			$resultSQL = $db->insert($this->main_table, $rekord);
			$this->id = $db->last_insert_id($this->main_table);
		}


		if((int)$this->id > 0) {
			$a_jezyki = Model_Jezyk::pobierzWszystkie();

			foreach($a_jezyki as $idJezyka => $skrotJezyka) {
				$rekord = array();
				$rekord[$this->table_prefix."_id"] = (int)$this->id;
				$rekord["jezyk_id"] = (int)$idJezyka;
				$rekord[$this->table_prefix."_tytul"] = $this->tytul[$idJezyka];
				$rekord[$this->table_prefix."_url"] = $this->url[$idJezyka];
				$rekord[$this->table_prefix."_skrot"] = $this->skrot[$idJezyka];
				$rekord[$this->table_prefix."_tresc"] = $this->tresc[$idJezyka];
				$rekord[$this->table_prefix."_miejsce"] = $this->miejsce[$idJezyka];
				$rekord[$this->table_prefix."_aktywna"] = $this->aktywna[$idJezyka];
				$rekord[$this->table_prefix."_data_wydarzenia"] = $this->data_wydarzenia;
				$rekord[$this->table_prefix."_godzina_wydarzenia"] = $this->godzina_wydarzenia;

				$queryCount = $db->query('SELECT * FROM '.$this->opis_table.' WHERE '.$this->table_prefix.'_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				if( $queryCount->RecordCount() > 0 ) {
					$resultSQL = $db->update($this->opis_table, $rekord, $this->table_prefix.'_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				} else {
					$resultSQL = $db->insert($this->opis_table, $rekord);
				}
			}

			$sql_clera_podstrony = "DELETE FROM ".$this->podstrony_table." WHERE ".$this->table_prefix."_id=".(int)$this->id;
			$queryClear = $db->query($sql_clera_podstrony);

			$a_podstrony = array();
			$a_podstrony[$this->table_prefix.'_id'] = (int)$this->id;
			$a_podstrony[$this->podstrony_table_prefix.'_id'] = $this->id_podstrona;

			$resultSQL = $db->insert($this->podstrony_table, $a_podstrony);
			
			
			if($this->formToken>0)
			{
				$sql_foto_update = "UPDATE ".$this->foto_table." SET ".$this->foto_table_prefix."_aktualnosc_id=".(int)$this->id.", ".$this->foto_table_prefix."_token=0 WHERE ".$this->foto_table_prefix."_token=".$this->formToken;
				$result_update = $db->query($sql_foto_update);
			}
			
			//=========== glowne
		
			if((int)$this->zdjecie_glowne_id>0)
			{
				$sql_clear = "UPDATE ".$this->foto_table." SET ".$this->foto_table_prefix."_glowna=0 WHERE ".$this->foto_table_prefix."_aktualnosc_id=".(int)$this->id;
				$result_clear = $db->query($sql_clear);
				
				
				$sql_update = "UPDATE ".$this->foto_table." SET ".$this->foto_table_prefix."_glowna=1 WHERE ".$this->foto_table_prefix."_aktualnosc_id=".(int)$this->id." AND ".$this->foto_table_prefix."_id=".(int)$this->zdjecie_glowne_id;
				$result_update= $db->query($sql_update);
			}
			//=========== kolejnosc 
			if(count($this->zdjecia_kolejnosc)>0)
			{
				
				foreach ($this->zdjecia_kolejnosc as $index =>$id_zdjecia)
				{
					$sql_update_kolejnosc = "UPDATE ".$this->foto_table." SET ".$this->foto_table_prefix."_kolejnosc=".$index." WHERE ".$this->foto_table_prefix."_id=".(int)$id_zdjecia." AND ".$this->foto_table_prefix."_aktualnosc_id=".(int)$this->id;
					$result_kolejnosc = $db->query($sql_update_kolejnosc);
				}
			}
			
			//============== usuwanie 
			if(count($this->zdjecia_do_usuniecia)>0)
			{
				foreach ($this->zdjecia_do_usuniecia as $id_zdjecia=>$tmp)
				{
					$sql_del = "DELETE FROM ".$this->foto_table." WHERE ".$this->foto_table_prefix."_id=".$id_zdjecia;
					$resultDEL = $db->query($sql_del);
					
					
					
					$sciezka = $this->zdjecia[$id_zdjecia];
					
					//unlink(......);
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

			$sql_del = 'DELETE FROM '.$this->main_table.' WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$db->Execute($sql_del);

			$sql_del = 'DELETE FROM '.$this->opis_table.' WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$db->Execute($sql_del);

			$sql_del = 'DELETE FROM '.$this->podstrony_table.' WHERE '.$this->table_prefix.'_id = '.(int)$id;
			$db->Execute($sql_del);

			$komunikaty[] = array('ok', 'Rekord ' . (int)$id . ' został usunięty.');
		}

		return $komunikaty;
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzZdjecia($id, $token)
	{
		// moze sie przyda ale jeszcze nie wiem :)
		$db = Core_DB::instancja();	
		$kolumna = '_towar_id';
		if((int)$id==0)
		{
			$id = $token;	
			$kolumna = "_token";
		}
			$sql_zdjecia = 'SELECT 
						'.$this->foto_table_prefix.'_id as id,
						'.$this->foto_table_prefix.'_sciezka as plik,
						'.$this->foto_table_prefix.'_glowna as glowne
					 FROM '.$this->foto_table.' WHERE '.$this->foto_table_prefix.$kolumna.' = '.(int)$id." ORDER BY ".$this->foto_table_prefix.'_kolejnosc';
			$result_zdjecia = $db->query($sql_zdjecia);
			
			foreach($result_zdjecia as $zdjecie)
			{
				$this->zdjecia[$zdjecie['id']]  = $zdjecie['plik'];
				
				if($zdjecie['glowne']==1)
				{
					$this->zdjecie_glowne = $zdjecie['plik'];
					$this->zdjecie_glowne_id = $zdjecie['id'];
				}
				
			}
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
				$this->data_dodania = $result[$this->table_prefix.'_data_dodania'];

				$sql_opis = 'SELECT * FROM '.$this->opis_table.' WHERE '.$this->table_prefix.'_id = '.(int)$this->id;
				$result_opis = $db->query($sql_opis);
				foreach($result_opis as $opis)
				{
					$this->tytul[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_tytul']);
					$this->url[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_url']);
					$this->skrot[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_skrot']);
					$this->tresc[$opis['jezyk_id']] = stripslashes($opis[$this->table_prefix.'_tresc']);
					$this->title[$opis['jezyk_id']] = $opis[$this->table_prefix.'_title'];
					$this->description[$opis['jezyk_id']] = $opis[$this->table_prefix.'_description'];
					$this->keywords[$opis['jezyk_id']] = $opis[$this->table_prefix.'_keywords'];
					$this->miejsce[$opis['jezyk_id']] = $opis[$this->table_prefix.'_miejsce'];
					$this->aktywna[$opis['jezyk_id']] = $opis[$this->table_prefix.'_aktywna'];
					$this->glowna[$opis['jezyk_id']] = $opis[$this->table_prefix.'_glowna'];

					$this->data_wydarzenia = $opis[$this->table_prefix.'_data_wydarzenia'];
					$this->godzina_wydarzenia = $opis[$this->table_prefix.'_godzina_wydarzenia'];

					$j = new Model_Jezyk($opis['jezyk_id']);

					$this->adres[$opis['jezyk_id']] = Core_Config::get('www_url').$j->skrot.'/'.$this->modul_adres.'/'.$this->url[$opis['jezyk_id']];
				}

				$sql_podstrony = 'SELECT '.$this->table_prefix.'_id AS id FROM '.$this->podstrony_table.' WHERE '.$this->table_prefix.'_id = '.(int)$this->id;
				$this->id_podstrona = $db->get_one($sql_podstrony);
				
				//				
				//==========================================================================================
				$sql_zdjecia = 'SELECT 
							'.$this->foto_table_prefix.'_id as id,
							'.$this->foto_table_prefix.'_sciezka as plik,
							'.$this->foto_table_prefix.'_glowna as glowne
						 FROM '.$this->foto_table.' WHERE '.$this->foto_table_prefix.'_'.$this->table_prefix.'_id = '.(int)$this->id." ORDER BY ".$this->foto_table_prefix.'_kolejnosc';
				$result_zdjecia = $db->query($sql_zdjecia);
				
				foreach($result_zdjecia as $zdjecie)
				{
					$this->zdjecia[$zdjecie['id']]  = $zdjecie['plik'];
					
					if($zdjecie['glowne']==1)
					{
						$this->zdjecie_glowne = $zdjecie['plik'];
						$this->zdjecie_glowne_id = $zdjecie['id'];
					}
					
				}
				//==========================================================================================
				
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
	public function zapiszZdjecie($rekord_id, $plik_upload, $token='')
	{
		$db = Core_DB::instancja();
		$prefix = time().'_';
		//====================================
		//$plik_upload = Core_Narzedzia::usunZnakiNiedozwolonePliki($plik_upload);
		//$plik_nazwa = time().'_'.$o_requestIn->getParametr('qqfile');
		$plik_nazwa = $prefix.Core_Narzedzia::usunZnakiNiedozwolonePliki($plik_upload);
		//====================================
		//sprawdzanie głównego zdjecia
		$sql_glowne = "SELECT count(*) AS ile FROM  ".$this->foto_table." WHERE ".$this->foto_table_prefix."_towar_id=".(int)$rekord_id." AND ".$this->foto_table_prefix."_token=".(int)$token;
		$czy_glowne = $db->get_one($sql_glowne);	
		
		$glowne = 1;
		if($czy_glowne>0)
		{
			$glowne = 0;
		}
		//====================================
		$sql = "INSERT INTO ".$this->foto_table." ( ".$this->foto_table_prefix."_towar_id, ".$this->foto_table_prefix."_sciezka, ".$this->foto_table_prefix."_glowna, ".$this->foto_table_prefix."_token,".$this->foto_table_prefix."_kolejnosc ) VALUES (".(int)$rekord_id.",'".$plik_nazwa."',".$glowne.",'".(int)$token."',9999)";		
		$parentSelect = $db->Execute($sql);	
		
		$katalog_zdjecia = Core_Config::get('images_path').$this->foto_dir.'/';
		
		$a_wymiary[0]['wysokosc'] = '';
		$a_wymiary[0]['szerokosc'] = '';
		$a_wymiary[0]['typ'] = 'standard';
		
		$a_wymiary[1]['wysokosc'] = '100';
		$a_wymiary[1]['szerokosc'] = '165';
		$a_wymiary[1]['typ'] = 'kadr';
		
		$a_wymiary[2]['wysokosc'] = '260';
		$a_wymiary[2]['szerokosc'] = '345';
		$a_wymiary[2]['typ'] = 'standard';		
		
		$p_zdjecie = new Plugin_FileUpload();
		$this->errors = $p_zdjecie->uploadNew($plik_upload, $plik_nazwa, $a_wymiary, $katalog_zdjecia);
		
		
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function fromArray(array $r)
	{
		$this->id = (int) $r['id'];

		if(isset($r['id_podstrona'])) $this->id_podstrona = (int)$r['id_podstrona'];
		if(isset($r['formToken'])) $this->formToken = (int)$r['formToken'];
		//if(isset($r['data_dodania'])) $this->data_dodania = $r['data_dodania'];
		$this->data_dodania = date("Y-m-d");
		if(isset($r['data_wydarzenia'])) $this->data_wydarzenia = $r['data_wydarzenia'];
		//if(isset($r['godzina_wydarzenia'])) 
		
		if(isset($r['foto_kolejnosc'])) $this->zdjecia_kolejnosc = $r['foto_kolejnosc'];		
		if(isset($r['usun'])) $this->zdjecia_do_usuniecia = $r['usun'];
		if(isset($r['zdjecie_glowne'])) $this->zdjecie_glowne_id = $r['zdjecie_glowne'];

		
		$this->godzina_wydarzenia = date("H:i:s");


		if(isset($r['tytul']) && is_array($r['tytul'])) {
			foreach($r['tytul'] as $jezykId => $wartosc) {
				$this->tytul[$jezykId] = stripslashes($wartosc);
			}
		}

		if(isset($r['skrot']) && is_array($r['skrot'])) {
			foreach($r['skrot'] as $jezykId => $wartosc) {
				$this->skrot[$jezykId] = stripslashes($wartosc);
			}
		}

		if(isset($r['tresc']) && is_array($r['tresc'])) {
			foreach($r['tresc'] as $jezykId => $wartosc) {
				$this->tresc[$jezykId] = stripslashes($wartosc);
			}
		}

		if(isset($r['glowna']) && is_array($r['glowna'])) {
			foreach($r['glowna'] as $jezykId => $wartosc) {
				$this->glowna[$jezykId] = stripslashes($wartosc);
			}
		}

		if(isset($r['url']) && is_array($r['url'])) {
			foreach($r['url'] as $jezykId => $wartosc) {

				if(trim($wartosc) != '') {
					$this->url[$jezykId]  = Core_Narzedzia::usunZnakiNiedozwolone(trim(stripslashes($wartosc)));
				}
				else {
					$this->url[$jezykId] = Core_Narzedzia::usunZnakiNiedozwolone(trim(stripslashes($this->tytul[$jezykId])));
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

		$sql = 'SELECT '.$this->table_prefix.'_id AS id FROM '.$this->opis_table.' WHERE '.$this->table_prefix.'_url = "'.trim($url).'" AND jezyk_id = '.(int)$jezyk_id;
		$rekord = $db->get_row($sql);

		if(count($rekord) == 0)
		{
			$this->errors[] = 'Nie odnaleziono rekordu';
		}
		else if(count($rekord) == 1)
		{
			$this->pobierz($rekord['id']);
		}

	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function filtrujRekordy($kosz=0)
	{

		$db = Core_DB::instancja();


		$sql = 'SELECT
				r.'.$this->table_prefix.'_id AS id
			FROM
				'.$this->main_table.' AS r, 
				'.$this->opis_table.' AS ro';

		if((int)$this->filtr_id_podstrona > 0)
		{
			$sql .=	' ,'.$this->podstrony_table.' AS rp ';
		}

		$sql .= ' WHERE
				r.'.$this->table_prefix.'_id = ro.'.$this->table_prefix.'_id ';

		if((int)$this->filtr_id_podstrona > 0)
		{
			$sql .= ' AND
				r.'.$this->table_prefix.'_id = rp.'.$this->table_prefix.'_id';
		}

		
		$sql .= ' AND r.'.$this->table_prefix.'_usuniety='.$kosz;

		if($this->filtr_strona < 1) $this->filtr_strona = 1;

		if($this->filtr_id != '') $sql .= ' AND ro.'.$this->table_prefix.'_id='.(int)$this->filtr_id;
		if($this->filtr_jezyk_id != '') $sql .= ' AND ro.jezyk_id='.(int)$this->filtr_jezyk_id;
		if($this->filtr_tytul != '') $sql .= ' AND ro.'.$this->table_prefix.'_tytul LIKE "%'.$this->filtr_tytul.'%" ';
		if($this->filtr_skrot != '') $sql .= ' AND ro.'.$this->table_prefix.'_skrot LIKE "%'.$this->filtr_skrot.'%" ';
		if($this->filtr_tresc != '') $sql .= ' AND ro.'.$this->table_prefix.'_tresc LIKE "%'.$this->filtr_tresc.'%" ';
		if($this->filtr_data_dodania != '') $sql .= ' AND ro.'.$this->table_prefix.'_data_dodania = "%'.$this->filtr_data_dodania.'" ';
		if($this->filtr_data_wydarzenia_od != '') $sql .= ' AND ro.'.$this->table_prefix.'_data_wydarzenia >= "'.$this->filtr_data_wydarzenia_od.'" ';
		if($this->filtr_data_wydarzenia_do != '') $sql .= ' AND ro.'.$this->table_prefix.'_data_wydarzenia <= "'.$this->filtr_data_wydarzenia_do.'" ';
		if($this->filtr_url != '') $sql .= ' AND ro.'.$this->table_prefix.'_url LIKE "%'.$this->filtr_url.'%" ';
		if($this->filtr_glowna != '') $sql .= ' AND ro.'.$this->table_prefix.'_glowna LIKE "%'.$this->filtr_glowna.'%" ';

		if($this->filtr_id_podstrona != '') $sql .= ' AND rp.'.$this->table_prefix.'_id ='.(int)$this->filtr_id_podstrona;

		if($this->filtr_aktywna == '1'){
			$sql .= ' AND ro.'.$this->table_prefix.'_aktywna = 1 ';
		}
		else if($this->filtr_aktywna == '0') {
			$sql .= ' AND ro.'.$this->table_prefix.'_aktywna = 0 ';
		}


		$sql_count = $sql;

		if($this->filtr_sortuj_po != '')
		{
			$kolumna = '';
			switch (strtolower(trim($this->filtr_sortuj_po)))
			{
				case 'tytul':
					$kolumna = ' ro.'.$this->table_prefix.'_tytul ';
					break;
				case 'data_dodania':
					$kolumna = ' r.'.$this->table_prefix.'_data_dodania ';
					break;
				case 'data_wydarzenia':
					$kolumna = ' ro.'.$this->table_prefix.'_data_wydarzenia ';
					break;
				case 'id':
					$kolumna = ' r.'.$this->table_prefix.'_id ';
					break;
				case 'url':
					$kolumna = ' ro.'.$this->table_prefix.'_url ';
					break;
				case 'kolejnosc':
					$kolumna = ' ro.'.$this->table_prefix.'_miejsce ';
					break;
				case 'aktywna':
					$kolumna = ' ro.'.$this->table_prefix.'_aktywna ';
					break;
				default:
					$kolumna = ' ro.'.$this->table_prefix.'_id ';
					break;
			}

			$sql .= ' ORDER BY '.$kolumna;
			if($this->filtr_sortuj_jak != '') $sql .= ' '.$this->filtr_sortuj_jak;
		}
		else
		{
			$sql .= ' ORDER BY r.'.$this->table_prefix.'_id DESC';
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
