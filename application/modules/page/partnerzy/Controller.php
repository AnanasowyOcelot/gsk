<?php



class partnerzy_Controller extends Core_ModuleController

{

	public function __construct($params=array()) {

		$this->modul = 'partnerzy';

		parent::__construct($params);

	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function listaAjaxAction(array $route_in, $refererRoute_in = null)
	{	
		
		$view_partnerzy  = new partnerzy_View();	
		$body = $view_partnerzy->BudujListe($route_in['jezyk_id']);
		
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($router.' '.$body);
		
		return $o_indexResponse;		
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

	
	

};

