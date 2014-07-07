<?php

class formkontakt_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'formkontakt';
		parent::__construct();
	}

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function wyswietlAction(array $route_in, $refererRoute_in = null) {

        $o_response = new Core_Response();
        
        $view = new formkontakt_View();
        
        $html = $view->wyswietlFormularz($route_in['jezyk_id']);
        
        $o_response->setContent($html);
        
        return $o_response;
    }

};
