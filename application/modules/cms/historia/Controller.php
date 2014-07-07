<?php
class historia_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'historia';
		parent::__construct();
	}
	
	
	//================================================================================
	function pobierzAction($obiekt_klucz) {
		
		
		$o_indexResponse = new Core_Response();
		$o_indexResponse = $this->obslugaFormularza($o_requestIn);		
		$o_indexResponse->dodajParametr('form_nazwa', "dodaj");
		$o_indexResponse->dodajParametr('button_del', "0");
		
		return $o_indexResponse;
	}
	
	
	
};
