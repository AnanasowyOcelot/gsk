<?php

class kontakt_Controller extends Core_ModuleController
{
	public $params = array();
	
	public function __construct($params) {
		$this->modul = 'kontakt';
		$this->params = $params;
		parent::__construct();
	}
	
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function wyslijAjaxAction($route_in)
	{
		$contetn = 'OK';
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		
		$contetn = Model_Kontakt::zapiszZapytanie($this->params);
		$o_indexResponse->setContent($contetn);
		
		return $o_indexResponse;		
	}

	
};
