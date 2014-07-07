<?php

class formularz_View extends Core_View
{	
	public function __construct() {
		$this->modul = 'formularz';
		parent::__construct();		
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}	
	//============================================================================
	public function formularzView($dane,$jezyk_id,$formularz_nazwa,$errors=array())
	{
		if($formularz_nazwa!='')
		{
			$this->sm->assign("dane",$dane);
			$this->sm->assign("errors",$errors);
			$html =  $this->sm->fetch($this->moduleTemplateDir.$formularz_nazwa.'.tpl');	
		}
		
		return $html;
	}
	//============================================================================
	public function komunikatView($komunikat,$jezyk_id)
	{
		$this->sm->assign("komunikat",$komunikat);
		$this->sm->assign("jezyk_id",$jezyk_id);
		$html =  $this->sm->fetch($this->moduleTemplateDir.'komunikat.tpl');	
	
		return $html;
	}
	//============================================================================
	
};
