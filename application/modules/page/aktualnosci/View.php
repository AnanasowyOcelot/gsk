<?php
class aktualnosci_View extends Core_View
{
	public function __construct() {
		$this->modul = 'aktualnosci';
		parent::__construct();
		$this->moduleTemplateDir = Core_Config::get('modules_path').$this->modul . '/views/';
	}
	//============================================================================
    function wyswietlBox($jezyk_id, Model_Aktualnosc $o_aktualnosc) {
        $tytul = $o_aktualnosc->tytul[$jezyk_id];
        $tresc = $o_aktualnosc->tresc[$jezyk_id];
        
        $html = '';
        $this->sm->assign('tytul', $tytul);
        $this->sm->assign('tresc', $tresc);
        $this->sm->assign('jezyk_id', $jezyk_id);
        $html .=  $this->sm->fetch($this->moduleTemplateDir . 'box.tpl');
        return $html;
    }
    //============================================================================
	function wyswietl($jezyk_id, Model_Aktualnosc $o_aktualnosc, $linkDoAktualnosci = '') {

		$html = '';
		$this->sm->assign('aktualnosc', $o_aktualnosc);
		$this->sm->assign('jezyk_id', $jezyk_id);
		$this->sm->assign('link_powrot', $linkDoAktualnosci);
		$html .=  $this->sm->fetch($this->moduleTemplateDir . 'szczegoly.tpl');
		return $html;
	}
	//============================================================================
	function BudujListeStronaGlowna($jezyk_id,$strona) {

		$html = '';

		$na_strone = 15;
		$o_akt = new Model_Aktualnosc();
		$o_akt->filtr_jezyk_id = $jezyk_id;
		//$o_akt->filtr_ilosc_wynikow = $na_strone;
		$o_akt->filtr_strona = $strona;
		$o_akt->filtr_sortuj_jak = "DESC";
		$o_akt->filtr_sortuj_po = "data_wydarzenia";

		$o_akt->filtr_aktywna = 1;
		$o_akt->filtrujRekordy();

		$a_aktualnosci = array();
		foreach($o_akt->rekordy as $index =>$akt_id)
		{
			$aktualnosc = new Model_Aktualnosc($akt_id);
			$a_aktualnosci[$akt_id] = $aktualnosc;
		}
		//$link = '/pl/aktualnosci/';
		//$a_parametry = array();
		//$parametry_strony = '';
		//$o_porcjowarka = new Plugin_Porcjowarka($o_akt->ilosc_rekordow, $na_strone, $link, $a_parametry);
		//$porcjowarka = $o_porcjowarka->buduj($strona,$parametry_strony);

		$a_strony = array_chunk($a_aktualnosci, 3);

		$html = '';

		//$html = count($a_aktualnosci);

		$a_naglowki = array();
		$a_naglowki[1]['link_more'] = "więcej";
		$a_naglowki[2]['link_more'] = "more";

		
		foreach ($a_strony as $index => $lista_akt)
		{
			$html .='<div style="width:322px; height:150px;">';

			foreach ($lista_akt as $numer => $aktualnosc)
			{
				$html .= '<div  class="wierszAktualnosc">';
				$html .= '<div class="tresc">'.$aktualnosc->podajSkroconaOpis($jezyk_id, 80).'</div>';
				//$html .= '<div class="link"><a href="javascript:void(0);" onClick="popupNews('.$aktualnosc->id.','.$jezyk_id.')">więcej</a></div>';
				$html .= '<div class="link"><a href="/aktualnosci/widokAjax/id:'.$aktualnosc->id.',lang:'.$jezyk_id.'" class="various fancybox.ajax" >'.$a_naglowki[$jezyk_id]['link_more'].'</a></div>';

				$html .= '</div>';
			}

			$html .='</div>';
		}



		//$this->sm->assign('aktualnosci', $a_aktualnosci);
		//	$this->sm->assign('porcjowarka', $porcjowarka);
		//$this->sm->assign('jezyk_id', $jezyk_id);
		//$html .=  $this->sm->fetch($this->moduleTemplateDir . 'lista_strona_glowna.tpl');


		return $html;
	}
};
