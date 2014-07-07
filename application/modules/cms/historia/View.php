<?php

class historia_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'historia';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	
	//============================================================================
	public function historiaObiektow($obiekt_id, $obiekt_typ, $modul, $klucz)
	{
		$obj = new Model_Historia();
		$a_historia = $obj->pobierzHistorie($obiekt_id,$obiekt_typ);
		
		$this->sm->assign('link_modul', Core_Config::get('cms_dir').'/'.$modul.'/');
		$this->sm->assign("lista_historia",$a_historia);	
		$this->sm->assign("klucz",$klucz);
		$html =  $this->sm->fetch($this->moduleTemplateDir . 'listaHistoria.tpl');			
		
		return $html;
	}
	//============================================================================
	
};
