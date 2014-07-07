<?php
class podstrona_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'podstrona';
		parent::__construct();
	}
	
	//================================================================================
	private function obslugaFormularza(Core_Request $o_requestIn)
	{
		$r = new Model_Podstrona((int)$o_requestIn->getParametr('id'));
		$engine_indexResponse = new Core_Response();
		$engine_indexResponse->setModuleTemplate("form");		
		
		$komunikaty = array();
		$a_rekord = $o_requestIn->getParametr('r');
		if(is_array($a_rekord)) {
			$r->fromArray($a_rekord);
			$komunikaty = $r->validate();
			if(count($komunikaty) == 0) {
				$r->zapisz();
				$this->setTemplate('komunikat');
				$komunikaty[] = array('ok', 'Rekord został zapisany.');
				$engine_indexResponse->setModuleTemplate("info");
			}
		}
		else {
			
			$a_jezyki = Model_Jezyk::pobierzWszystkie();
			$a_pole_tresc = array();
			foreach($a_jezyki as $idJezyka => $skrotJezyka) {
				$CKEditor = new CKEditor();
				$CKEditor->config['width'] = 537;
				//$CKEditor->config['enterMode'] = 'CKEDITOR.ENTER_BR';
				//$CKEditor->config['shiftEnterMode'] = 'CKEDITOR.ENTER_BR';
              	$CKEditor->config['filebrowserBrowseUrl'] = '/www/cms/filemanager/index.html';

				$config = array();
				$config['toolbar'] = array(
					array('Source', '-', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-', 'Subscript', 'Superscript', '-', 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Table'),
					array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
					array('Image', 'Link', 'Unlink', 'Anchor'),
					array('TextColor', 'BGColor')
				);
				//Core_Narzedzia::drukuj($CKEditor->config);

				$a_pole_tresc[$idJezyka] = $CKEditor->editor('r[tresc]['.$idJezyka.']', $r->tresc[$idJezyka], $config);
			}
			
			
			$parentSelect ='<option value="0"> -- brak -- </option>';
			$parentSelect .= Core_Narzedzia::wyswietlListePodstron(0,1,0,$r->id_nadrzedna,$r->id);
			$engine_indexResponse->dodajParametr('parentSelect', $parentSelect);
			
			$szablonSelect = '<option value=""> -- brak -- </option>';
			if ($handle = opendir(Core_Config::get('application_path').'modules/page/podstrona/views' )) {
				while (false !== ($entry = readdir($handle))) {
					if($entry != '.' && $entry != '..') {
						$szablonIdTmp = substr($entry, 0, -4);
						$szablonSelect .= '<option';
						if($szablonIdTmp == $r->szablon_id) {
							$szablonSelect .= ' selected="selected"';
						}
						$szablonSelect .= ' value="'.$szablonIdTmp.'">'.$szablonIdTmp.'</option>';
					}
				}
				closedir($handle);
			}
			$engine_indexResponse->dodajParametr('szablonSelect', $szablonSelect);

			$szablonHtml = podstrona_View::listaElementySelect();
			$engine_indexResponse->dodajParametr('szablonHtml', $szablonHtml);
			
			$listaElementowHtml = podstrona_View::listaElementy($r);
			$engine_indexResponse->dodajParametr('listaElementowHtml', $listaElementowHtml);

			$engine_indexResponse->dodajPlikJS("podstrona/js/engine.js");
			$engine_indexResponse->dodajParametr('link_form', Core_Config::get('cms_dir').'/'.$this->modul.'/edytuj/');
			$engine_indexResponse->dodajParametr('jezyki', $a_jezyki);
			$engine_indexResponse->dodajParametr('r', $r);
			$engine_indexResponse->dodajParametr('pole_tresc', $a_pole_tresc);
		}
		$engine_indexResponse->dodajParametr("link_powrot",$_SESSION['podstrona']['link_powrot']);
		$engine_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$engine_indexResponse->dodajParametr('komunikaty', $komunikaty);
		
		//=======================================================================================
		
		
		return $engine_indexResponse;
	}
	//================================================================================
	function indexAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("lista");
		$this->akcja = "index";
		$komunikaty = array();
		
		//************************************************************/
		$a_listaId = $o_requestIn->getParametr('id_zaznaczone');		
		if(is_array($a_listaId)) {
			foreach($a_listaId as $id) {
				Model_Podstrona::usun($id);
				$komunikaty[] = array('ok', 'Rekord '.$id.' został usunięty.');
			}
		}
		//************************************************************/
				
		$na_strone = 15;
		$jezyk_id = 1;
		$sort_kolumna = '';
		$sort_typ = '';
		$parametry = array();
		if($o_requestIn->getParametr('col')=='') { $sort_kolumna = "nazwa"; } else { $sort_kolumna = $o_requestIn->getParametr('col');  $parametry['col'] = $sort_kolumna; }
		if($o_requestIn->getParametr('typ')=='') { $sort_typ = "asc"; } else { $sort_typ = $o_requestIn->getParametr('typ'); $parametry['typ'] = $sort_typ;  }
		if($o_requestIn->getParametr('s')=='') { $strona = 1; } else { $strona = $o_requestIn->getParametr('s'); /*$parametry['s'] = $strona;*/  }
		$filtr_podstrony = new Model_Podstrona();
		$filtr_podstrony->filtr_strona = $strona;
		$filtr_podstrony->filtr_sortuj_po = $sort_kolumna;
		$filtr_podstrony->filtr_sortuj_jak = $sort_typ;
		$filtr_podstrony->filtr_ilosc_wynikow = $na_strone;
		$filtr_podstrony->filtr_jezyk_id = $jezyk_id;
		$parametry_szukaj = array(); // link + parametry szukaj do sortowania po kolumnach 
		if((int)$o_requestIn->getParametr('id')>0) { $parametry_szukaj['id'] =$filtr_podstrony->filtr_id = $o_requestIn->getParametr('id');  }
		if($o_requestIn->getParametr('nazwa')!="") { $parametry_szukaj['nazwa'] =$filtr_podstrony->filtr_nazwa = $o_requestIn->getParametr('nazwa');  }
		if($o_requestIn->getParametr('modul')!="") { $parametry_szukaj['modul'] =$filtr_podstrony->filtr_modul = $o_requestIn->getParametr('modul');  }
		$filtr_podstrony->filtrujPodstrony();
		//************************************************************/
		$v_parametry_link = '';
		$a_tmp = array();
		if(count($parametry_szukaj)>0)
		{
			foreach ($parametry_szukaj as $nazawa => $wartosc)
			{
				$a_tmp[] = $nazawa.':'.$wartosc;
			}
		}
		$v_parametry_link = implode(",",$a_tmp);
		//************************************************************/
				
		$a_parametry = array_merge($parametry, $parametry_szukaj);		
		//************************************************************/
		$a_powrot = array();
		$a_powrot [] = "s:".$strona;	
		if(count($a_parametry)>0)
		{
			foreach ($a_parametry as $nazawa => $wartosc)
			{
				$a_powrot [] = $nazawa.':'.$wartosc;
			}
		}			
		$v_parametry_powrot = implode(",",$a_powrot);
		//************************************************************/
		$link = Core_Config::get('cms_dir').'/'.$this->modul.'/index/';		
		$o_porcjowarka = new Plugin_Porcjowarka($filtr_podstrony->ilosc_rekordow, $na_strone, $link, $a_parametry);
		$porcjowarka = $o_porcjowarka->buduj($strona);
		//************************************************************/
		$rekordy = array();
		if(count($filtr_podstrony->rekordy)>0)
		{
			foreach ($filtr_podstrony->rekordy as $index => $podstrona_id)
			{
				$o = new Model_Podstrona($podstrona_id);
				if((int)$o->id>0)
				{
					$html = '';
					foreach($o->nadrzedne as $n)
					{
						$rr = new Model_Podstrona($n);
						$html .= '<span style="verticalaq-align:middle; top:0px;"><b>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp; </b></span><span style="vertical-align:middle;">'.$rr->nazwa[1].'</span>';
					}
					//$rekordy[$index]['podstrona_nazwa'] = $html;
					$o->nazwa[$jezyk_id] = $html;
					$rekordy[] = $o;
				}
			}
		}
