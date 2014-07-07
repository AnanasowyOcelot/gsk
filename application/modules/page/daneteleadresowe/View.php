<?php

class daneteleadresowe_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'daneteleadresowe';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	
    //============================================================================
    function wyswietlDane($jezyk_id) {        
        $html = '';
    
        $this->sm->assign('jezyk_id', $jezyk_id);
        $html .=  $this->sm->fetch($this->moduleTemplateDir . 'dane.tpl');    
        
        return $html;
    }
    
};
