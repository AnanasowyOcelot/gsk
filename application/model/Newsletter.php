<?php

class Model_Newsletter
{
	public $params = array();
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function __construct($params_in)
	{
		$this->params = $params_in;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapiszSubskrybenta($email='')
	{
		$db = Core_DB::instancja();
		if($email!="")
		{
			$komunikat = "OK";
			
			$sql_czek = "SELECT count(*) AS ilosc FROM  subskrybenci WHERE s_email='".$email."' ";
			$ilosc = $db->get_one($sql_czek);
			
			if($ilosc == 0)
			{
				$sql_insert = "INSERT INTO subskrybenci SET s_email='".$email."', s_ip='".$_SERVER[REMOTE_ADDR]."' ";
				$result_insert = $db->query($sql_insert);			
				
				if(count($result_insert)==0)
				{
					$komunikat = "Wystąpił błąd";
				}
			}
			else 
			{
				$komunikat = "Podany e-email jest już w bazie";
			}
			
			return $komunikat;
			
		}
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	
}
