<?php

class Model_Kontakt
{
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function __construct()
	{
		
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapiszZapytanie($route_in)
	{
		$db = Core_DB::instancja();
		
		$komunikat = "OK";
		
	
			$sql_insert = "INSERT INTO 
						zapytania 
					SET 
						zapytanie_email='".$this->params['e_mail']."',
						zapytanie_name='".$this->params['name']."',
						zapytanie_tresc='".$this->params['message']."',
						zapytanie_data='".date("Y-m-d H:i:s")."' ";
			$result_insert = $db->query($sql_insert);			
			
			if(count($result_insert)==0)
			{
				$komunikat = "Wystąpił błąd";
			}
		
		
		return $komunikat;
	}
	
}
