<?php
class podstrona_Controller extends Core_ModuleController
{
	private $params = array();
	private $files = array();
	
	//public function __construct($request_in) {
	public function __construct($params) {
		$this->modul = 'podstrona';
		$this->params = $params;
		//$this->files = $request_in->getPliki();
		parent::__construct();
	}
	
	//================================================================
	public function zmienJezykAction($route_in) 
	{
		
		$podstrona = new Model_Podstrona();
		$podstrona->pobierzPrzezUrl($this->params->getParametr('jezyk_in'), $this->params->getParametr('url'));
		
		
		
		$html =$podstrona->adres[$this->params->getParametr('jezyk_out')];//.' >> '.$podstrona->id.' > '.$this->params->getParametr('url').' > '.$this->params->getParametr('jezyk_in');
		$o_indexResponse = new Core_Response();		
		$o_indexResponse->setContentType('ajax');	
		$o_indexResponse->setContent($html);
		
		return $o_indexResponse;		
	}
	
	//================================================================
	public function pobierzAction($route_in) {
		$podstrona = new Model_Podstrona();
		$podstrona->pobierzPrzezUrl($route_in['jezyk_id'], $route_in['url']);
		$o_pobierzResponse = new Core_Response();
		$o_pobierzResponse->setModuleTemplate($podstrona->szablon_id);
		$o_pobierzResponse->setLayoutTemplate($podstrona->szablon_glowny_id);
		$o_pobierzResponse->setPodstronaId($podstrona->id);
		$a_naglowki = array();
		$a_naglowki[1]['aktualnosci'] = "Aktualności";
		$a_naglowki[2]['aktualnosci'] = "News";
		
		$a_naglowki[1]['nowsze'] = "nowsze wiadomości";
		$a_naglowki[1]['starsze'] = "starsze wiadomości";
		
		$a_naglowki[2]['nowsze'] = "Next posts";
		$a_naglowki[2]['starsze'] = "Prev posts";
		
		$a_naglowki[1]['link_more'] = "więcej";
		$a_naglowki[2]['link_more'] = "more";
		$a_naglowki[1]['wolny'] = 'Termin wolny';
		$a_naglowki[1]['czesciowy'] = 'Termin częściowo zajęty';
		$a_naglowki[1]['zajety'] = 'Termin zajęty';
		$a_naglowki[1]['wybrany'] = 'Termin wybrany';
		
		$a_naglowki[2]['wolny'] = 'Date free';
		$a_naglowki[2]['czesciowy'] = 'Date partially taken';
		$a_naglowki[2]['zajety'] = 'Date taken';
		$a_naglowki[2]['wybrany'] = 'Choosen date';
		if(count($podstrona->errors)==0)
		{
			$modul = $podstrona->pobierzModul($route_in['jezyk_id']);
			$o_pobierzResponse->setContent($podstrona->tresc[$route_in['jezyk_id']]);
			$o_pobierzResponse->dodajParametr("podstrona",$podstrona);
			$o_pobierzResponse->dodajParametr("naglowki",$a_naglowki);
		}
		else
		{
			$o_pobierzResponse->setContent(implode("<br/>",$podstrona->errors));
		}
		return $o_pobierzResponse;
	}
};
