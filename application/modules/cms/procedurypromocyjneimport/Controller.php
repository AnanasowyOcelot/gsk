<?php
class procedurypromocyjneimport_Controller extends Core_ModuleController
{
    public function __construct()
    {
        $this->modul = 'procedurypromocyjneimport';
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

            $importer = new Model_ProceduryPromocyjne_ImportExcelForProceduryPromocyjne($path);
            $importer->setDocumentName($fileData['name']);
            $importer->import();
        } else {
            $komunikaty[] = array('error', 'Nie wybrano pliku.');
        }

        //$komunikaty = "Zaimportowano plik";
        $komunikaty[] = array('info', 'Zaimportowano plik.');

        $o_indexResponse->dodajParametr('komunikaty', $komunikaty);
        $o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
        return $o_indexResponse;
    }
}
