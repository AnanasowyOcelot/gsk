<?php

class kontakt_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'kontakt';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	
	//============================================================================
	public function formularzKontaktView()
	{
		$html =  $this->sm->fetch($this->moduleTemplateDir . 'kontakt.tpl');	
		
		return $html;
	}
	//============================================================================
	
};
