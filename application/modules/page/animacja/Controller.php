<?php
class animacja_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'animacja';
		parent::__construct();
	}
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
	public function pobierzAction(array $route_in, $refererRoute_in = null) {
		$o_response = new Core_Response();
		$filtr_modul = new Model_ZdjecieAnimacja();
		$filtr_modul->filtr_jezyk_id = $route_in['jezyk_id'];
		$filtr_modul->filtr_sortuj_po = 'id';
		
		if(!isset($route_in['aktywne']))
		{
			$filtr_modul->filtr_aktywne = 1;
		}
		$filtr_modul->filtrujRekordy();
		$a_zdjecia = array();
		if(count($filtr_modul->rekordy)>0) {
			foreach ($filtr_modul->rekordy as $index => $element_id) {
				$o = new Model_ZdjecieAnimacja($element_id);
				$a_zdjecia[] = $o;
			}
		}
		$view = new animacja_View();
		$html = $view->wyswietlAnimacje($route_in['jezyk_id'], $a_zdjecia, $route_in);
		$o_response->setContent($html);
		return $o_response;
	}
	
	//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
};