//		echo "UPRAWNIENIA ".$this->modul."<pre>";
//		print_r($o_requestIn->getUprawnieniaModul('podstrona'));
//		echo "</pre>";
		
		
		$_SESSION['podstrona']['link_powrot'] = $v_parametry_powrot;
	
		//$o_indexResponse->dodajPlikJS($this->modul."/js/engine.js");
		$o_indexResponse->dodajParametr("lista",$rekordy);
		$o_indexResponse->dodajParametr("link_parametry",$v_parametry_link);
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr("parametry",$parametry_szukaj);
		$o_indexResponse->dodajParametr("jezyk_id",$jezyk_id);
		$o_indexResponse->dodajParametr("modul",$this->modul);
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('porcjowarka', $porcjowarka);
		$o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul('podstrona'));
		
		
		return $o_indexResponse;
	}
	//============================================================================
	function dodajAction(Core_Request $o_requestIn) {
		
		$o_indexResponse = new Core_Response();		
		$o_indexResponse = $this->obslugaFormularza($o_requestIn);		
		$o_indexResponse->dodajParametr('button_del', "0");
		$o_indexResponse->dodajParametr('form_nazwa', "dodaj");
		$this->akcja = "dodaj";
		return $o_indexResponse;
	}
	//============================================================================
	function edytujAction(Core_Request $o_requestIn) {
		
		$o_indexResponse = new Core_Response();
		$o_indexResponse = $this->obslugaFormularza($o_requestIn);
		$o_indexResponse->dodajParametr('button_del', "1");
		$o_indexResponse->dodajParametr('form_nazwa', "edycja");
		$this->akcja = "edytuj";
		
		return $o_indexResponse;
	}
	//============================================================================
	function usunAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate('usun');
		$this->akcja = "usun";
		$komunikaty = array();
		$idRekordu = (int)$o_requestIn->getParametr('id');
		if($idRekordu > 0) {
			$this->db->query('DELETE FROM podstrony WHERE podstrona_id = '.(int)$idRekordu);
			$this->db->query('DELETE FROM podstrony_opisy WHERE podstrona_id = '.(int)$idRekordu);
			$komunikaty[] = array('ok', 'Rekord o id = ' . (int)$idRekordu . ' został usunięty.');
		}
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', '/'.$this->modul.'/dodaj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		return $o_indexResponse;
	}
}
