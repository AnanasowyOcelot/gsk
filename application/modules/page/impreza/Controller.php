<?php
class impreza_Controller extends Core_ModuleController
{
	public $params = array();

	public function __construct($params) {
		$this->modul = 'impreza';
		$this->params = $params;
		parent::__construct();
	}

	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function panelZapytajAction(array $route_in, $refererRoute_in = null) {

		$view = new impreza_View();
		$html = $view->wyswietlZapytaj($this->params->getParametr('jezyk_id'));
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setContentType('ajax');
		$o_indexResponse->setContent($html);

		return $o_indexResponse;
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function imprezaZapytanieAction(array $route_in, $refererRoute_in = null)
	{
		//$router = print_r($this->params->getParametr('daneform'),1);

		$o_impreza = new Model_Impreza();

		$wynik = $o_impreza->zapytanie($this->params->getParametr('daneform'));
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setContentType('ajax');
		$o_indexResponse->setContent($wynik);

		return $o_indexResponse;
	}

};
