<?php

class galeria_View extends Core_View
{
	public function __construct() {
		$this->modul = 'galeria';
		parent::__construct();
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}

	//============================================================================
	function wyswietlADGallery($jezyk_id, Model_Galeria $galeria, $linkDoGalerii = '') {
		$html = '';

		$this->sm->assign('galeria', $galeria);
		$this->sm->assign('jezyk_id', $jezyk_id);
		$this->sm->assign('linkDoGalerii', $linkDoGalerii);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'galeria.tpl');

		return $html;
	}

	//============================================================================
	function wyswietlADGalleryMini($jezyk_id, Model_Galeria $galeria, $linkDoGalerii = '') {
		$html = '';

		$this->sm->assign('galeria', $galeria);
		$this->sm->assign('jezyk_id', $jezyk_id);
		$this->sm->assign('linkDoGalerii', $linkDoGalerii);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'galeria_mini.tpl');

		return $html;
	}
};
