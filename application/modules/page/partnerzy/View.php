<?php

class partnerzy_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'partnerzy';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	
	//============================================================================
	function BudujListe($jezyk_id) {
		
		$html = '';		
		$o_akt = new Model_Partner();
		$o_akt->filtr_jezyk_id = $jezyk_id;		
		$o_akt->filtr_sortuj_jak = "ASC";
        $o_akt->filtr_sortuj_po = "kolejnosc";		
		$o_akt->filtr_aktywna = 1;
		$o_akt->filtrujRekordy();
		
		$a_partnerzy = array();
		foreach($o_akt->rekordy as $index =>$akt_id)
		{	
			$partner = new Model_Partner($akt_id);						
			$a_partnerzy[$akt_id] = $partner;
		}
		
		$this->sm->assign('partnerzy', $a_partnerzy);		
		$this->sm->assign('jezyk_id', $jezyk_id);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'lista.tpl');	
	
		
		return $html;
	}
};
