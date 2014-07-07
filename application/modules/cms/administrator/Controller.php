<?php

class administrator_Controller extends Core_ModuleController
{
	public function __construct() {
		$this->modul = 'administrator';
		parent::__construct();
	}

	//============================================================================
	function indexAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("lista");

		$na_strone = 10;

		$strona = (int)$o_requestIn->getParametr('s');
		$strona = max(1, $strona);

		$sql = "SELECT
				administrator_id AS id,
	 			administrator_grupa_id	 AS grupa_id, 
	 			administrator_login AS login,	 			
	 			administrator_imie AS imie,
	 			administrator_nazwisko AS nazwisko, 
	 			administrator_email AS email,
	 			administrator_aktywny AS aktywny,
	 			grupa_nazwa AS grupa_nazwa, 
	 			grupa_id
			FROM 
				administratorzy ,
				administratorzy_grupy
			WHERE 
				grupa_id=administrator_grupa_id 
			ORDER BY id";
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
		$o_indexResponse->dodajParametr('porcjowarka', $porcjowarka);

		return $o_indexResponse;
	}

	//============================================================================
	function ajaxAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		//$o_indexResponse->dodajParametr('test', "test");
		$o_indexResponse->setContent("ping z ajaxAction :)");
		$o_indexResponse->setContentType(Core_Response::CONTENT_TYPE_AJAX );

		return $o_indexResponse;
	}

	//============================================================================
	function dodajAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("form");

		$r = new Model_Administrator();

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

		

		$administrator_Widok = new administrator_View();
		$select_grupy = $administrator_Widok->selectGrupy();


		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', Core_Config::get('cms_dir').'/'.$this->modul.'/dodaj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr('selectGrupy', $select_grupy);
		$o_indexResponse->dodajParametr('r', $r);

		return $o_indexResponse;
	}

	//============================================================================
	function edytujAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("form");

		$r = new Model_Administrator((int)$o_requestIn->getParametr('id'));

		$komunikaty = array();
		$a_rekord = $o_requestIn->getParametr('r');
		if(is_array($a_rekord)) {
			$r->fromArray($a_rekord);

			$komunikaty = array();//$r->validate();

			if(count($komunikaty) == 0)
			{
				$r->zapisz();
				$this->setTemplate('komunikat');
				$komunikaty[] = array('ok', 'Rekord został zapisany.');
			}
		}


		$administrator_Widok = new administrator_View();
		$select_grupy = $administrator_Widok->selectGrupy($r->grupa_id);


		$o_indexResponse->dodajPlikJS("uzytkownik/js/ajax.js");
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', Core_Config::get('cms_dir').'/'.$this->modul.'/edytuj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr('selectGrupy', $select_grupy);
		$o_indexResponse->dodajParametr('r', $r);

		return $o_indexResponse;
	}

	//============================================================================
	function usunAction(Core_Request $o_requestIn) {
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate('usun');


		$komunikaty = array();
		$idRekordu = (int)$o_requestIn->getParametr('id');
		echo "==>".$idRekordu;
		if($idRekordu > 0) {
			$this->db->Execute('DELETE FROM administratorzy WHERE administrator_id = '.(int)$idRekordu);
			$komunikaty[] = array('ok', 'Rekord o id = ' . (int)$idRekordu . ' został usunięty.');
		}


		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir').'/'.$this->modul.'/');
		$o_indexResponse->dodajParametr('link_form', '/'.$this->modul.'/dodaj/');
		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);

		return $o_indexResponse;
	}
};
