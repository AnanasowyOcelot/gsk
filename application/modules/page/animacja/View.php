<?php

class animacja_View extends Core_View
{
	public function __construct() {
		$this->modul = 'animacja';
		parent::__construct();
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}

	//============================================================================
	function wyswietlAnimacje($jezyk_id, array $a_zdjecia, array $a_parametry = array()) {
		$html = '';
        
		$this->sm->assign('a_zdjecia', $a_zdjecia);
       		$this->sm->assign('jezyk_id', $jezyk_id);        		
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'animacja.tpl');

		return $html;
	}
};
