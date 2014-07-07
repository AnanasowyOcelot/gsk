<?php
class aktualnosci_Controller extends Core_ModuleController
{
	public function __construct($params=array()) {
		$this->modul = 'aktualnosci';
		parent::__construct($params);
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function widokAjaxAction(array $route_in, $refererRoute_in = null)
	{
		$view = new aktualnosci_View();
		
		$id_aktualnosc = $route_in['parametry_lista']['id'];
		$jezyk_id = $route_in['parametry_lista']['lang'];
		$aktualnosc  = new Model_Aktualnosc($id_aktualnosc);
		/*
        $body = '<b>'.$aktualnosc->tytul[$jezyk_id].'</b>';
		$body .='<br><br>';
		$body .= $aktualnosc->tresc[$jezyk_id];
        */
		$body = $view->wyswietlBox($route_in['jezyk_id'], $aktualnosc);
        
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($body);
		
		return $o_indexResponse;		
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function wyswietlAction(array $route_in, $refererRoute_in = null) {
		$o_response = new Core_Response();
		$view = new aktualnosci_View();
		$link = '/' . Core_Config::get('jezyk_skrot') . '/Aktualnosci';
		$url_aktualnosci = strtolower(trim($route_in['parametry']));
		
		if(trim($url_aktualnosci)!="") {
			$o_aktualnosc = new Model_Aktualnosc();
			$o_aktualnosc->pobierzPrzezUrl($route_in['jezyk_id'], $url_aktualnosci);
			
			$html = $view->wyswietl($route_in['jezyk_id'], $o_aktualnosc, $link);
		} else {			
			//echo "<pre>".print_r($route_in,1)."</pre>";
			$html = $view->BudujListeStronaGlowna($route_in['jezyk_id'],$route_in['strona']);
		}
		$o_response->setContent($html);
		return $o_response;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzAction($route_in, $refererRoute_in) {
		$galeria = new Model_Aktualnosc();
		$galeria->pobierzPrzezUrl($route_in['jezyk_id'], $route_in['url']);
	
		$o_pobierzResponse = new Core_Response();
		$o_pobierzResponse->setModuleTemplate("szczegoly");
		$o_pobierzResponse->setContent($galeria->nazwa[$route_in['jezyk_id']]);
		
		$link_powrot = '';
		if(isset($refererRoute_in['url']))
		{			
			$o_pobierzResponse->setRefererURL($refererRoute_in['url']);			
			$link_powrot = '/'.Core_Config::get("jezyk_skrot").'/'.$refererRoute_in['url'];			 
		}
		else 
		{			
			$def_podstrona_id = $galeria->podstrony[0];
			
			if((int)$def_podstrona_id>0)
			{
				$podstrona = new Model_Podstrona($def_podstrona_id);
				$o_pobierzResponse->setRefererURL($podstrona->url[$route_in['jezyk_id']]);
			}
			
		}
		
		$o_pobierzResponse->dodajParametr("link_powrot",$link_powrot);
		return $o_pobierzResponse;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function listaAction($route_in) {
		
		$podstrona = new Model_Aktualnosc();
		$podstrona->pobierzPrzezUrl($route_in['jezyk'], $route_in['url']);
		$o_pobierzResponse = new Core_Response();
		$o_pobierzResponse->setModuleTemplate("podstrona");
		$o_pobierzResponse->setContent($podstrona->tresc[$route_in['jezyk']]);
		return $o_pobierzResponse;
	}
};
