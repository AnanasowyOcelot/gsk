<?php

class onas_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'onas';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	
	//============================================================================
	function wyswietl(Model_Podstrona $o_podstrona, $jezyk_id) {
		
		$html = '';		
		$this->sm->assign('tresc', $o_podstrona->tresc[$jezyk_id]);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'index.tpl');	
		return $html;
	}
};
