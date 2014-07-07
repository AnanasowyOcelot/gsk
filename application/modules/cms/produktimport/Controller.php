<?php
class produktimport_Controller extends Core_ModuleController
{
	public function __construct()
	{
		$this->modul = 'produktimport';
		parent::__construct();
	}

	//================================================================================
	function indexAction(Core_Request $o_requestIn)
	{
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("index");


		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
		$o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
		return $o_indexResponse;
	}

	//================================================================================
	function importujAction(Core_Request $o_requestIn)
	{
		$o_indexResponse = new Core_Response();
		$o_indexResponse->setModuleTemplate("zaimportowany");

		$pliki    = $o_requestIn->getPliki();
		$fileData = $pliki['plikXls'];
		if ($fileData['tmp_name'] != "") {
			$path = $fileData['tmp_name'];

			$importer = new Model_ProduktImportExcel($path);
			$importer->importProducts();
			$numberOfImportedProducts = $importer->getNumberOfImportedProducts();

			if ($numberOfImportedProducts > 0) {
				$komunikaty[] = array('ok', 'Zaimportowano produktów: ' . $numberOfImportedProducts);
			} else {
				$komunikaty[] = array('error', 'Nie zaimportowano żadnych produktów.');
			}
		} else {
			$komunikaty[] = array('error', 'Nie wybrano pliku.');
		}

		$o_indexResponse->dodajParametr('komunikaty', $komunikaty);
		$o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
		$o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
		return $o_indexResponse;
	}
}
