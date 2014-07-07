<?php

class podstrona_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'podstrona';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	
	//============================================================================
	public function podstronaView($tresc)
	{
		$this->sm->assign("tresc",$tresc);
		$html =  $this->sm->fetch($this->moduleTemplateDir . 'podstrona.tpl');	
		
		return $html;
	}
	//============================================================================
	public function formularzZgloszeniaView($request_in)
	{
		
		//$this->files = $request_in->getPliki();
		$this->sm->assign("dane",$request_in->getParametry());
		$html =  $this->sm->fetch($this->moduleTemplateDir . 'formularze/zgloszenie_formularz.tpl');	
		
		return $html;
	}
	//============================================================================
	
};
