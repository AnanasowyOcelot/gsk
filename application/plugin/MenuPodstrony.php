<?php
class Plugin_MenuPodstrony
{

	public function wyswietlMenuPodstronyDol($jezyk_id, $url_wybrana='')
	{
		//======== podstrona wybrana ================
		if($url_wybrana!='')
		{
			$podstrona_wybrana = new Model_Podstrona();
			$podstrona_wybrana->pobierzPrzezUrl($jezyk_id, $url_wybrana);
		}
		//=======================================
		$o_podstrony = new Model_Podstrona();
		$o_podstrony->filtr_menu_dol = 1;
		$o_podstrony->filtr_aktywna = 1;
		$o_podstrony->filtr_jezyk_id = $jezyk_id;
		$o_podstrony->filtr_sortuj_po = "kolejnosc";
		$o_podstrony->filtr_sortuj_jak = "ASC";
		$o_podstrony->filtrujPodstrony();


		$html = '';
		$index = 0;
		if(count($o_podstrony->rekordy )>0)
		{
			//$html .='<ul id="menuTop">';
			foreach ($o_podstrony->rekordy as $id_podstrony)
			{
				$o_podstrony->pobierz($id_podstrony);

				$select = '';// background-color:#EFEFEF;';

				if($url_wybrana!='')
				{
					if(in_array($o_podstrony->id,$podstrona_wybrana->nadrzedne))
					{
						$select = " active ";
					}
				}
                
				if($index>0)
				{
					$html .= '  |  ';
				}

				$adres = $o_podstrony->adres[$jezyk_id];
                
                $nazwa = $o_podstrony->nazwa[$jezyk_id];
                $nazwa = str_replace(' ', '&nbsp;', $nazwa);

				$adres = str_replace("%jezyk_id%",$jezyk_id,$adres);
				$adres = str_replace("%jezyk_skrot%",Core_Config::get("jezyk_skrot"),$adres);
				$html .= '<a href="'.$adres.'"  class="'.$select.'" >'.$nazwa.'</a>';

				$index++;
			}
			//$html .='</ul>';
		}


		return $html;
	}

	public function wyswietlMenuPodstronyGora($jezyk_id, $url_wybrana='')
	{
		//======== podstrona wybrana ================
		if($url_wybrana!='')
		{
			$podstrona_wybrana = new Model_Podstrona();
			$podstrona_wybrana->pobierzPrzezUrl($jezyk_id, $url_wybrana);
		}
		//=======================================
		$o_podstrony = new Model_Podstrona();
		$o_podstrony->filtr_menu_gora = 1;
		$o_podstrony->filtr_aktywna = 1;
		$o_podstrony->filtr_jezyk_id = $jezyk_id;
		$o_podstrony->filtr_sortuj_po = "kolejnosc";
		$o_podstrony->filtr_sortuj_jak = "ASC";
		$o_podstrony->filtrujPodstrony();

		$a_podrzedne = array();
		foreach ($o_podstrony->rekordy as $id_podstrony)
		{
			$o_podstrony->pobierz($id_podstrony);
			if($o_podstrony->id_nadrzedna != 0) {
				if(!is_array($a_podrzedne[$o_podstrony->id_nadrzedna])) {
					$a_podrzedne[$o_podstrony->id_nadrzedna] = array();
				}
				$a_podrzedne[$o_podstrony->id_nadrzedna][] = $o_podstrony->id;
			}
		}

		$o_podstrony->filtr_id_nadrzedna = 0;
		$o_podstrony->rekordy = array();
		$o_podstrony->filtrujPodstrony();

		$html = '';
		if(count($o_podstrony->rekordy )>0)
		{
			$html .='<ul class="nav nav-pills pull-right">';

			foreach ($o_podstrony->rekordy as $id_podstrony)
			{
				$o_podstrony->pobierz($id_podstrony);

				$select = '';

				if($url_wybrana!='')
				{
					if(in_array($o_podstrony->id,$podstrona_wybrana->nadrzedne))
					{
						$select = " active ";
					}
				}

				$html .= '<li><a href="'.$o_podstrony->adres[$jezyk_id].'"  class="'.$select.'" >'.str_replace(' ', '&nbsp;', $o_podstrony->nazwa[$jezyk_id]).'</a>';
				if(count($a_podrzedne[$o_podstrony->id])) {
					$o_podstrona_podrzedna = new Model_Podstrona();
					$html .='<ul>';
					foreach($a_podrzedne[$o_podstrony->id] as $podrzednaId) {
						$o_podstrona_podrzedna->pobierz($podrzednaId);
						$select = '';
						if(in_array($o_podstrona_podrzedna->id, $podstrona_wybrana->nadrzedne))
						{
							$select = " active ";
						}
						$html .= '<li><a href="'.$o_podstrona_podrzedna->adres[$jezyk_id].'" class="'.$select.'">'.str_replace(' ', '&nbsp;', $o_podstrona_podrzedna->nazwa[$jezyk_id]).'</a></li>';
					}
					$html .='</ul>';
				}
				$html .= '</li>';

			}
			$html .='</ul>';


			$html .= '<script>
                jQuery("ul#menuTop li a").each(function(){
                    jQuery(this).width( jQuery(this).width() );
                });
                jQuery("ul#menuTop li a.active").addClass("active2");
            </script> ';

		}

		return $html;
	}

