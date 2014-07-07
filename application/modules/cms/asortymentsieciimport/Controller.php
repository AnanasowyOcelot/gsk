<?php
class asortymentsieciimport_Controller extends Core_ModuleController
{
    public function __construct()
    {
        $this->modul = 'asortymentsieciimport';
        parent::__construct();
    }

    //================================================================================
    function indexAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setModuleTemplate("index");

        /*$asortymentSieci = array();
        $mapperKlient = new Model_AsortymentSieci_KlientMapper();
        $mapperAsortymentProdukt = new Model_AsortymentSieci_ProduktMapper();
        $klienci = $mapperKlient->find();
        foreach ($klienci as $klient) {
            $values = $mapperKlient->getValues($klient->id);
            $produkty = array();
            foreach ($values as $value) {
                $asortymentProdukt = $mapperAsortymentProdukt->findOneById($value['produkt_id']);
                $produkt = new Model_Produkt($asortymentProdukt->produktId);
                $asortymentProdukt->imageSmall = 'z1/1/' . $produkt->zdjecie_1;
                $produkty[] = $asortymentProdukt;
            }

            $asortymentSieci[] = array(
                'klientNazwa' => $klient->nazwa,
                'produkty' => $produkty
            );
        }
        $debug = Core_Narzedzia::drukuj($asortymentSieci, 1);
        $o_indexResponse->dodajParametr('debug', $debug);*/

        $o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
        return $o_indexResponse;
    }

    //================================================================================
    function importujAction(Core_Request $o_requestIn)
    {
        $o_indexResponse = new Core_Response();
        $o_indexResponse->setModuleTemplate("zaimportowany");

        $debug = '';

        $pliki    = $o_requestIn->getPliki();
        $fileData = $pliki['plikXls'];
        if ($fileData['tmp_name'] != "") {
            $path = $fileData['tmp_name'];

            $importer = new Model_AsortymentSieci_ImportExcel($path);
            $debug    = $importer->import();
        } else {
            $komunikaty[] = array('error', 'Nie wybrano pliku.');
        }

        $o_indexResponse->dodajParametr('debug', $debug);
        $o_indexResponse->dodajParametr('komunikaty', $komunikaty);
        $o_indexResponse->dodajParametr('link', Core_Config::get('cms_dir') . '/' . $this->modul . '/');
        $o_indexResponse->dodajParametr('uprawnienia', $o_requestIn->getUprawnieniaModul($this->modul));
        return $o_indexResponse;
    }
}
