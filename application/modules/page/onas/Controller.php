<?php

class onas_Controller extends Core_ModuleController
{
	protected $params = array();

	public function __construct($params) {
		$this->modul = 'onas';

		$this->params = $params;
		parent::__construct();
	}

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function indexAjaxAction(array $route_in, $refererRoute_in = null)
    {
        $o_podstrona = new Model_Podstrona(70);
        
        $view = new onas_View();
        $html = $view->wyswietl($o_podstrona, $route_in['jezyk_id']);
        
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setContentType('ajax');
        $o_indexResponse->setContent($html);
        return $o_indexResponse;
    }
};