	public function wyswietlMenuPodstronyPodrzedne($jezyk_id, $url_wybrana='')
	{
		//======== podstrona wybrana ================
		if($url_wybrana!='')
		{
			$podstrona_wybrana = new Model_Podstrona();
			$podstrona_wybrana->pobierzPrzezUrl($jezyk_id, $url_wybrana);
		}
		//=======================================

		$html = '';


		if(count($podstrona_wybrana->errors)==0)
		{
			$o_podstrony = new Model_Podstrona();
			$o_podstrony->filtr_menu_lewa = 1;
			$o_podstrony->filtr_aktywna = 1;
			$o_podstrony->filtr_jezyk_id = $jezyk_id;



			if(isset($podstrona_wybrana ))
			{
				if($podstrona_wybrana->id_nadrzedna==0){

					$o_podstrony->filtr_id_nadrzedna = $podstrona_wybrana->id;
				}
				else{
					$o_podstrony->filtr_id_nadrzedna = $podstrona_wybrana->id_nadrzedna;
				}
			}

			$o_podstrony->filtrujPodstrony();



			if(count($o_podstrony->rekordy )>0)
			{
				//$html .= '<div class="top">&nbsp;</div>';
				//$html .='<ul>';

				foreach ($o_podstrony->rekordy as $id_podstrony)
				{
					$o_podstrony->pobierz($id_podstrony);

					$select = ' background-color:#EFEFEF; ' ;

					if($url_wybrana!='')
					{
						if(in_array($o_podstrony->id,$podstrona_wybrana->nadrzedne))
						{
							$select = " background-color:#666666; ";
						}
					}
					//$html .= '<li><a href="'.$o_podstrony->adres[$jezyk_id].'">'.$select.$o_podstrony->nazwa[$jezyk_id].'</a>';
					$html .= '<div style="width:185px; -moz-border-radius: 5px; border-radius: 5px; border:1px solid #999999;  margin-bottom:10px;  '.$select.' " ><a style="text-decoration:none; display:block; padding:5px 10px;" href="'.$o_podstrony->adres[$jezyk_id].'">'.$o_podstrony->nazwa[$jezyk_id].'</a></div>';
				}

				//$html .='</ul>';
				//$html .= '<div class="bottom">&nbsp;</div>';
			}
		}
		return $html;
	}


	public function render() {
		$html = '';
		$db = Core_DB::instancja();
		$rekordy = $db->Execute('SELECT * FROM nawigacja WHERE aktywny = 1')->GetRows();
		$a_drzewka = $this->buildTree($rekordy);
		foreach($a_drzewka as $a_drzewko) {
			$html .= '<div class="top">&nbsp;</div>';
			$html .= $this->wyswietlDrzewo($a_drzewko);
			$html .= '<div class="bottom">&nbsp;</div>';
		}
		return $html;
	}
	private function wyswietlDrzewo($o_wezelGlowny) {
		$html = '';
		$html .= '<ul>';
		$html .= '<li>';
		$html .= $this->wyswietlWezel($o_wezelGlowny);
		$html .= '</li>';
		if(isset($o_wezelGlowny->children)) {
			$drzewo = $o_wezelGlowny->children;
			if(is_array($drzewo) && count($drzewo) > 0) {
				foreach($drzewo as $wezel) {
					$html .= '<li>';
					$html .= $this->wyswietlDrzewo($wezel);
					$html .= '</li>';
				}
			}
		}
		$html .= '</ul>';
		return $html;
	}
	private function wyswietlWezel($o_wezel) {
		if($o_wezel->modul != '') {
			$link = Core_Config::get('page_dir').$o_wezel->modul.'';
			if($o_wezel->akcja != '') {
				$link .= '/'.$o_wezel->akcja.'';
			}
		} elseif($o_wezel->url != '') {
			$link = Core_Config::get('page_dir').$o_wezel->url.'';
		} else {
			$link = 'javascript:;';


		}
		return '<a href="'.$link.'">'.$o_wezel->nazwa.'</a> ';
	}
	private function buildTree(array $rekordy) {
		$items = array();
		foreach($rekordy as $r) {
			$items[] = (object)$r;
		}
		$childs = array();
		foreach($items as $item) {
			$childs[$item->parent_id][] = $item;
		}
		foreach($items as $item) if (isset($childs[$item->id])) {
			$item->children = $childs[$item->id];
		}
		$tree = $childs[0];
		return $tree;
	}
}
