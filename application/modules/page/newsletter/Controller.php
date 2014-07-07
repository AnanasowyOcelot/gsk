<?php

class newsletter_Controller extends Core_ModuleController
{
	protected $params = array();

	public function __construct($params) {
		$this->modul = 'newsletter';

		$this->params = $params;
		parent::__construct();

		//echo "===>".print_r($params,true);
	}


	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function zapiszAjaxAction(array $route_in, $refererRoute_in = null)
	{
		$o_newsletter = new Model_Newsletter();
		$odp = $o_newsletter->zapiszSubskrybenta($this->params->getParametr('e_mail'));

		//$contetn = 'ZAPIS DO NEWSLETTERA ==>'.$wynik."<==";

		$o_indexResponse = new Core_Response();
		$o_indexResponse->setContentType('ajax');
		$o_indexResponse->setContent($odp);

		return $o_indexResponse;
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function panelAjaxAction(array $route_in, $refererRoute_in = null)
	{
		$view = new newsletter_View();
		$html = $view->wyswietlPanel($route_in['jezyk_id']);
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setContentType('ajax');
		
		//$html = Core_Config::get("jezyk_id");
		$o_indexResponse->setContent($html);

		return $o_indexResponse;
	}
};
