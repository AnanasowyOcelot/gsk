<?php
class nawigacja_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'nawigacja';
		parent::__construct();
	}
	//================================================================================
	function indexAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("lista");
		$komunikaty = array();
		$a_listaId = $o_requestIn->getParametr('id');
		if(is_array($a_listaId)) {
			foreach($a_listaId as $id) {
				$rekord = new Model_Nawigacja($id);
				$rekord->usun();
				$komunikaty[] = array('ok', 'Rekord '.$id.' został usunięty.');
			}
		}
		$na_strone = 50;
		$strona = (int)$o_requestIn->getParametr('s');
		$strona = max(1, $strona);
		$sql = "SELECT * FROM nawigacja";
		$sql_records = $sql.' LIMIT '.($na_strone * ($strona-1) ).', '.(int)$na_strone.'';
		
		//****************************************** DO PRZENIESIENIA GDZIES :) ******************/
		$link = Core_Config::get('cms_dir').'/'.$this->modul.'/index/';
		$rekordy_all = $this->db->Execute($sql)->GetRows();
		$o_porcjowarka = new Plugin_Porcjowarka(count($rekordy_all), $na_strone, $link);
		$porcjowarka = $o_porcjowarka->buduj($strona);
		//****************************************** DO PRZENIESIENIA GDZIES :) ******************/
		
		$rekordy = $this->db->Execute($sql_records)->GetRows();
		$o_indexResponse->dodajParametr("rekordy",$rekordy);
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr('porcjowarka', $porcjowarka);
		$o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
		return $o_indexResponse;
	}
	//============================================================================
	function dodajAction(Core_Request $o_requestIn) 
	{
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("form");

		$r = new Model_Nawigacja();
		$komunikaty = array();
		$a_rekord = $o_requestIn->getParametr('r');
		
		if(is_array($a_rekord)) {
			$r->fromArray($a_rekord);
			$komunikaty = $r->validate();
			if(count($komunikaty) == 0)
			{
				$r->zapisz();
				$this->setTemplate('komunikat');
				$komunikaty[] = array('ok', 'Rekord został dodany.');
			}
		}
		
		$modulySelect = Core_Narzedzia::wyswietlListeModulow($r->modul);		
		$o_indexResponse->dodajParametr('modulySelect', $modulySelect);
		
		
		$parentSelect = $this->db->Execute('SELECT id, nazwa FROM nawigacja WHERE id != '.(int)$r->id.'')->GetRows();
		$parentSelect = array_merge(array(array('id' => 0, 'nazwa' => '-- brak --')), $parentSelect);
		$o_indexResponse->dodajParametr('parentSelect', $parentSelect);
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', Core_Config::get('cms_dir').'/'.$this->modul.'/dodaj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr('r', $r);
		
		return $o_indexResponse;
	}
	//============================================================================
	function edytujAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("form");
		$r = new Model_Nawigacja((int)$o_requestIn->getParametr('id'));
		$komunikaty = array();
		$a_rekord = $o_requestIn->getParametr('r');
		if(is_array($a_rekord)) {
			$r->fromArray($a_rekord);
			$komunikaty = $r->validate();
			if(count($komunikaty) == 0)
			{
				$r->zapisz();
				$this->setTemplate('komunikat');
				$komunikaty[] = array('ok', 'Rekord został zapisany.');
			}
		}
		
		$modulySelect = Core_Narzedzia::wyswietlListeModulow($r->modul);		
		$o_indexResponse->dodajParametr('modulySelect', $modulySelect);
		
		$parentSelect = $this->db->Execute('SELECT id, nazwa FROM nawigacja WHERE id != '.(int)$r->id.'')->GetRows();
		$parentSelect = array_merge(array(array('id' => 0, 'nazwa' => '-- brak --')), $parentSelect);
		$o_indexResponse->dodajParametr('parentSelect', $parentSelect);		
		
		$o_indexResponse->dodajPlikJS("uzytkownik/js/ajax.js");
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', Core_Config::get('cms_dir').'/'.$this->modul.'/edytuj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr('r', $r);
		
		return $o_indexResponse;
	}
	//============================================================================
	function usunAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate('usun');
		$komunikaty = array();
		$idRekordu = (int)$o_requestIn->getParametr('id');
		if($idRekordu > 0) {
			$this->db->Execute('DELETE FROM nawigacja WHERE id = '.(int)$idRekordu);
			$komunikaty[] = array('ok', 'Rekord o id = ' . (int)$idRekordu . ' został usunięty.');
		}
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', '/'.$this->modul.'/dodaj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		return $o_indexResponse;
	}
};
