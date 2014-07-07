<?php
class cennik_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'cennik';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	//============================================================================
	function wyswietlCennik($jezyk_id, array $cennik) {
		
		$html = '';
    
		$this->sm->assign('cennik', $cennik);
		$this->sm->assign('jezyk_id', $jezyk_id);		
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'cennik.tpl');	
		
		return $html;
	}	
	//============================================================================
	function wyswietlWiersz($jezyk_id, array $sekcja, array $wartosci) {
		
		$html = '';
		
		$a_naglowki = array();
		
		$a_naglowki[1]['cennik_wynajecie_podstawowa'] = "Broń podstawowa";
		$a_naglowki[1]['cennik_wynajecie_extra'] = "Broń extra";
		$a_naglowki[1]['cennik_sztuka'] = "szt.";
		$a_naglowki[1]['cennik_op'] = "szt.";
		
		$a_naglowki[2]['cennik_wynajecie_podstawowa'] = "Basic firearms";
		$a_naglowki[2]['cennik_wynajecie_extra'] = "Special firearms ";
		$a_naglowki[2]['cennik_sztuka'] = "rds";
		$a_naglowki[2]['cennik_op'] = "rds";
    
		$tpl = $sekcja['cs_szablon'];
		$this->sm->assign('sekcja', $sekcja);
		$this->sm->assign('wartosci', $wartosci);
		$this->sm->assign('naglowki', $a_naglowki);
		$this->sm->assign('jezyk_id', $jezyk_id);		
		$html =  $this->sm->fetch($this->moduleTemplateDir . '/wiersze/'.$tpl);	
		
		return $html;
	}	
	//============================================================================
	
};
