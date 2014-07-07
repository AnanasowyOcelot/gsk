<?php
class cennik_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'cennik';
		parent::__construct();
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function wyswietlAction(array $route_in, $refererRoute_in = null) {
	
		
		$db = Core_DB::instancja();
		$jezyk_id = $route_in['jezyk_id'];
		
		$view = new cennik_View();
		
		$sql =" SELECT * FROM cennik_wartosc ";
		$result = $db->query($sql);
		
		$a_wartosci = array();
		$index = 0;
		if(count($result) > 0)
		{
			foreach($result as $wartosc)
			{	
				$a_wartosci[$wartosc['cw_nazwa_pola']][$wartosc['cw_sekcja_id']]['cena'] = $wartosc['cw_cena'];
				$a_wartosci[$wartosc['cw_nazwa_pola']][$wartosc['cw_sekcja_id']]['naglowek'] = $wartosc['cw_naglowek'];
			}
		}
		
		
		$sql =" SELECT * FROM cennik_naglowek AS cn WHERE cn.cennik_naglowek_aktywny=1 AND cn.jezyk_id=".$jezyk_id." ORDER BY cn.cennik_naglowek_kolejnosc ASC";
		$result = $db->query($sql);
		
		$a_cennik = array();
		$index = 0;
		if(count($result) > 0)
		{
			foreach($result as $naglowek)
			{	
				$id_naglowek = $naglowek['cennik_naglowek_id'];
				
				$sql_sekcje = "SELECT 
							cso.*, 
							cs.cs_szablon,
							cs.cs_zdjecie
						FROM
							cennik_sekcja AS cs,
							cennik_sekcja_opisy AS cso
						WHERE 
							cso.cs_naglowek_id =".$id_naglowek." AND 
							cso.jezyk_id=".$jezyk_id." AND
							cs.cs_id = cso.cs_id
						ORDER BY
							cso.cs_kolejnosc";
				
				
				$result_sekcje = $db->query($sql_sekcje);
				
				$a_sekcje = array();
				foreach($result_sekcje as $sekcja)
				{
					$sekcja['widok'] = $view->wyswietlWiersz($route_in['jezyk_id'],$sekcja, $a_wartosci );
					$a_sekcje[$sekcja['cs_id']] = $sekcja;
				}				
				
				$index++;
				$last = 'sekcjaCennikLinia';
				if($index==$result->RecordCount())
				{
					$last = 'sekcjaCennik';
				}
				
				$a_cennik[$id_naglowek]['naglowek']= $naglowek;
				$a_cennik[$id_naglowek]['sekcje'] = $a_sekcje;
				
				$a_cennik[$id_naglowek]['classa'] = $last;
				
				
				
			}
		}
				
		
		
		
		$html = $view->wyswietlCennik($route_in['jezyk_id'], $a_cennik);
		
		//$html .= '<pre>'.print_r($a_cennik,1).'</pre>';
		$o_response = new Core_Response();
		$o_response->setContent($html);
		return $o_response;
		
		
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

};
