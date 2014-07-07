<?php

class newsletter_View extends Core_View
{
	public function __construct()
	{
		$this->modul = 'newsletter';
		parent::__construct();
		$this->moduleTemplateDir = Core_Config::get('modules_path') . $this->modul . '/views/';
	}

	//============================================================================
	public function formularzZapiszView()
	{
		$html = $this->sm->fetch($this->moduleTemplateDir . 'form.tpl');
		return $html;
	}

	//============================================================================
	function wyswietlPanel($jezyk_id)
	{
		$a_naglowki = array();

		$a_naglowki[1]['button']   = "zapisz";
		$a_naglowki[1]['naglowek'] = "Wpisz swój adres email";
		$a_naglowki[1]['tekst']    = "Jeżli chcesz otrzymywać najświeższe informacje związane z&nbsp;wydarzeniami na naszej strzelnicy oraz być na bieżąco z aktulanymi promocjami zapisz się do bezpłtnego newslettera.";

		$a_naglowki[2]['button']   = "sign in";
		$a_naglowki[2]['naglowek'] = "If you want to receive latest news relating to events on our range and keep up to date with our current promotions please subscribe to our free newsletter.";

		$html = '';
		$this->sm->assign('naglowki', $a_naglowki);
		$this->sm->assign('jezyk_id', $jezyk_id);
		$this->sm->assign('jezyk_skrot', $jezyk_skrot);
		$html .= $this->sm->fetch($this->moduleTemplateDir . 'form.tpl');


		return $html;
	}
}
