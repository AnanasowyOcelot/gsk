<?php

class video_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'video';
		parent::__construct();
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function wyswietlAction(array $route_in, $refererRoute_in = null) {

		$o_response = new Core_Response();

		$view = new video_View();

		$link = '/' . Core_Config::get('jezyk_skrot') . '/video';

		$url_video = strtolower(trim($route_in['parametry']));
		
		if(trim($url_video)!="") {

			$o_video = new Model_Video();
			$o_video->pobierzPrzezUrl($route_in['jezyk_id'], $url_video);
			
			$html = $view->wyswietl($route_in['jezyk_id'], $o_video, $link);

		} else {				
			$html = $view->BudujListeStronaGlowna($route_in['jezyk_id'],$route_in['strona']);
		}

		$o_response->setContent($html);

		return $o_response;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function wyslijAjaxAction($route_in)
	{
		$contetn = '';
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($contetn);
		
		return $o_indexResponse;		
	}

	
};
