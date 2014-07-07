<?php

class Model_Cennik
{

	public $id = '';
	
	//======================================
	public $a_pola_cennik = array();
	public $a_naglowki = array();
	
	public $cenniki_db = array();
	public $naglowki_db = array();
	
	public $table_prefix = "klient";
	public $main_table = "klienci";
	//public $opis_table = "klient_opisy";

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function __construct()
	{
		$this->pobierzPolaCeny();
		$this->pobierzNaglowki();
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzPolaCeny()
	{
		$db = Core_DB::instancja();
		
		$sql = "SELECT * FROM cennik_wartosc";

		$result_rekordy = $db->query($sql);
		
		$a_pola = array();
		foreach($result_rekordy as $row)
		{
			$this->a_pola_cennik[] = $row;
		}
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzNaglowki()
	{
		$db = Core_DB::instancja();
		
		$sql = "SELECT * FROM cennik_naglowek WHERE jezyk_id=1 ";

		$result_rekordy = $db->query($sql);
		
		$a_pola = array();
		foreach($result_rekordy as $row)
		{
			$this->a_naglowki[$row['cennik_naglowek_id']]['nazwa'] = $row['cennik_naglowek_nazwa'];
			
			$sql_opisy = "SELECT * FROM cennik_sekcja_opisy WHERE cs_naglowek_id=".$row['cennik_naglowek_id']." AND cs_form=1";
			$result_opisy = $db->query($sql_opisy);
			
			$a_tmp = array();
			foreach($result_opisy as $row_opis)
			{
				$a_tmp[$row_opis['cs_id']][$row_opis['jezyk_id']]	['nazwa'] = $row_opis['cs_nazwa'];
				$a_tmp[$row_opis['cs_id']][$row_opis['jezyk_id']]	['opis'] = $row_opis['cs_opis'];
			}
			
			$this->a_naglowki[$row['cennik_naglowek_id']]['naglowki'] = $a_tmp;
		}
	}
	
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function setFiles($pliki_in)
	{
		$this->pliki = $pliki_in;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapisz()
	{
		$db = Core_DB::instancja();

		

		if(count($this->cenniki_db)>0)
		{
			foreach ($this->cenniki_db as $cennik_id => $wartosc)
			{
				$a_update = array();
				$a_update['cw_cena'] = $wartosc;
				$a_update['cw_naglowek'] = $this->cenniki_naglowki_db[$cennik_id];;
				
				$resultSQL = $db->update("cennik_wartosc", $a_update, 'cw_id = '.$cennik_id);				
			}
		}
		
		if(count($this->naglowki_db)>0)
		{
			foreach ($this->naglowki_db as $naglowek_id => $wartosci)
			{
				foreach ($wartosci as $jezyk_id => $tresc)
				{
					$a_update = array();
					$a_update['cs_opis'] = $tresc;
					
					$resultSQL = $db->update("cennik_sekcja_opisy", $a_update, 'cs_id = '.$naglowek_id.' AND jezyk_id='.$jezyk_id);				
				}
			}
			
		}

	}


	
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function fromArray(array $r)
	{
		$this->cenniki_db = $r['cennik_wartosc'];
		$this->cenniki_naglowki_db = $r['cennik_naglowek'];
		$this->naglowki_db = $r['cennik_sekcja_opisy'];
	}
	

	

}
