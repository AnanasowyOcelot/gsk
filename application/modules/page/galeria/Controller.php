<?php
class galeria_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'galeria';
		parent::__construct();
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function showAction(array $route_in, $refererRoute_in = null)
	{
		
		$id_galeria = $route_in['parametry_lista']['id'];
		
		$id_galeria = 70;
		
		$o_gal = new Model_Galeria($id_galeria);
		
		
		$tmp = '';
		$a_elementy = array();
		foreach ($o_gal->zdjecia as $index => $sciezka)
		{
			$a_elementy[] = '"/images/galerie/0/'.$sciezka.'" : "" ';
		}
		
		$body = '{'.implode(",",$a_elementy).'}';
		
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($router.' '.$body);
		
		return $o_indexResponse;		
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzPrzezIdAction(array $route_in, $refererRoute_in = null) {
		$o_response = new Core_Response();
		$galeria = new Model_Galeria($route_in['galeria_id']);
		$view = new galeria_View();
		$link = '';
		switch ($route_in['galeria_typ']) {
			case 'mini':
				// TU LINK DO GALERII
				$link = '/' . Core_Config::get('jezyk_skrot') . '/Galeria';
				$html = $view->wyswietlADGalleryMini($route_in['jezyk_id'], $galeria, $link);
				break;
			default:
				$html = $view->wyswietlADGallery($route_in['jezyk_id'], $galeria, $link);
				break;
		}
		$o_response->setContent($html);
		return $o_response;
	}
	/*
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzAction($route_in, $refererRoute_in) {
	$galeria = new Model_Galeria();
	$galeria->pobierzPrzezUrl($route_in['jezyk_id'], $route_in['url']);
	$o_pobierzResponse = new Core_Response();
	$o_pobierzResponse->setModuleTemplate("galeria");
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
	$podstrona = new Model_Podstrona();
	$podstrona->pobierzPrzezUrl($route_in['jezyk'], $route_in['url']);
	$o_pobierzResponse = new Core_Response();
	$o_pobierzResponse->setModuleTemplate("podstrona");
	$o_pobierzResponse->setContent($podstrona->tresc[$route_in['jezyk']]);
	return $o_pobierzResponse;
	}
	*/
};
