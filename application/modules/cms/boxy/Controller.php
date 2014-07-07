<?php
class boxy_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'boxy';
		parent::__construct();
	}
	
	//================================================================================
	private function obslugaFormularza(Core_Request $o_requestIn)
	{
		$r = new Model_Box((int)$o_requestIn->getParametr('id'));
		$engine_indexResponse = new Core_Response();
		$engine_indexResponse->setModuleTemplate("form");
		$komunikaty = array();
		$a_rekord = $o_requestIn->getParametr('r');
		if(is_array($a_rekord)) 
		{
			$r->fromArray($a_rekord);
			$r->setFiles($o_requestIn->getPliki());		
			
			$errors = Core_Narzedzia::validate($o_requestIn->getParametr('r'), $o_requestIn->getParametr('wymagane'));
			if(count($errors) == 0) 
			{
				$r->zapisz();
				$this->setTemplate('komunikat');
				$komunikaty[] = array('ok', 'Rekord został zapisany.');				
				$engine_indexResponse->dodajParametr('rekord_id', $r->id);
				$engine_indexResponse->setModuleTemplate("info");
				Model_Historia::zapiszRekord($r,$r->id,$this->modul,'zapis',1);
			}
			else
			{
				$engine_indexResponse->dodajParametr('errors', $errors);
				$komunikaty[] = array('error', 'Proszę wypełnić wymagane pola');	
			}
		}
		
		$klucz = $o_requestIn->getParametr('klucz');
	
		if(isset($klucz) && $klucz!='')
		{
			$r = Model_Historia::pobierzRekord($klucz);
			$komunikaty[] = array('warning', 'Przywrócenie wersji archiwalnej');
			$engine_indexResponse->dodajParametr('historiaOpen','1');
		}
			
		$v_historia = new historia_View();
		$v_historia = $v_historia->historiaObiektow($r->id,$this->modul,$this->modul, $klucz);		
		$engine_indexResponse->dodajParametr('historia_html', $v_historia);
		
		//================================================================================
		
		$a_jezyki = Model_Jezyk::pobierzWszystkie();
		$a_pole_tresc = array();
		foreach($a_jezyki as $idJezyka => $skrotJezyka) {
			$CKEditor = new CKEditor();
			$CKEditor->config['width'] = 537;
			$CKEditor->config['enterMode'] = 'CKEDITOR.ENTER_BR';
			$CKEditor->config['shiftEnterMode'] = 'CKEDITOR.ENTER_BR';
        	$CKEditor->config['filebrowserBrowseUrl'] = '/www/cms/filemanager/index.html';
			$config = array();
			$config['toolbar'] = array(
				array('Source', '-', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-', 'Subscript', 'Superscript', '-', 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Table'),
				array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
				array('Image', 'Link', 'Unlink', 'Anchor'),
				array('TextColor', 'BGColor')
			);
			$a_pole_tresc[$idJezyka] = $CKEditor->editor('r[opis]['.$idJezyka.']', $r->opis[$idJezyka], $config);
		}
		
		$parentSelect ='<option value="0"> -- brak -- </option>';
		$parentSelect .=Core_Narzedzia::wyswietlListePodstron(0,1,0,$r->podstrona_id[0],$r->id);
		$engine_indexResponse->dodajParametr('parentSelect', $parentSelect);
		$engine_indexResponse->dodajParametr('jezyki', $a_jezyki);
		$engine_indexResponse->dodajParametr('r', $r);
		$engine_indexResponse->dodajParametr('pole_tresc', $a_pole_tresc);		
		$engine_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$engine_indexResponse->dodajParametr('link_form', Core_Config::get('cms_dir').'/'.$this->modul.'/edytuj/');
		$engine_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$engine_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
		
		
		return $engine_indexResponse;
	}
	
	//================================================================================
	public function indexAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("lista");
		$na_strone = 15;
		$jezyk_id = 1;
		
		$komunikaty = array();
		$a_listaId = $o_requestIn->getParametr('id');
		if(is_array($a_listaId)) {
			foreach($a_listaId as $id) {
				$rekord = new Model_Box();
				$rekord->usun($id);
				$komunikaty[] = array('ok', 'Rekord '.$id.' został usunięty.');
			}
		}
		/********************* POBIERANIE ************************************/
		$sort_kolumna = '';
		$sort_typ = '';
		$parametry = array();
		if($o_requestIn->getParametr('col')=='') { $sort_kolumna = "id"; } else { $sort_kolumna = $o_requestIn->getParametr('col');  $parametry['col'] = $sort_kolumna; }
		if($o_requestIn->getParametr('typ')=='') { $sort_typ = "desc"; } else { $sort_typ = $o_requestIn->getParametr('typ'); $parametry['typ'] = $sort_typ;  }
		if($o_requestIn->getParametr('s')=='') { $strona = 1; } else { $strona = $o_requestIn->getParametr('s'); /*$parametry['s'] = $strona;*/  }
		$filtr_modul = new Model_Box();
		$filtr_modul->filtr_strona = $strona;
		$filtr_modul->filtr_sortuj_po = $sort_kolumna;
		$filtr_modul->filtr_sortuj_jak = $sort_typ;
		$filtr_modul->filtr_ilosc_wynikow = $na_strone;
		$filtr_modul->filtr_jezyk_id = $jezyk_id;
		$parametry_szukaj = array(); // link + parametry szukaj do sortowania po kolumnach 
		if((int)$o_requestIn->getParametr('id')>0) { $parametry_szukaj['id'] =$filtr_modul->filtr_id = $o_requestIn->getParametr('id');  }
		if($o_requestIn->getParametr('nazwa')!="") { $parametry_szukaj['nazwa'] =$filtr_modul->filtr_nazwa = $o_requestIn->getParametr('nazwa');  }
		
		$filtr_modul->filtrujRekordy();
		
		$rekordy = array();
		if(count($filtr_modul->rekordy)>0)
		{
			foreach ($filtr_modul->rekordy as $index => $element_id)
			{				
				$o = new Model_Box($element_id);
				
				if((int)$o->id>0)
				{
					$mkat = new Model_Menukategoria($o->kategoria_menu_id);
					
					$o->nazwa_kategorii_menu = $mkat->nazwa[$jezyk_id];
					
					$rekordy[] = $o;
				}
			}
		}
		
		
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
		$o_porcjowarka = new Plugin_Porcjowarka($filtr_modul->ilosc_rekordow, $na_strone, $link, $a_parametry);
		$porcjowarka = $o_porcjowarka->buduj($strona);
		//************************************************************/
		
		$_SESSION['podstrona']['link_powrot'] = $v_parametry_powrot;
		$o_indexResponse->dodajParametr("lista",$rekordy);
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('porcjowarka', $porcjowarka);
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr("jezyk_id",$jezyk_id);
		$o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
		return $o_indexResponse;
	}
	
	//================================================================================
	function dodajAction(Core_Request $o_requestIn) {
		$o_indexResponse = $this->obslugaFormularza($o_requestIn);		
		$o_indexResponse->dodajParametr('form_nazwa', "dodaj");
		$o_indexResponse->dodajParametr('button_del', "0");
		return $o_indexResponse;
	}
	//============================================================================
	function przywrocAction(Core_Request $o_requestIn) {
		$o_indexResponse = $this->obslugaFormularza($o_requestIn);		
		$o_indexResponse->dodajParametr('form_nazwa', "edycja");
		$o_indexResponse->dodajParametr('button_del', "1");
		$o_indexResponse->dodajParametr('formToken', 0);
		return $o_indexResponse;
	}
	
	//============================================================================
	function edytujAction(Core_Request $o_requestIn) {
		$o_indexResponse = $this->obslugaFormularza($o_requestIn);
		$o_indexResponse->dodajParametr('button_del', "1");
		$o_indexResponse->dodajParametr('form_nazwa', "edycja");
		return $o_indexResponse;
	}
	//============================================================================
	function usunAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate('usun');
		$idRekordu = (int)$o_requestIn->getParametr('id');
		
		$obj = new Model_Box();
		$komunikaty = $obj->usun($idRekordu);
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', '/'.$this->modul.'/dodaj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		return $o_indexResponse;
	}
}
