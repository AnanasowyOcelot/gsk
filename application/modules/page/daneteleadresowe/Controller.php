<?php

class daneteleadresowe_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'daneteleadresowe';
		parent::__construct();
	}

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function wyswietlAction(array $route_in, $refererRoute_in = null) {

        $o_response = new Core_Response();
        
        $view = new daneteleadresowe_View();
        
        $html = $view->wyswietlDane($route_in['jezyk_id']);
        
        $o_response->setContent($html);
        
        return $o_response;
    }

};
