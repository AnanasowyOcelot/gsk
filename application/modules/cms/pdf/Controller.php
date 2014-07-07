<?php

class pdf_Controller extends Core_CMS_Module_Controller
{
    public function __construct()
    {
        $modul = 'pdf';
        $mapperClassName = 'Model_Mapper_Pdf';
        parent::__construct($modul, $mapperClassName, array(
            'liczbaNaStrone' => 50
        ));
    }

    /**
     * @param Core_Request $o_requestIn
     * @param Model_DataObject_Pdf $rekord
     * @return object
     */
    protected function handleRecordBeforeSave(Core_Request $o_requestIn, Model_DataObject_Pdf $rekord)
    {
        $rekord->setFiles($o_requestIn->getPliki());
        return $rekord;
    }

    //================================================================================
    /**
     * @param Core_Request $request
     * @return Core_Response
     */
    protected function obslugaFormularza(Core_Request $request)
    {
        $engine_indexResponse = parent::obslugaFormularza($request);

        $mapper = new Model_Mapper_Pdf();
        if ((int)$request->getParametr('id') > 0) {
            $r = $mapper->findOneById($request->getParametr('id'));
        } else {
            $r = $mapper->getNew();
        }

        $kategoriaSelect = produkt_View::wyswietlListeKategorii(0, 1, 0, $r->kategoria_id);
        $engine_indexResponse->dodajParametr('kategoriaSelect', $kategoriaSelect);

        $engine_indexResponse->dodajParametr('pageImagesPaths', $r->getPageImagesPaths());

        return $engine_indexResponse;
    }
}
