<?php

class Model_Galeria
{

	public $id = '';
	
	public $id_podstrona = 0;	
	public $autor = '';
	public $data_dodania = '';
	public $nazwa = array();
	public $url = array();
	public $tresc = array();		
	public $aktywna = array();
	public $miejsce = array();
	
	public $title = array();
	public $description = array();
	public $keywords = array();
	public $ilosc_zdjec = array();
	public $zdjecia = array();
	
	public $foto_opisy = array();
	public $foto_nazwy = array();
	public $foto_kolejnosc = array();
	public $foto_do_usuniecia = array();
	public $errors = array();
	
	private $rozmiary = array();
	
	
	public $zdjecie_glowne = '';
	public $zdjecie_glowne_id = '';

	
	//========= parametry filtrowania =============
	public $filtr_id = '';
	public $filtr_id_podstrona = '';
	public $filtr_aktywna = '';
	public $filtr_nazwa = '';
	public $filtr_autor = '';
	public $filtr_data_dodania = '';
	public $filtr_url = '';
	public $filtr_jezyk_id = '';
	public $filtr_sortuj_po =  '';
	public $filtr_sortuj_jak =  '';
	public $filtr_strona = '';
	public $filtr_ilosc_wynikow ='';
	public $filtr_maks = '';
	
	
	public $foto_dir = 'galerie';	
	public $foto_table = "galerie_foto";
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
	public function zapisz()
	{
		$db = Core_DB::instancja();

		$rekord = array();
		$rekord["galeria_data_dodania"] = $this->data_dodania;
		$rekord["galeria_autor"] = $this->autor;

		if((int)$this->id > 0) {			
			$resultSQL = $db->update('galerie', $rekord, 'galeria_id = '.(int)$this->id);
		} else {
			$resultSQL = $db->insert('galerie', $rekord);
			$this->id = $db->last_insert_id('galerie');//Insert_ID();
		}

		
		if((int)$this->id > 0) {
			$a_jezyki = Model_Jezyk::pobierzWszystkie();

			foreach($a_jezyki as $idJezyka => $skrotJezyka) {
				$rekord = array();
				$rekord["galeria_id"] = (int)$this->id;
				$rekord["jezyk_id"] = (int)$idJezyka;
				$rekord["galeria_nazwa"] = $this->nazwa[$idJezyka];				
				$rekord["galeria_url"] = $this->url[$idJezyka];
				$rekord["galeria_tresc"] = $this->tresc[$idJezyka];
				$rekord["galeria_miejsce"] = $this->miejsce[$idJezyka];
				$rekord["galeria_aktywna"] = $this->aktywna[$idJezyka];
				
				$queryCount = $db->query('SELECT * FROM galerie_opisy WHERE galeria_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				if( $queryCount->RecordCount() > 0 ) {
					$resultSQL = $db->update('galerie_opisy', $rekord, 'galeria_id = '.(int)$this->id.' AND jezyk_id = '.(int)$idJezyka.'');
				} else {
					$resultSQL = $db->insert('galerie_opisy', $rekord);
				}
			}
			
			$sql_clera_podstrony = "DELETE FROM galerie_podstrony WHERE galeria_id=".(int)$this->id;
			$queryClear = $db->query($sql_clera_podstrony);
			
			$a_podstrony = array();
			$a_podstrony['galeria_id'] = (int)$this->id;
			$a_podstrony['podstrona_id'] = $this->id_podstrona;
			
			$resultSQL = $db->insert('galerie_podstrony', $a_podstrony);
			
			
			if($this->formToken>0)
			{
				$sql_foto_update = "UPDATE ".$this->foto_table." SET ".$this->foto_table_prefix."_galeria_id=".(int)$this->id.", ".$this->foto_table_prefix."_token=0 WHERE ".$this->foto_table_prefix."_token=".$this->formToken;
				$result_update = $db->query($sql_foto_update);
			}
			
			//=========== glowne
		
			if((int)$this->zdjecie_glowne_id>0)
			{
				$sql_clear = "UPDATE ".$this->foto_table." SET ".$this->foto_table_prefix."_glowna=0 WHERE ".$this->foto_table_prefix."_galeria_id=".(int)$this->id;
				$result_clear = $db->query($sql_clear);
				
				
				$sql_update = "UPDATE ".$this->foto_table." SET ".$this->foto_table_prefix."_glowna=1 WHERE ".$this->foto_table_prefix."_galeria_id=".(int)$this->id." AND ".$this->foto_table_prefix."_id=".(int)$this->zdjecie_glowne_id;
				$result_update= $db->query($sql_update);
			}
			
			//=========== kolejnosc 
			if(count($this->foto_kolejnosc)>0)
			{
				
				foreach ($this->foto_kolejnosc as $index =>$id_zdjecia)
				{
					$sql_update_kolejnosc = "UPDATE galerie_foto SET foto_kolejnosc=".$index." WHERE foto_id=".(int)$id_zdjecia." AND foto_galeria_id=".(int)$this->id;
					$result_kolejnosc = $db->query($sql_update_kolejnosc);
				}
			}
			
			//=========== opisy
			if(count($this->foto_opisy)>0)
			{
				foreach ($this->foto_opisy as $id_zdjecia=>$a_opisy)
				{
					foreach ($a_opisy as $jezyk_id => $opis)
					{
						if(!isset($this->foto_do_usuniecia[$id_zdjecia]))
						{
							$sql_czek_opis = "SELECT count(*) FROM galerie_foto_opisy WHERE foto_id=".(int)$id_zdjecia." AND jezyk_id=".(int)$jezyk_id;
							$ilosc = $db->get_one($sql_czek_opis);

							$rekord_dane = array();
							$rekord_dane['foto_id'] = $id_zdjecia;
							$rekord_dane['jezyk_id'] = $jezyk_id;
							$rekord_dane['fo_opis'] = $opis;
							
							$rekord_dane['fo_nazwa'] = $this->foto_nazwy[$id_zdjecia][$jezyk_id];
							
							if($ilosc==0)
							{
								$resultSQL = $db->insert('galerie_foto_opisy', $rekord_dane);
							}
							else
							{
								$warunek = " foto_id=".$id_zdjecia." AND jezyk_id=".$jezyk_id;

								$resultSQL = $db->update('galerie_foto_opisy', $rekord_dane,$warunek);
							}
						}
					}
				}
			}
			
			//============== usuwanie 
			if(count($this->foto_do_usuniecia)>0)
			{
				foreach ($this->foto_do_usuniecia as $id_zdjecia=>$tmp)
				{
					$sql_del = "DELETE FROM galerie_foto_opisy WHERE foto_id=".$id_zdjecia;
					$resultDEL = $db->query($sql_del);
					
					$sql_del = "DELETE FROM galerie_foto WHERE foto_id=".$id_zdjecia;
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
			
			$sql_del = 'DELETE FROM galerie WHERE galeria_id = '.(int)$id;
			$db->Execute($sql_del);			
			
			$sql_del = 'DELETE FROM galerie_opisy WHERE galeria_id = '.(int)$id;
			$db->Execute($sql_del);		
			
			$sql_del = 'DELETE FROM galerie_podstrony WHERE galeria_id = '.(int)$id;
			$db->Execute($sql_del);	
			
			$sql_del = "DELETE FROM galerie_foto_opisy WHERE foto_id= IN ( SELECT foto_id FROM galerie_foto WHERE foto_galeria_id=".$id.")";
			$resultDEL = $db->query($sql_del);
						
			$sql_del = "DELETE FROM galerie_foto WHERE foto_galeria_id=".$id;
			$resultDEL = $db->query($sql_del);
						
			$komunikaty[] = array('ok', 'Rekord o id = ' . (int)$id . ' został usunięty.');
		}
		
		return $komunikaty;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapiszZdjecie($gal_id, $plik_upload, $token='')
	{
		$db = Core_DB::instancja();
		$prefix = time().'_';
		//====================================		
		$plik_nazwa = $prefix.Core_Narzedzia::usunZnakiNiedozwolonePliki($plik_upload);
		//====================================
		//sprawdzanie głównego zdjecia
		$sql_glowne = "SELECT count(*) AS ile FROM  ".$this->foto_table." WHERE ".$this->foto_table_prefix."_galeria_id=".(int)$gal_id." AND ".$this->foto_table_prefix."_token=".(int)$token;
		$czy_glowne = $db->get_one($sql_glowne);	
		
		$glowne = 1;
		if($czy_glowne>0)
		{
			$glowne = 0;
		}
		//====================================
		$sql = "INSERT INTO ".$this->foto_table." ( ".$this->foto_table_prefix."_galeria_id, ".$this->foto_table_prefix."_sciezka, ".$this->foto_table_prefix."_glowna, ".$this->foto_table_prefix."_token,".$this->foto_table_prefix."_kolejnosc ) VALUES (".(int)$gal_id.",'".$plik_nazwa."',".$glowne.",'".(int)$token."',9999)";		
		$parentSelect = $db->Execute($sql);	
		
		$katalog_zdjecia = Core_Config::get('images_path').$this->foto_dir.'/';
		
		$a_wymiary[0]['wysokosc'] = '';
		$a_wymiary[0]['szerokosc'] = '';
		$a_wymiary[0]['typ'] = 'standard';
		
		$a_wymiary[1]['wysokosc'] = '110';
		$a_wymiary[1]['szerokosc'] = '150';
		$a_wymiary[1]['typ'] = 'standard';
		
		$a_wymiary[2]['wysokosc'] = '340';
		$a_wymiary[2]['szerokosc'] = '700';
		$a_wymiary[2]['typ'] = 'standard';		
		
		$a_wymiary[3]['wysokosc'] = '345';
		$a_wymiary[3]['szerokosc'] = '450';
		$a_wymiary[3]['typ'] = 'standard';	
		
		$a_wymiary[4]['wysokosc'] = '180';
		$a_wymiary[4]['szerokosc'] = '245';
		$a_wymiary[4]['typ'] = 'standard';		
		
		$p_zdjecie = new Plugin_FileUpload();
		$this->errors = $p_zdjecie->uploadNew($plik_upload, $plik_nazwa, $a_wymiary, $katalog_zdjecia);
		
		
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzZdjecia($id, $token)
	{
		// moze sie przyda ale jeszcze nie wiem :)
		$db = Core_DB::instancja();	
		$kolumna = '_galeria_id';
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
			
			//========== opisy 
			$sql_opisy = 'SELECT 
						* 
					FROM 
						'.$this->foto_table.'_opisy AS gfo, 
						'.$this->foto_table.' AS gf							
					WHERE 
						gf.'.$this->foto_table_prefix.$kolumna.'='.(int)$id.' AND 
						gf.'.$this->foto_table_prefix.'_id = gfo.'.$this->foto_table_prefix.'_id';
			
			//echo $sql_opisy;
			//die();
			$result_opisy = $db->query($sql_opisy);
			
			foreach($result_opisy as $opisy)
			{					
				$this->foto_opisy[$opisy['foto_id']][$opisy['jezyk_id']] = $opisy['fo_opis'];
				$this->foto_nazwy[$opisy['foto_id']][$opisy['jezyk_id']] = $opisy['fo_nazwa'];
			}
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierz($id)
	{
		$db = Core_DB::instancja();

		if((int)$id > 0)
		{
			$sql = 'SELECT * FROM galerie WHERE galeria_id = '.(int)$id.' LIMIT 1';
			$result = $db->get_row($sql);

			if(count($result) > 0)
			{
				$this->id = (int)$result['galeria_id'];
				$this->data_dodania = $result['galeria_data_dodania'];
				$this->autor = $result['galeria_autor'];

				$sql_opis = 'SELECT * FROM galerie_opisy WHERE galeria_id = '.(int)$this->id;
				$result_opis = $db->query($sql_opis);
				foreach($result_opis as $opis)
				{
					$this->nazwa[$opis['jezyk_id']] = $opis['galeria_nazwa'];
					$this->url[$opis['jezyk_id']] = $opis['galeria_url'];
					$this->tresc[$opis['jezyk_id']] = $opis['galeria_tresc'];
					//$this->title[$opis['jezyk_id']] = $opis['galeria_title'];
					//$this->description[$opis['jezyk_id']] = $opis['galeria_description'];
					//$this->keywords[$opis['jezyk_id']] = $opis['galeria_keywords'];
					//$this->podpis[$opis['jezyk_id']] = $opis['galeria_podpis'];
					$this->ilosc_zdjec[$opis['jezyk_id']] = $opis['galeria_ilosc_zdjec'];
					$this->miejsce[$opis['jezyk_id']] = $opis['galeria_miejsce'];
					$this->aktywna[$opis['jezyk_id']] = $opis['galeria_aktywna'];
					//$this->glowna[$opis['jezyk_id']] = $opis['galeria_glowna'];


					$j = new Model_Jezyk($opis['jezyk_id']);

					$this->adres[$opis['jezyk_id']] = Core_Config::get('www_url').$j->skrot.'/gal/'.$this->url[$opis['jezyk_id']];
				}

				$sql_podstrony = 'SELECT podstrona_id AS id FROM galerie_podstrony WHERE galeria_id = '.(int)$this->id;
				$this->id_podstrona = $db->get_one($sql_podstrony);
				
				$sql_zdjecia = "SELECT * FROM galerie_foto WHERE foto_galeria_id=".(int)$this->id." ORDER BY foto_kolejnosc";
				$result_zdjecia = $db->query($sql_zdjecia);
				
				foreach($result_zdjecia as $zdjecie)
				{
					$this->zdjecia[$zdjecie['foto_id']]  = $zdjecie['foto_sciezka'];
					
					if($zdjecie['foto_glowna']==1)
					{
						$this->zdjecie_glowne = $zdjecie['foto_sciezka'];
						$this->zdjecie_glowne_id = $zdjecie['foto_id'];
					}
				}
				
				
				//========== opisy 
				$sql_opisy = "SELECT 
							* 
						FROM 
							galerie_foto_opisy AS gfo, 
							galerie_foto AS gf							
						WHERE 
							gf.foto_galeria_id=".(int)$this->id." AND 
							gf.foto_id = gfo.foto_id";
				
				$result_opisy = $db->query($sql_opisy);
				
				foreach($result_opisy as $opisy)
				{					
					$this->foto_opisy[$opisy['foto_id']][$opisy['jezyk_id']] = $opisy['fo_opis'];
					$this->foto_nazwy[$opisy['foto_id']][$opisy['jezyk_id']] = $opisy['fo_nazwa'];
				}
				
				
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
	public function validate() {
		$errors = array();

		return $errors;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function fromArray(array $r)
	{
		$this->id = (int) $r['id'];

		if(isset($r['id_podstrona'])) $this->id_podstrona = (int)$r['id_podstrona'];
		
		if(isset($r['autor'])) $this->autor = $r['autor'];
		if(isset($r['data_dodania'])) $this->data_dodania = $r['data_dodania'];

		
		if(isset($r['foto_kolejnosc'])) $this->foto_kolejnosc = $r['foto_kolejnosc'];
		if(isset($r['foto_opis'])) $this->foto_opisy = $r['foto_opis'];
		if(isset($r['foto_nazwa'])) $this->foto_nazwy = $r['foto_nazwa'];
		if(isset($r['zdjecie_glowne'])) $this->zdjecie_glowne_id = $r['zdjecie_glowne'];

		
		if(isset($r['usun'])) $this->foto_do_usuniecia = $r['usun'];

		
		if(isset($r['nazwa']) && is_array($r['nazwa'])) {
			foreach($r['nazwa'] as $jezykId => $wartosc) {
				$this->nazwa[$jezykId] = stripslashes($wartosc);
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
				//$this->url[$jezykId] = $wartosc;
			}
		}
		

		if(isset($r['tresc']) && is_array($r['tresc'])) {
			foreach($r['tresc'] as $jezykId => $wartosc) {
				$this->tresc[$jezykId] = $wartosc;
			}
		}		

		if(isset($r['aktywna']) && is_array($r['aktywna'])) {
			foreach($r['aktywna'] as $jezykId => $wartosc) {
				$this->aktywna[$jezykId] = (int)$wartosc;
			}
		}
		
		if(isset($r['miejsce']) && is_array($r['miejsce'])) {
			foreach($r['miejsce'] as $jezykId => $wartosc) {
				$this->miejsce[$jezykId] = $wartosc;
			}
		}
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzPrzezUrl($jezyk_id,$url)
	{
		$db = Core_DB::instancja();

		$sql = 'SELECT galeria_id AS id FROM galerie_opisy WHERE galeria_url = "'.trim($url).'" AND jezyk_id = '.(int)$jezyk_id;
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
	public function filtrujGalerie()
	{

		$db = Core_DB::instancja();


		$sql = "SELECT
				g.galeria_id AS id
			FROM
				galerie AS g, 
				galerie_opisy AS go";

		if((int)$this->filtr_id_podstrona > 0)
		{
			$sql .=	' ,galerie_podstrony AS gp ';
		}

		$sql .= ' WHERE
				g.galeria_id = go.galeria_id AND 
				g.galeria_id = go.galeria_id ';




		if($this->filtr_strona < 1) $this->filtr_strona = 1;
		
		if($this->filtr_id != '') $sql .= ' AND g.galeria_id='.(int)$this->filtr_id;
		if($this->filtr_jezyk_id != '') $sql .= ' AND go.jezyk_id='.(int)$this->filtr_jezyk_id;
		if($this->filtr_nazwa != '') $sql .= ' AND go.galeria_nazwa LIKE "%'.$this->filtr_nazwa.'%" ';
		if($this->filtr_autor != '') $sql .= ' AND g.galeria_autor LIKE "%'.$this->filtr_autor.'%" ';
		if($this->filtr_url != '') $sql .= ' AND go.galeria_url LIKE "%'.$this->filtr_url.'%" ';
		if($this->filtr_data_dodania != '') $sql .= ' AND g.galeria_data_dodania LIKE "%'.$this->filtr_data_dodania.'%" ';

		if($this->filtr_id_podstrona != '') $sql .= ' AND gp.podstrona_id ='.(int)$this->filtr_id_podstrona;

		if($this->filtr_aktywna == '1'){
			$sql .= ' AND go.galeria_aktywna = 1 ';
		}
		else if($this->filtr_aktywna == '0') {
			$sql .= ' AND go.galeria_aktywna = 0 ';
		}


		$sql_count = $sql;

		if($this->filtr_sortuj_po != '')
		{
			$kolumna = '';
			switch ($this->filtr_sortuj_po)
			{
				case 'nazwa':
					$kolumna = ' go.galeria_nazwa ';
					break;
				case 'id':
					$kolumna = ' g.galeria_id ';
					break;
				case 'data_dodania':
					$kolumna = ' g.galeria_data_dodania ';
					break;
				case 'autor':
					$kolumna = ' g.galeria_autor ';
					break;
				case 'url':
					$kolumna = ' go.galeria_url ';
					break;
				case 'kolejnosc':
					$kolumna = ' go.galeria_miejsce ';
					break;				
				case 'aktywna':
					$kolumna = ' go.galeria_aktywna ';
					break;
				default:
					$kolumna = ' go.galeria_nazwa ';
					break;
			}
						
			$sql .= ' ORDER BY '.$kolumna;
			if($this->filtr_sortuj_jak != '') $sql .= ' '.$this->filtr_sortuj_jak;
		}
		else 
		{
			$sql .= ' ORDER BY go.galeria_nazwa DESC';
		}

		if($this->filtr_maks != '')
		{
			$sql .= ' LIMIT '.(int)$this->filtr_maks.'';
		}
		else if($this->filtr_ilosc_wynikow != '' && $this->filtr_strona != '')
		{
			$sql .= ' LIMIT '.($this->filtr_ilosc_wynikow * $this->filtr_strona - $this->filtr_ilosc_wynikow).', '.(int)$this->filtr_ilosc_wynikow.'';
		}
		
		$result_galerie = $db->query($sql);
		foreach($result_galerie as $row)
		{
			$this->rekordy[] = $row['id'];
		}

		$result_count = $db->query($sql_count);
		$this->ilosc_rekordow =$result_count->RecordCount();
	}


}
