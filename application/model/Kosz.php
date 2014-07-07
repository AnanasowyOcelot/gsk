<?php

class Model_Kosz
{	
	private function budujListeObiektow($a_indexy, $model)
	{
		$a_obiekty = array();
		
		if(count($a_indexy)>0 && $model!='')
		{
			foreach ($a_indexy as $index) 
			{
				$obj = new $model($index);
				$a_obiekty[] = $obj;
			}
		}
		
		return $a_obiekty;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzKosz()
	{
		$db = Core_DB::instancja();
		
		$a_kosz = array();

		
		return $a_kosz;
		
	}
}
